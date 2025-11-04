<?php

namespace App\Http\Controllers\API\Kids;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KidController extends Controller
{
    public function KidProfileEdit(Request $request)
    {
        $kid = auth('kid')->user(); // JWT-authenticated kid
        $request->validate([
            'full_name' => 'nullable|string|max:100',
            'kavatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pin' => 'nullable|string|min:1|confirmed',
        ]);

        if ($request->has('full_name')) {
            $kid->full_name = $request->full_name;
        }

        if ($request->filled('pin')) {
            $kid->pin = Hash::make($request->pin);
        }

        if ($request->hasFile('kavatar')) {
            $file = $request->file('kavatar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('kids/avatar'), $filename);
            $kid->kavatar = 'kids/avatar/'.$filename;
        }

        $kid->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'kid' => $kid,
        ]);

    }

    public function changePassword(Request $request)
    {
        $kid = auth('kid')->user();
        $request->validate([
            'current_password' => 'required|string|min:1',
            'new_password' => 'required|string|min:1|confirmed',
        ]);

        if (! Hash::check($request->current_password, $kid->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Current password is incorrect',
            ], 401);
        }

        $kid->password = Hash::make($request->new_password);
        $kid->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password changed successfully',
        ]);
    }

    public function myFamily(Request $request)
    {
        $parent = auth('kid')->user();

        if (! $parent) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized or invalid token',
            ], 401);
        }

        $families = Family::with([
            'parent:id,full_name,p_unique_id',
            'kids:id,full_name,k_unique_id,family_id,parent_id',
        ])
            ->where('created_by_parent', $parent->id)
            ->get();

        return response()->json([
            'status' => 'success',
            'families' => $families,
        ]);
    }

    
    public function AddMoney(Request $request, $goal_id)
    {
        $kid = auth('kid')->user();
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $goal = Saving::where('id', $goal_id)->where('kid_id', $kid->id)->first();

        if (! $goal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saving goal not found',
            ], 404);
        }

        if ($goal->status == 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This saving goal is already completed.',
            ], 400);
        }

        $newAmount = $goal->saved_amount + $request->amount;

        if ($newAmount > $goal->target_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Amount exceeds target goal. You can only add up to '
                             .number_format($goal->target_amount - $goal->saved_amount, 2).' more.',
            ], 400);

        }

        $goal->saved_amount = $newAmount;
        if ($goal->saved_amount == $goal->target_amount) {
            $goal->status = 'completed';
        }
        $goal->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Amount added successfully',
            'goal' => $goal,
        ]);
    }

    public function getKidSaving()
    {
        $kid = auth('kid')->user();
        $goals = Saving::where('kid_id', $kid->id)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'saving goals retrieved successfully.',
            'tasks' => $goals,
        ]);
    }

    public function KidProfile()
    {
        $kid = auth('kid')->user();

        return response()->json([
            'message' => 'Kid profile retrieved successfully.',
            'profile' => [
                'id' => $kid->id,
                'full_name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'today_can_spend' => (float) $kid->today_can_spend,
                'total_balance' => (float) $kid->balance,
            ],
        ], 200);
    }

    public function updateTodayCanSpend(Request $request, $kidId)
    {

        $parent = auth('parent')->user();
        $request->validate([
            'today_can_spend' => 'required|numeric|min:0',
        ]);

        $kid = Kid::where('id', $kidId)
            ->where('parent_id', $parent->id)
            ->first();

        if (! $kid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kid not found',
            ], 404);
        }

        $kid->today_can_spend = $request->today_can_spend;
        $kid->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Today spend updated successfully',
            'kid' => [
                'id' => $kid->id,
                'username' => $kid->username,
                'today_can_spend' => $kid->today_can_spend,
                'balance' => $kid->balance,
            ],
        ]);
    }

    public function klogout()
    {
        try {

            $token = auth('kid')->getToken();
            auth('kid')->invalidate($token);

            return response()->json([
                'status' => 'success',
                'message' => 'kid logged out successfully',
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout, please try again',
            ], 500);
        }
    }
}
