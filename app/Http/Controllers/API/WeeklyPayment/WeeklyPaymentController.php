<?php

namespace App\Http\Controllers\API\WeeklyPayment;

use App\Http\Controllers\Controller;
use App\Models\Kid;
use App\Models\WeeklyPayment;
use Illuminate\Http\Request;

class WeeklyPaymentController extends Controller
{
    public function createWeeklyPayment(Request $request)
    {
        $parent = auth('parent')->user();
        $request->validate([
            'kid_id' => 'required|exists:kids,id',
            'title' => 'required|string|max:150',
            'amount' => 'required|numeric|min:0',
            'due_in_days' => 'required|integer|min:0',
        ]);
        $kid = Kid::where('id', $request->kid_id)->where('parent_id', $parent->id)->first();

        if (! $kid) {
            return response()->json([
                'message' => 'Kid not found.',
            ], 404);
        }

        $weeklyPayment = WeeklyPayment::create([
            'kid_id' => $request->kid_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'due_in_days' => $request->due_in_days,
            'status' => 'pending',
            'created_by_parent_id' => $parent->id,
        ]);

        return response()->json([
            'message' => 'Weekly payment created successfully!',
            'weekly_payment' => $weeklyPayment,
            'due_days' => $weeklyPayment->due_date,
        ], 201);
    }

    public function payWeeklyPayment($id)
    {
        $kid = auth('kid')->user();
        $payment = WeeklyPayment::where('id', $id)->where('kid_id', $kid->id)->first();

        if (! $payment) {
            return response()->json([
                'message' => 'weekly payment not found',
            ], 400);
        }

        if ($payment->status === 'paid') {
            return response()->json([
                'message' => 'This weekly payment is already paid.',
            ], 400);
        }

        if ($kid->balance < $payment->amount) {
            return response()->json([
                'message' => 'Not enough balance to pay this weekly payment.',
            ], 400);
        }

        $kid->balance -= $payment->amount;
        $kid->save();
        $payment->update([
            'status' => 'paid',
        ]);

        return response()->json([
            'message' => 'Weekly payment successfully paid!',
            'weekly_payment' => $payment,
            'kid_balance' => $kid->balance,
        ], 200);

    }

    public function getKidPayment()
    {
        $kid = auth('kid')->user();
        $payment = WeeklyPayment::where('kid_id', $kid->id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Weekly payment retrieved successfully.',
            'tasks' => $payment,
        ]);
    }
}
