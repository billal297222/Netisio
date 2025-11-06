<?php

namespace App\Http\Controllers\API\SavingGoals;

use App\Http\Controllers\Controller;
use App\Models\Kid;
use App\Models\KidTransaction;
use App\Models\Saving;
use Illuminate\Http\Request;
use App\Services\FcmService;
use App\Traits\ApiResponse;

class SavingGoalController extends Controller
{
    use ApiResponse;
    public function createGoal(Request $request)
    {

        $request->validate([
            'kid_id' => 'nullable|exists:kids,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:200',
            'target_amount' => 'required|numeric|min:0.01',
        ]);

        $kid = null;
        $createdByParentId = null;

        if (auth('kid')->check()) {
            $kid = auth('kid')->user();

        } elseif (auth('parent')->check()) {
            $parent = auth('parent')->user();

            if (! $request->kid_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'kid_id is required when parent creates a goal.',
                ], 422);
            }

            $kid = Kid::where('id', $request->kid_id)
                ->where('parent_id', $parent->id)
                ->first();

            if (! $kid) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid kid or kid not associated with this parent.',
                ], 403);
            }

            $createdByParentId = $parent->id;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized user.',
            ], 401);
        }

        $goal = Saving::create([
            'kid_id' => $kid->id,
            'title' => $request->title,
            'description' => $request->description ?? '',
            'target_amount' => $request->target_amount,
            'saved_amount' => 0.00,
            'status' => 'in_progress',
            'created_by_parent_id' => $createdByParentId,
        ]);


        if(!$parent){
             // Use FcmService-------------------------------------
        try {
            $fcmService = new FcmService;

            // Send to the parent
            if ($kid->parent && $kid->parent->fcm_token) {
                $fcmService->sendToToken(
                    $kid->parent->fcm_token,
                    $kid->full_name.' created a Goal!',
                     'Goal: "' . $goal->title . '" with target amount: ' . number_format($goal->target_amount, 2)
                );
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }

        // ---------------------------
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Saving goal created successfully.',
            'goal' => $goal,
        ]);
    }

    // AddMoneyToGoal

    public function AddMoneyToGoal(Request $request, $goal_id)
    {

        $kid = auth('kid')->user();

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $goal = Saving::where('id', $goal_id)->where('kid_id', $kid->id)->first();
        if (! $goal) {
            return response()->json([
                'status' => 'error',
                'message' => ' Goals not found.',
            ], 401);
        }

        if ($goal->status === 'completed') {


        // Use FcmService-------------------------------------
        try {
            $fcmService = new FcmService;

            // Send to the kid
            $fcmService->sendToToken(
                $kid->fcm_token,
                'Goal Completed!',
                'You  Completed the Goal "'.$goal->title.'"'
            );

            // Send to the parent
            if ($kid->parent && $kid->parent->fcm_token) {
                $fcmService->sendToToken(
                    $kid->parent->fcm_token,
                    $kid->full_name.' completed a Goal!',
                    'The Completed is "'.$goal->title.'"'
                );
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }

        // ---------------------------


            return response()->json([
                'status' => 'error',
                'message' => ' Goals already completed',
            ], 201);
        }

        $newAmount = $goal->saved_amount + $request->amount;
        if ($newAmount > $goal->target_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Amount exceeds target goal. You can add up to '
                        .number_format($goal->target_amount - $goal->saved_amount, 2).' more.',
            ], 400);
        }

        $goal->saved_amount = $newAmount;

        if ($newAmount >= $goal->target_amount) {
            $goal->status = 'completed';
        }
        $goal->save();

        $progress = round(($goal->saved_amount / $goal->target_amount) * 100, 2);

        KidTransaction::create([
            'kid_id' => $kid->id,
            'type' => 'saving',
            'saving_goal_id' => $goal->id,
            'amount' => $request->amount,
            'status' => 'completed',
            'transaction_date' => now(),
            'note' => 'Added to saving goal: '.$goal->title,
            'progress_percentage' =>$progress,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'amount added successfully',
            'goal' => [
                'id' => $goal->id,
                'title' => $goal->title,
                'saved_amount' => number_format($goal->saved_amount, 2),
                'target_amount' => number_format($goal->target_amount, 2),
                'status' => $goal->status,
                'progress_percentage' => $progress,
            ],
        ]);

    }

    //  collectGoal
    public function collectGoal(Request $request, $goal_id)
    {

        $kid = auth('kid')->user();

        $request->validate([
            'action' => 'required|in:yes,cancel',
        ]);

        $goal = Saving::where('id', $goal_id)->where('kid_id', $kid->id)->firstOrFail();

        if ($goal->status === 'collected') {
            return response()->json([
                'status' => 'error',
                'message' => 'Goal already collected.',
            ], 400);
        }

        if ($goal->status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Goal is not completed yet.',
            ], 400);
        }

        if ($request->action === 'yes') {

            $goal->status = 'collected';
            $goal->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Goal collected successfully',
                'goal' => $goal->title,
            ]);
        } elseif ($request->action === 'cancel') {
            $kid->balance += $goal->saved_amount;
            $kid->save();
            $goal->status = 'collected';
            $goal->save();

        KidTransaction::create([
            'kid_id' => $kid->id,
            'type' => 'refund',
            'saving_goal_id' => $goal->id,
            'amount' => $goal->saved_amount,
            'status' => 'completed',
            'note' => 'Goal cancelled, amount refunded: ' . $goal->title,
            'transaction_date' => now(),
        ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Goal cancelled. Amount returned to your balance.',
                'goal' => $goal,
                'balance' => number_format($kid->balance, 2),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid action',
        ], 400);

    }
}
