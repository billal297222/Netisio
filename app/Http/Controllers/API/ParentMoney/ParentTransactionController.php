<?php

namespace App\Http\Controllers\API\ParentMoney;

use App\Http\Controllers\Controller;
use App\Models\Backend;
use App\Models\Kid;
use App\Models\KidTransaction;
use App\Models\ParentTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ParentTransactionController extends Controller
{
    use ApiResponse;
    public function deposite(Request $request)
    {

        $parent = auth('parent')->user();

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = $request->amount;
        if ($amount > $parent->available_limit) {

            return $this->error('','Deposit amount exceeds available limit',400);
        }

        $parent->balance += $amount;
        $parent->available_limit -= $amount;
        $parent->save();

        $transaction = ParentTransaction::create([
            'parent_id' => $parent->id,
            'type' => 'deposit',
            'amount' => $amount,
            'max_deposit' => $parent->available_limit,
            'transaction_datetime' => Carbon::now(),
        ]);


        $data = [
            'balance' => $parent->balance,
            'available_limit' => $parent->available_limit,
            'transaction' => $transaction,
        ];
        return $this->success($data,'Deposit successful',200);
    }

    public function depositeLimite()
    {
        $parent = auth('parent')->user();
        $backend = Backend::first();
        $monthly_limit = $backend ? $backend->monthly_limit : 10000.00;

        $data = [
            'monthly_limit' => $monthly_limit,
            'available_limit' => $parent->available_limit,
        ];
         return $this->success($data,'Monthly limit',200);
    }

    public function wallet()
    {
        $parent = auth('parent')->user();

        if (! $parent) {

            return $this->success('','user not found',401);
        }

        $data = [
                'id' => $parent->id,
                'full_name' => $parent->full_name,
                'kavatar_url' => $parent->kavatar ? url($parent->kavatar) : null,
                'balance' => number_format($parent->balance, 2),
        ];
        return $this->success($data,'Kids Wallet',200);

    }

    public function transferMoney(Request $request)
    {
        $parent = auth('parent')->user();
        $request->validate([
            'kid_id' => 'required|exists:kids,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'string|nullable',
        ]);

        $kid = Kid::where('id', $request->kid_id)->where('parent_id', $parent->id)->first();
        if (! $kid) {

            return $this->success('','kids not found',401);
        }

        if ($parent->balance < $request->amount) {

            return $this->success('','Insufficient balance',401);
        }

        $parent->balance -= $request->amount;
        $parent->save();
        $kid->balance += $request->amount;
        $kid->save();

        $Ptransaction = ParentTransaction::create([
            'parent_id' => $parent->id,
            'kid_id' => $kid->id,
            'type' => 'transfer',
            'amount' => $request->amount,
            'message' => $request->note,
            'transaction_datetime' => Carbon::now(),
        ]);

        $Ktransaction = KidTransaction::create([
            'kid_id' => $kid->id,
            'sender_parent_id' => $parent->id,
            'type' => 'request',
            'amount' => $request->amount,
            'note' => $request->note,
            'transaction_datetime' => Carbon::now(),
        ]);

         $data = [
            'amount' => number_format($request->amount, 2),
         ];

         return $this->success($data,'Money sent successfully.',200);

    }

    public function getParentTransactions()
    {
         $parent = auth('parent')->user();
        $transactions = ParentTransaction::with('kid')->where('parent_id', $parent->id)->latest()->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'type' => ucfirst($t->type),
                    'amount' => number_format($t->amount, 2),
                    'message' => $t->message,
                    'kid_name' => $t->kid?->full_name,
                    'date' => $t->transaction_datetime->format('Y-m-d'),
                    'time' => $t->transaction_datetime->format('H:i:s'),
                ];
            });

        return $this->success($transactions,'Parent transactions',200);
    }
}
