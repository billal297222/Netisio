<?php

namespace App\Http\Controllers\API\ParentMoney;
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

class ParentTransactionController extends Controller
{
    public function deposite(Request $request){
        $parent = auth('parent')->user();

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = $request->amount;
        if($amount > $parent->available_limit){
            return response()->json([
                'status' => 'error',
                'message' => 'Deposit amount exceeds available limit',
            ], 400);
        }

        $parent->balance+=$amount;
        $parent->available_limit -= $amount;
        $parent->save();

        $transaction = ParentTransaction::create([
            'parent_id' => $parent->id,
            'type' => 'deposit',
            'amount' => $amount,
            'max_deposit' => $parent->available_limit,
            'transaction_datetime' => Carbon::now(),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Deposit successful',
            'balance' => $parent->balance,
            'available_limit' => $parent->available_limit,
            'transaction' => $transaction,
        ]);
    }

    public function depositeLimite(){
         $parent = auth('parent')->user();
         $backend = Backend::first();
        $monthly_limit = $backend ? $backend->monthly_limit : 10000.00;

        return response()->json([
            'status' => 'success',
            'monthly_limit' => $monthly_limit,
            'available_limit' => $parent->available_limit,
        ]);
    }

     public  function wallet(){
         $parent = auth('parent')->user();

         if(!$parent){
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found',
            ],401);
         }

         return response()->json([
              'status' => 'success',
              'kid' => [
                'id'=>$parent->id,
                'full_name' => $parent->full_name,
                'kavatar_url'=>$parent->kavatar ? url($parent->kavatar):null,
                'balance'=>number_format($parent->balance,2),
               ]

         ],201);

    }
}
