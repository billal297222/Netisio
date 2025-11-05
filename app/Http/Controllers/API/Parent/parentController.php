<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Saving;
use App\Models\Task;
use App\Models\WeeklyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class parentController extends Controller
{
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
                return response()->json([
                    'status' => 'error',
                    'message' => 'No family found for this parent',
                ], 404);
            }

            $file = $request->file('favatar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('family_avatars/favatar'), $filename);
            $family->favatar = 'family_avatars/favatar/'.$filename;
            $family->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'parent' => $parent,
        ]);
    }

    public function changePassword(Request $request)
    {
        $parent = auth('parent')->user();

        if (! $parent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid token',
            ], 401);
        }

        $request->validate([
            'current_password' => 'required|string|min:1',
            'new_password' => 'required|string|min:1|confirmed',
        ]);

        if (! Hash::check($request->current_password, $parent->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect',
            ], 401);
        }

        $parent->password = Hash::make($request->new_password);
        $parent->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);
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
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid token',
            ], 401);
        }

        $familyId = $parent ? $parent->id : $kid->family_id;

        $family = Family::with(['kids:id,full_name,k_unique_id,family_id,kavatar', 'parent:id,full_name,p_unique_id,pavatar'])
            ->where('id', $parent ? $parent->id : $kid->family_id)
            ->first();

        if (! $family) {
            return response()->json([
                'status' => 'error',
                'message' => 'Family not found',
            ], 404);
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

        foreach ($family->kids as $kid) {
            $members->push([
                'id' => $kid->id,
                'name' => $kid->full_name,
                'unique_id' => $kid->k_unique_id,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'role' => 'kid',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'family_name' => $family->name,
            'total_members' => $members->count(),
            'members' => $members,
        ]);
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
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid kid ID or kid does not belong to this parent.',
            ], 403);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Saving goal created successfully for kid: '.$kid->full_name,
            'goal' => $goal,
        ]);
    }

    // kid info -----------------------------------------------------

    public function getKidInfo($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = $parent->kids()->select('id', 'full_name', 'k_unique_id', 'kavatar', 'balance', 'today_can_spend', 'family_id')
            ->with('family:id,name,favatar')->where('id', $kid_id)->first();
        if (! $kid) {
            return response()->json([
                'status' => 'error',
                'message' => 'kids not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'kid' => [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'unique_id' => $kid->unique_id,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'balance' => number_format($kid->balance, 2),
                'today_can_spend' => number_format($kid->today_can_spend, 2),
            ],
        ]);

    }

    public function getAssignTask($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)->where('parent_id', $parent->id)->first();

        if (! $kid) {
            return response()->json([
                'status' => 'error',
                'message' => 'kids not found',
            ], 404);
        }
        $tasks = Task::where('kid_id', $kid->id)->latest()->get(['id', 'title', 'description', 'reward_amount', 'status']);

        return response()->json([
            'status' => 'success',
            'kid' => [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
            ],
            'tasks' => $tasks,
        ]);

    }

    public function getAssignGoal($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)
            ->where('parent_id', $parent->id)
            ->first();

        if (! $kid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kid not found or does not belong to you.',
            ], 404);
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

        return response()->json([
            'status' => 'success',
            'kid' => [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
            ],
            'savings' => $savings,
        ]);
    }

    public function getAssignPayment($kid_id)
    {
        $parent = auth('parent')->user();

        $kid = Kid::where('id', $kid_id)->where('parent_id', $parent->id)->first();

        if (! $kid) {
            return response()->json([
                'status' => 'error',
                'message' => 'kids not found',
            ], 404);
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

        return response()->json([
            'status' => 'success',
            'kid' => [
                'id' => $kid->id,
                'name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
            ],
            'payments' => $payments,
        ]);
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

        return response()->json([
            'status' => 'success',
            'tasks' => $tasks,
        ]);
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

        return response()->json([
            'status' => 'success',
            'savings' => $saving,
        ]);
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

        return response()->json([
            'status' => 'success',
            'payments' => $payments,
        ]);

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

        return response()->json([
            'status' => 'success',
            'kids' => $membar,
        ]);
    }



    public function myProfile()
    {
        $parent = auth('parent')->user();
        $kid = auth('kid')->user();

        if ($parent) {
            $familyMembersCount = 1+$parent->kids()->count();

            return response()->json([
                'status' => 'success',
                'logged_in_user' => [
                    'id' => $parent->id,
                    'name' => $parent->full_name,
                    'unique_id' => $parent->p_unique_id,
                    'avatar' => $parent->pavatar ? url($parent->pavatar) : null,
                ],
                'total_family_members' => $familyMembersCount,
            ]);
        }

        if ($kid) {
            $family = Family::withCount('kids')->find($kid->family_id);

            $totalMembers = 1 + ($family?->kids_count ?? 0);

            return response()->json([
                'status' => 'success',
                'logged_in_user' => [
                    'id' => $kid->id,
                    'name' => $kid->full_name,
                    'unique_id' => $kid->k_unique_id,
                    'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                ],
                'total_family_members' => $totalMembers,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 401);
    }
   
}




