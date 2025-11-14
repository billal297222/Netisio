<?php

namespace App\Http\Controllers\API\KidMoney;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Task;
use App\Models\Backend;
use App\Models\KidTransaction;
use App\Models\ParentTransaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Services\FcmService;
use App\Traits\ApiResponse;

class KidTransactionController extends Controller
{
    use ApiResponse;

    public function sendMoney(Request $request)
    {
        $kid = auth('kid')->user();

        $request->validate([
            'receiver_unique_id' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($kid->balance < $request->amount) {
            return $this->error('', 'Insufficient balance', 400);
        }

        $receiverKid = Kid::where('k_unique_id', $request->receiver_unique_id)->first();
        $receiverParent = null;

        if (!$receiverKid) {
            $receiverParent = ParentModel::where('p_unique_id', $request->receiver_unique_id)->first();
            if (!$receiverParent) {
                return $this->error('', 'Receiver not found', 404);
            }
        }

        $kid->balance -= $request->amount;
        $kid->save();

        if ($receiverKid) {
            $receiverKid->balance += $request->amount;
            $receiverKid->save();
        } elseif ($receiverParent) {
            $receiverParent->balance += $request->amount;
            $receiverParent->save();
        }

        $transaction = KidTransaction::create([
            'kid_id' => $kid->id,
            'receiver_kid_id' => $receiverKid ? $receiverKid->id : null,
            'sender_parent_id' => $receiverParent ? $receiverParent->id : null,
            'type' => 'send',
            'amount' => $request->amount,
            'status' => 'completed',
        ]);

        return $this->success($transaction, 'Money sent successfully', 201);
    }

    public function requestMoney(Request $request)
    {
        $kid = auth('kid')->user();

        $request->validate([
            'money' => 'required|numeric|min:0.01',
            'note' => 'string|nullable',
        ]);

        $parent = $kid->parent;

        // Send FCM notification to parent
        try {
            $fcmService = new FcmService();
            $fcmService->sendToToken(
                $parent->fcm_token,
                $kid->full_name . ' requested money!',
                'Amount: ' . number_format($request->money, 2) . ($request->note ? ' - Note: ' . $request->note : '')
            );
        } catch (\Exception $e) {
            \Log::error('FCM Error: ' . $e->getMessage());
        }

        return $this->success('', 'Money request sent to parent successfully', 200);
    }

    public function sendUsers()
    {
        $kid = auth('kid')->user();

        $transactions = KidTransaction::where('kid_id', $kid->id)
            ->where('type', 'send')
            ->with(['receiverKid', 'receiverParent'])
            ->orderBy('transaction_date', 'desc')
            ->get();

        $result = $transactions->map(function ($tx) {
            $receiverType = $tx->receiverKid ? 'kid' : ($tx->receiverParent ? 'parent' : 'unknown');
            $receiverId = $tx->receiverKid?->id ?? $tx->receiverParent?->id ?? null;
            $receiverName = $tx->receiverKid?->full_name ?? $tx->receiverParent?->full_name ?? 'N/A';
            $receiverAvatar = $tx->receiverKid ? ($tx->receiverKid->kavatar ? asset($tx->receiverKid->kavatar) : null)
                                               : ($tx->receiverParent ? ($tx->receiverParent->pavatar ? asset($tx->receiverParent->pavatar) : null) : null);

            return [
                'transaction_id' => $tx->id,
                'amount' => $tx->amount,
                'date' => $tx->transaction_date,
                'receiver_id' => $receiverId,
                'avatar' => $receiverAvatar,
            ];
        });

        return $this->success($result, 'Transactions send users', 200);
    }

    public function wallet()
    {
        $kid = auth('kid')->user();

        if (!$kid) {
            return $this->error('', 'User not found', 404);
        }

        $data = [
            'id' => $kid->id,
            'full_name' => $kid->full_name,
            'kavatar_url' => $kid->kavatar ? asset($kid->kavatar) : null,
            'balance' => number_format($kid->balance, 2),
            'today_can_spend' => number_format($kid->today_can_spend, 2),
        ];

        return $this->success($data, 'Wallet info', 200);
    }

    public function getKidTransaction(Request $request, $kid_id)
    {
        $kid = auth('kid')->user();

        if ($kid->id != $kid_id) {
            return $this->error('', 'Unauthorized access to transactions.', 403);
        }

        $transactions = KidTransaction::with(['goal', 'senderParent', 'receiverKid', 'kid'])
            ->where(function ($query) use ($kid_id) {
                $query->where('kid_id', $kid_id)
                      ->orWhere('receiver_kid_id', $kid_id);
            })
            ->latest()
            ->get()
            ->map(function ($t) use ($kid_id) {
                $isSender = $t->kid_id === $kid_id;
                $direction = $isSender ? 'Sent' : 'Received';
                $relatedName = null;
                $relatedAvatar = null;

                if (in_array($t->type, ['saving', 'refund'])) {
                    $relatedName = $t->goal->title ?? 'Saving Goal';
                    $relatedAvatar = null;
                } elseif ($isSender && $t->receiverKid) {
                    $relatedName = $t->receiverKid->full_name;
                    $relatedAvatar = $t->receiverKid->kavatar ? asset($t->receiverKid->kavatar) : null;
                } elseif (!$isSender && $t->kid) {
                    $relatedName = $t->kid->full_name;
                    $relatedAvatar = $t->kid->kavatar ? asset($t->kid->kavatar) : null;
                } elseif (!$isSender && $t->senderParent) {
                    $relatedName = $t->senderParent->full_name;
                    $relatedAvatar = $t->senderParent->pavatar ? asset($t->senderParent->pavatar) : null;
                }

                return [
                    'id' => $t->id,
                    'type' => ucfirst($t->type),
                    'amount' => number_format($t->amount, 2),
                    'direction' => $direction,
                    'related_name' => $relatedName,
                    'avatar' => $relatedAvatar,
                    'date' => $t->created_at->format('Y-m-d'),
                    'time' => $t->created_at->format('H:i:s'),
                ];
            });

        return $this->success($transactions, 'Your Transactions', 200);
    }

    public function familyMember()
    {
        $user = auth('parent')->user() ?? auth('kid')->user();

        if (!$user) {
            return $this->error('', 'Unauthenticated', 401);
        }

        // Determine if parent or kid
        if (auth('parent')->check()) {
            $family = Family::where('created_by_parent', $user->id)
                            ->with('kids')
                            ->first();
        } else {
            $family = Family::where('id', $user->family_id)
                            ->with('kids')
                            ->first();
        }

        if (!$family) {
            return $this->success([], 'No family found', 200);
        }

        $members = [];

        $parents = ParentModel::where('id', $family->created_by_parent)
                        ->when(auth('parent')->check(), function ($q) use ($user) {
                            $q->where('id', '!=', $user->id);
                        })
                        ->get();

        foreach ($parents as $p) {
            $members[] = [
                'type' => 'parent',
                'id' => $p->id,
                'unique_id' => $p->p_unique_id,
                'name' => $p->full_name,
                'avatar' => $p->pavatar ? asset($p->pavatar) : null, // parent avatar path
            ];
        }

        foreach ($family->kids as $kid) {
            if (auth('kid')->check() && $kid->id == $user->id) continue;

            $members[] = [
                'type' => 'kid',
                'id' => $kid->id,
                'unique_id' => $kid->k_unique_id,
                'name' => $kid->full_name ?? $kid->username,
                'avatar' => $kid->kavatar ? asset($kid->kavatar) : null, // kid avatar path
            ];
        }

        return $this->success($members, 'Family members list', 200);
    }
}
