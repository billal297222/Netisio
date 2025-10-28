<?php

namespace App\Http\Controllers\API\KidMoney;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Task;
use App\Models\KidTransaction;
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
}
