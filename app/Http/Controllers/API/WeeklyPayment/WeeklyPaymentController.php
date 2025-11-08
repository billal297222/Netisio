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

            return $this->error('','Kid not Found',404);
        }

        $weeklyPayment = WeeklyPayment::create([
            'kid_id' => $request->kid_id,
            'title' => $request->title,
            'amount' => $request->amount,
            'due_in_days' => $request->due_in_days,
            'status' => 'pending',
            'created_by_parent_id' => $parent->id,
        ]);

        $data = [
            'weekly_payment' => $weeklyPayment,
            'kid_avatar' => $kid->kavatar ? url($kid->kavatar) : null, // added avatar path
            'due_days' => $weeklyPayment->due_date,
        ];
        return $this->success($data,'Weekly payment created successfully!',201);
    }

    public function payWeeklyPayment($id)
    {
        $kid = auth('kid')->user();
        $payment = WeeklyPayment::where('id', $id)->where('kid_id', $kid->id)->first();

        if (! $payment) {

            return $this->error('','weekly payment not found',404);
        }

        if ($payment->status === 'paid') {

            return $this->error('','This weekly payment is already paid.',400);
        }

        if ($kid->balance < $payment->amount) {

            return $this->error('','Not enough balance to pay this weekly payment.',400);
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
                'Paid '.number_format($payment->amount, 2).' for the payment "'.$payment->title.'"'
            );

            // Send to the parent
            if ($kid->parent && $kid->parent->fcm_token) {
                $fcmService->sendToToken(
                    $kid->parent->fcm_token,
                    $kid->full_name.' completed a weekly payment!',
                    'Paid: '.number_format($payment->amount, 2).' for "'.$payment->title.'"'
                );
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }
        // ---------------------------

        $data = [
            'weekly_payment' => $payment,
            'kid_balance' => $kid->balance,
            'kid_avatar' => $kid->kavatar ? url($kid->kavatar) : null, // added avatar path
        ];
        return $this->success($data,'Weekly payment successfully paid!',200);
    }

    public function getKidPayment()
    {
        $kid = auth('kid')->user();
        $payment = WeeklyPayment::where('kid_id', $kid->id)->orderBy('created_at', 'desc')->get()
            ->map(function($p) use ($kid) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'amount' => $p->amount,
                    'due_in_days' => $p->due_in_days,
                    'status' => $p->status,
                    'kid_avatar' => $kid->kavatar ? url($kid->kavatar) : null, // added avatar path
                ];
            });

        return $this->success($payment,'Weekly payment retrieved successfully.',200);
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
                'Needs '.number_format($payment->amount, 2).' to pay "'.$payment->title.'"'
            );
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }
    }
}
