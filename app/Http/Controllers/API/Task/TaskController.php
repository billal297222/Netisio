<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\Controller;
use App\Models\Kid;
use App\Models\Task;
use App\Services\FcmService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class TaskController extends Controller
{
    use ApiResponse;
    public function createTask(Request $request)
    {
        $parent = auth('parent')->user();
        $request->validate([
            'kid_id' => 'required|exists:kids,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:200',
            'reward_amount' => 'required|numeric|min:0',
        ]);

        $kid = Kid::where('id', $request->kid_id)->where('parent_id', $parent->id)->first();
        if (! $kid) {
            return response()->json([
                'message' => 'kids not found',
            ], 404);
        }

        $task = Task::create([
            'kid_id' => $request->kid_id,
            'title' => $request->title,
            'description' => $request->description,
            'reward_amount' => $request->reward_amount,
            'status' => 'not_started',
            'due_date' => Carbon::today(),
            'created_by_parent_id' => $parent->id,
        ]);

        return response()->json([
            'message' => 'task created successfully',
            'task' => $task,
        ], 201);
    }

    public function startTask($taskId)
    {
        $kid = auth('kid')->user();
        $task = Task::where('id', $taskId)->where('kid_id', $kid->id)->first();

        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ]);
        }

        if ($task->status !== 'not_started') {
            return response()->json([
                'message' => 'Task already started',
            ]);

        }
        $task->update([
            'status' => 'in_progress',
        ]);

        return response()->json([
            'message' => 'Task started successfully!',
            'task' => $task,
        ], 200);
    }

    public function completeTask($taskId)
    {
        $kid = auth('kid')->user();
        $task = Task::where('id', $taskId)->where('kid_id', $kid->id)->first();

        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ]);
        }

        if ($task->status !== 'in_progress') {
            return response()->json([
                'message' => 'You have to start the task',
            ]);

        }
        $task->update([
            'status' => 'completed',
        ]);


        // Use FcmService-------------------------------------
        try {
            $fcmService = new FcmService;

            // Send to the kid
            $fcmService->sendToToken(
                $kid->fcm_token,
                'Task Completed!',
                'You earned '.number_format($task->reward_amount, 2).' for the task "'.$task->title.'"'
            );

            // Send to the parent
            if ($kid->parent && $kid->parent->fcm_token) {
                $fcmService->sendToToken(
                    $kid->parent->fcm_token,
                    $kid->full_name.' completed a task!',
                    'Earned: '.number_format($task->reward_amount, 2).' for "'.$task->title.'"'
                );
            }
        } catch (\Exception $e) {
            \Log::error('FCM Error: '.$e->getMessage());
        }

        // ---------------------------


        return response()->json([
            'message' => 'Task completed successfully!',
            'task' => $task,
        ], 200);
    }

    public function rewardCollected($taskId)
    {
        $kid = auth('kid')->user();
        $task = Task::where('id', $taskId)->where('kid_id', $kid->id)->first();

        if (! $task) {
            return response()->json([
                'message' => 'Task not found',
            ]);
        }

        if ($task->status !== 'completed') {
            return response()->json([
                'message' => 'You have to complete the task',
            ]);

        }

        $task->update([
            'status' => 'reward_collected',
        ]);

        $kid->balance += $task->reward_amount;
        $kid->save();

        return response()->json([
            'message' => 'Rewarded collected successfully!',
            'task' => $task,
            'new_balance' => $kid->balance,
        ], 200);
    }

    public function getKidTasks()
    {
        $kid = auth('kid')->user();
        $tasks = Task::where('kid_id', $kid->id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'Tasks retrieved successfully.',
            'tasks' => $tasks,
        ]);
    }
}
