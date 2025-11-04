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

class KidTransactionController extends Controller
{
    public function sendMoney(Request $request)
    {
        $kid = auth('kid')->user();

        $request->validate([
            'receiver_unique_id' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($kid->balance < $request->amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance',
            ], 400);
        }
        $receiverKid = Kid::where('k_unique_id', $request->receiver_unique_id)->first();
        $receiverParent = null;

        if (! $receiverKid) {
            $receiverParent = ParentModel::where('p_unique_id', $request->receiver_unique_id)->first();
            if (! $receiverParent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Receiver not found',
                ], 404);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Money sent successfully',
            'transaction' => $transaction,
        ]);

    }

    public function sendUsers()
    {
        $kid = auth('kid')->user();
        $transections = KidTransaction::where('kid_id', $kid->id)->where('type', 'send')
            ->with(['receiverKid', 'receiverParent'])->orderBy('transaction_date', 'desc')->get();

        $result = $transections->map(function ($tx) {
            return [
                'transaction_id' => $tx->id,
                'amount' => $tx->amount,
                'status' => $tx->status,
                'date' => $tx->transaction_date,
                'receiver_type' => $tx->receiverKid ? 'kid' : 'parent',
                'receiver_id' => $tx->receiverKid ? $tx->receiverKid->id : $tx->receiverParent->id,
                'receiver_name' => $tx->receiverKid ? $tx->receiverKid->full_name : $tx->receiverParent->full_name,
            ];
        });

        return response()->json([
            'status' => 'success',
            'send_users' => $result,
        ]);
    }

    public function wallet()
    {
        $kid = auth('kid')->user();

        if (! $kid) {
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'kid' => [
                'id' => $kid->id,
                'full_name' => $kid->full_name,
                'kavatar_url' => $kid->kavatar ? url($kid->kavatar) : null,
                'balance' => number_format($kid->balance, 2),
                'today_can_spend' => number_format($kid->today_can_spend, 2),
            ],

        ], 201);

    }

   public function getKidTransaction(Request $request, $kid_id)
{
    $kid = auth('kid')->user();

    // Make sure the logged-in kid can only access their own transactions
    if ($kid->id != $kid_id) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized access to transactions.'
        ], 403);
    }

    $transactions = KidTransaction::with([
            'goal',
            'senderParent',
            'receiverKid',
            'kid'
        ])
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
                $relatedAvatar = $t->receiverKid->kavatar;
            } elseif (!$isSender && $t->kid) {
                $relatedName = $t->kid->full_name;
                $relatedAvatar = $t->kid->kavatar;
            } elseif (!$isSender && $t->senderParent) {
                $relatedName = $t->senderParent->full_name;
                $relatedAvatar = null;
            }

            return [
                'id' => $t->id,
                'type' => ucfirst($t->type),
                'amount' => number_format($t->amount, 2),
                'status' => ucfirst($t->status),
                'direction' => $direction,
                'related_name' => $relatedName,
                'avatar' => $relatedAvatar,
                'goal_title' => $t->goal->title ?? null,
                'date' => $t->created_at->format('Y-m-d'),
                'time' => $t->created_at->format('H:i:s'),
            ];
        });

    return response()->json([
        'status' => 'success',
        'transactions' => $transactions
    ]);
}

}
