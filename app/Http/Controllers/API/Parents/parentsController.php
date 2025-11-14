<?php

namespace App\Http\Controllers\API\Parents;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Saving;
use App\Models\Task;
use App\Models\WeeklyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Traits\ApiResponse;

class parentsController extends Controller
{
    use ApiResponse;
    public function ParentProfileEdit(Request $request)
    {
        $parent = auth('parent')->user(); // JWT-authenticated parent

        // if (!$parent) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized or invalid token',
        //     ], 401);
        // }

        $request->validate([
            'full_name' => 'nullable|string|max:100',
            'favatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->has('full_name')) {
            $parent->full_name = $request->full_name;
            $parent->save();
        }

        if ($request->hasFile('favatar')) {
            $family = Family::where('created_by_parent', $parent->id)->first();

            if (! $family) {
                return $this->error('','No family found for this parent',404);
            }

            $file = $request->file('favatar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('family_avatars/favatar'), $filename);
            $family->favatar = url('family_avatars/favatar/'.$filename);
            $family->save();
        }

        return $this->success($parent,'Profile updated successfully',200);
    }

    public function changePassword(Request $request)
    {
        $parent = auth('parent')->user();

        if (! $parent) {

            return $this->error('','Unauthorized or invalid token',401);
        }

        $request->validate([
            'current_password' => 'required|string|min:1',
            'new_password' => 'required|string|min:1|confirmed',
        ]);

        if (! Hash::check($request->current_password, $parent->password)) {

             return $this->error('','Current password is incorrect',401);
        }

        $parent->password = Hash::make($request->new_password);
        $parent->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);
        return $this->success('','Password changed successfully',200);
    }

    // public function myFamily(Request $request)
    // {
    //     $parent = auth('parent')->user();

    //     if (! $parent) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Unauthorized or invalid token',
    //         ], 401);
    //     }

    //     $families = Family::with([
    //         'parent:id,full_name,p_unique_id,pavatar',
    //         'kids:id,full_name,k_unique_id,family_id,parent_id,kavatar',
    //     ])->where('created_by_parent', $parent->id)->get();

    //     return response()->json([
    //         'status' => 'success',
    //         'families' => $families,
    //     ]);
    // }

    public function myFamily(Request $request)
{
    $parent = auth('parent')->user();
    $kid = auth('kid')->user();

    if (! $parent && ! $kid) {
        return $this->error('', 'Unauthorized or invalid token', 401);
    }

    $family = null;

    if ($parent) {
        $family = Family::with([
            'kids:id,full_name,k_unique_id,family_id,kavatar',
            'parent:id,full_name,p_unique_id,pavatar'
        ])
        ->where('created_by_parent', $parent->id)
        ->first();
    } else {
        $family = Family::with([
            'kids:id,full_name,k_unique_id,family_id,kavatar',
            'parent:id,full_name,p_unique_id,pavatar'
        ])
        ->where('id', $kid->family_id)
        ->first();
    }

    if (! $family) {
        return $this->error('', 'Family not found', 404);
    }

    $members = collect([]);

    if ($family->parent) {
        $members->push([
            'id' => $family->parent->id,
            'name' => $family->parent->full_name,
            'unique_id' => $family->parent->p_unique_id,
            'avatar' => $family->parent->pavatar ? url($family->parent->pavatar) : null,
            'role' => 'parent',
        ]);
    }

    foreach ($family->kids as $k) {
        $members->push([
            'id' => $k->id,
            'name' => $k->full_name,
            'unique_id' => $k->k_unique_id,
            'avatar' => $k->kavatar ? url($k->kavatar) : null,
            'role' => 'kid',
        ]);
    }

    $data = [
        'family_name' => $family->name,
        'family_avatar' => $family->favatar,
        'total_members' => $members->count(),
        'members' => $members,
    ];

    return $this->success($data, 'Family Member list', 200);
}


    public function createGoal(Request $request)
    {
        $parent = auth('parent')->user();

        $request->validate([
            'kid_id' => 'required|exists:kids,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string|max:200',
            'target_amount' => 'required|numeric|min:0.01',
        ]);

        $kid = Kid::where('id', $request->kid_id)
            ->where('parent_id', $parent->id)
            ->first();

        if (! $kid) {

             return $this->error('','Invalid kid ID or kid does not belong to this parent',403);
        }

        $goal = Saving::create([
            'kid_id' => $kid->id,
            'title' => $request->title,
            'description' => $request->description,
            'target_amount' => $request->target_amount,
            'saved_amount' => 0.00,
            'status' => 'in_progress',
            'created_by_parent_id' => $parent->id,
        ]);


        return $this->success($goal,'Saving goal created successfully for kid: '.$kid->full_name,201);
    }

    // kid info -----------------------------------------------------

    public function getKidInfo($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = $parent->kids()->select('id', 'full_name', 'k_unique_id', 'kavatar', 'balance', 'today_can_spend', 'family_id')
            ->with('family:id,name,favatar')->where('id', $kid_id)->first();
        if (! $kid) {

             return $this->error('','kids not found',404);
        }

        $data = [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'unique_id' => $kid->unique_id,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'balance' => number_format($kid->balance, 2),
                'today_can_spend' => number_format($kid->today_can_spend, 2),
        ];
        return $this->success($data,'Kid Information',200);

    }

    public function getAssignTask($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)->where('parent_id', $parent->id)->first();

        if (! $kid) {

            return $this->error('','kids not found',404);
        }
        $tasks = Task::where('kid_id', $kid->id)->latest()->get(['id', 'title', 'description', 'reward_amount', 'status']);

         $data = [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'tasks' => $tasks,
             ];
             return $this->success($data,'Assign Task to the kids',200);

    }

    //---------------------------------- 5 tarikh

    public function getAssignGoal($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)
            ->where('parent_id', $parent->id)
            ->first();

        if (! $kid) {
           return $this->error('','kids not found',404);
        }

        $savings = Saving::where('kid_id', $kid->id)
            ->latest()
            ->get(['id', 'title', 'description', 'saved_amount', 'target_amount', 'status'])
            ->map(function ($saving) {
                $percentage = null;
                if ($saving->status === 'in progress' && $saving->target_amount > 0) {
                    $percentage = round(($saving->saved_amount / $saving->target_amount) * 100, 2);
                }

                return [
                    'id' => $saving->id,
                    'title' => $saving->title,
                    'description' => $saving->description,
                    'saved_amount' => $saving->saved_amount,
                    'target_amount' => $saving->target_amount,
                    'status' => ucfirst($saving->status),
                    'progress_percentage' => $percentage,
                ];
            });

        $kidInf = [
            'id' => $kid->id,
            'name' => $kid->full_name,
            'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
        ];
        $data = [
            'kidInf' => $kidInf,
             'savings' => $savings,
        ];
        return $this->success($data,'Assign goals to the kids',200);
    }

    public function getAssignPayment($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)->where('parent_id', $parent->id)->first();

        if (! $kid) {

            return $this->error('','kids not found',404);
        }
        $payments = WeeklyPayment::where('kid_id', $kid->id)->latest()->get(['id', 'title', 'amount', 'due_in_days', 'status'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'amount' => number_format($p->amount, 2),
                    'status' => ucfirst($p->status),
                    'due_in_days' => $p->due_in_days,
                ];
            });

        $kidInf = [
            'id' => $kid->id,
            'name' => $kid->full_name,
            'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
        ];
        $data = [
            'kidInf' => $kidInf,
            'payments' => $payments,
        ];
        return $this->success($data,'Assign payment to the kids',200);
    }

    public function AssignAllTask()
    {
        $parent = auth('parent')->user();

        $tasks = Task::with(['kid:id,full_name,kavatar'])
            ->where('created_by_parent_id', $parent->id)
            ->latest()
            ->get(['id', 'kid_id', 'title', 'description', 'reward_amount', 'status', 'due_date'])
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'reward_amount' => number_format($task->reward_amount, 2),
                    'status' => ucfirst($task->status),
                    'kid' => $task->kid ? [
                        'id' => $task->kid->id,
                        'name' => $task->kid->full_name,
                        'avatar' => $task->kid->kavatar ? url($task->kid->kavatar) : null,
                    ] : null,
                ];
            });

        return $this->success($tasks,'Assign all tasks to kids',200);
    }

    public function AssignAllGoal()
    {
        $parent = auth('parent')->user();

        $saving = Saving::with(['kid:id,full_name,kavatar'])
            ->where('created_by_parent_id', $parent->id)->latest()
            ->get(['id', 'kid_id', 'title', 'description', 'saved_amount', 'target_amount', 'status'])
            ->map(function ($saving) {
                $percentage = null;
                if ($saving->status === 'in progress' && $saving->target_amount > 0) {
                    $percentage = round(($saving->saved_amount / $saving->target_amount) * 100, 2);
                }

                return [
                    'id' => $saving->id,
                    'title' => $saving->title,
                    'description' => $saving->description,
                    'saved_amount' => $saving->saved_amount,
                    'target_amount' => $saving->target_amount,
                    'status' => ucfirst($saving->status),
                    'progress_percentage' => $percentage,
                    'kid' => $saving->kid ? [
                        'id' => $saving->kid->id,
                        'name' => $saving->kid->full_name,
                        'avatar' => $saving->kid->kavatar ? url($saving->kid->kavatar) : null,
                    ] : null,
                ];
            });


        return $this->success($saving,'Assign all goals to kids',200);
    }

    public function AssignAllPayment()
    {
        $parent = auth('parent')->user();

        $payments = WeeklyPayment::with(['kid:id,full_name,kavatar'])
            ->where('created_by_parent_id', $parent->id)->latest()->get(['id', 'kid_id', 'title', 'amount', 'due_in_days', 'status'])
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'amount' => number_format($p->amount, 2),
                    'status' => ucfirst($p->status),
                    'due_in_days' => $p->due_in_days,
                    'kid' => $p->kid ? [
                        'id' => $p->kid->id,
                        'name' => $p->kid->full_name,
                        'avatar' => $p->kid->kavatar ? url($p->kid->kavatar) : null,
                    ] : null,
                ];
            });

        return $this->success($payments,'Assign all payemnts to kids',200);
    }

    public function allMemberAssign()
    {
        $parent = auth('parent')->user();
        $membar = Kid::where('parent_id', $parent->id)
            ->select('id', 'full_name', 'kavatar')->get()
            ->map(function ($kid) {
                return [
                    'id' => $kid->id,
                    'name' => $kid->full_name,
                    'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                ];
            });

        return $this->success($membar,'All Family member',200);
    }

    public function myProfile()
    {
        $parent = auth('parent')->user();
        $kid = auth('kid')->user();

        if ($parent) {
            $familyMembersCount = 1+$parent->kids()->count();

            $profile = [
                    'id' => $parent->id,
                    'name' => $parent->full_name,
                    'unique_id' => $parent->p_unique_id,
                    'avatar' => $parent->pavatar ? url($parent->pavatar) : null,
            ];
            $data = [
                'profile'=>$profile,
                'total_family_members' => $familyMembersCount,
            ];
            return $this->success($data,'My profile (parent)',200);
        }

        if ($kid) {
            $family = Family::withCount('kids')->find($kid->family_id);

            $totalMembers = 1 + ($family?->kids_count ?? 0);

             $profile = [
                    'id' => $kid->id,
                    'name' => $kid->full_name,
                    'unique_id' => $kid->k_unique_id,
                    'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
            ];
            $data = [
                'profile'=>$profile,
                'total_family_members' => $totalMembers,
            ];
            return $this->success($data,'My profile (kids)',200);
        }

        return $this->error('','Unauthorized',401);
    }

   public function recentActivity(){
    $parent = auth('parent')->user();

    if (! $parent) {

         return $this->error('','Unauthorized or invalid token',401);
    }

    $kids = Kid::where('parent_id', $parent->id)->pluck('id');

    $tasks = Task::whereIn('kid_id', $kids)
        ->latest('updated_at')
        ->take(10)
        ->get(['id', 'kid_id', 'title', 'status', 'updated_at']);

    $goals = Saving::whereIn('kid_id', $kids)
        ->latest('updated_at')
        ->take(10)
        ->get(['id', 'kid_id', 'title', 'status', 'progress_percentage', 'updated_at']);

    $payments = WeeklyPayment::whereIn('kid_id', $kids)
        ->latest('updated_at')
        ->take(10)
        ->get(['id', 'kid_id', 'title', 'status', 'updated_at']);

    $activities = new Collection();

    foreach ($tasks as $task) {
        $kid = $task->kid;
        if (!$kid) continue;

        if ($task->status === 'completed') {
            $activities->push([
                'message' => "{$kid->full_name} completed the task \"{$task->title}\".",
                'type' => 'task',
                'time' => $task->updated_at,
            ]);
        }
    }

    foreach ($goals as $goal) {
        $kid = $goal->kid;
        if (!$kid) continue;

         if ($goal->progress_percentage <= 0) continue;

        if ($goal->status === 'completed') {
            $activities->push([
                'message' => "{$kid->full_name} completed the goal \"{$goal->title}\".",
                'type' => 'goal',
                'time' => $goal->updated_at,
            ]);
        } else {
            $activities->push([
                'message' => "{$kid->full_name} reached {$goal->progress_percentage}% of \"{$goal->title}\" goal.",
                'type' => 'goal',
                'time' => $goal->updated_at,
            ]);
        }
    }

    foreach ($payments as $payment) {
        $kid = $payment->kid;
        if (!$kid) continue;

        if ($payment->status === 'paid') {
            $activities->push([
                'message' => "{$kid->full_name} received payment for \"{$payment->title}\".",
                'type' => 'payment',
                'time' => $payment->updated_at,
            ]);
        }
    }

    $sorted = $activities->sortByDesc('time')->values();

    return $this->success($sorted,'All activities of kids',200);
}
}
