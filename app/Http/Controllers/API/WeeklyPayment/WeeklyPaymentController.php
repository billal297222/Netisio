<?php

namespace App\Http\Controllers\API\WeeklyPayment;

use App\Http\Controllers\Controller;
use App\Models\Kid;
use App\Models\WeeklyPayment;
use App\Services\FcmService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class WeeklyPaymentController extends Controller
{
    use ApiResponse;
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

        // Use FcmService-------------------------------------
        try {
            $fcmService = new FcmService;

            // Send to the kid
            $fcmService->sendToToken(
                $kid->fcm_token,
                'Weekly Payment Completed!',
                'Paid '.number_format($task->amount, 2).' for the payment "'.$payment->title.'"'
            );

            // Send to the parent
            if ($kid->parent && $kid->parent->fcm_token) {
                $fcmService->sendToToken(
                    $kid->parent->fcm_token,
                    $kid->full_name.' completed a task!',
                    'paid: '.number_format($payment->amount, 2).' for "'.$payment->title.'"'
                );
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }

        // ---------------------------

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

    public function requestMoneyPayment(Request $request, $payment_id)
    {
        $kid = auth('kid')->user();
        $payment = WeeklyPayment::where('id', $payment_id)->where('kid_id', $kid->id)->first();
        $parent = $kid->parent;

        $need = $payment->amount - $kid->balance;

        // Send FCM notification to parent
        try {
            $fcmService = new FcmService;
            $fcmService->sendToToken(
                $parent->fcm_token,
                $kid->full_name.' Out of money!',
                'Need'.number_format($payment->amount, 2).' to payment "'.$payment->title.'"'
            );
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }

    }
}
