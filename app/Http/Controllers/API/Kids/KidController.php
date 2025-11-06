<?php

namespace App\Http\Controllers\API\Kids;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Saving;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\ApiResponse;

class KidController extends Controller
{
    use ApiResponse;
    public function KidProfileEdit(Request $request)
    {
        $kid = auth('kid')->user();
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

        return $this->success($kid,'Profile updated successfully',201);

    }

    public function changePassword(Request $request)
    {
        $kid = auth('kid')->user();
        $request->validate([
            'current_password' => 'required|string|min:1',
            'new_password' => 'required|string|min:1|confirmed',
        ]);

        if (! Hash::check($request->current_password, $kid->password)) {

            return $this->error('','Current password is incorrect',401);
        }

        $kid->password = Hash::make($request->new_password);
        $kid->save();

        return $this->success('', 'Password changed successfully',201);
    }

    public function myFamily(Request $request)
    {
        $parent = auth('kid')->user();

        if (! $parent) {
            return $this->error('','Unauthorized or invalid token',401);
        }

        $families = Family::with([
            'parent:id,full_name,p_unique_id',
            'kids:id,full_name,k_unique_id,family_id,parent_id',
        ])
            ->where('created_by_parent', $parent->id)
            ->get();

        return $this->success($families,'Your Family information',201);
    }


    public function AddMoney(Request $request, $goal_id)
    {
        $kid = auth('kid')->user();
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $goal = Saving::where('id', $goal_id)->where('kid_id', $kid->id)->first();

        if (! $goal) {
            return $this->error('','Saving goal not found',404);
        }

        if ($goal->status == 'completed') {
            return $this->error('','This saving goal is already completed.',400);
        }

        $newAmount = $goal->saved_amount + $request->amount;

        if ($newAmount > $goal->target_amount) {
            $overMoney = number_format($goal->target_amount - $goal->saved_amount, 2);
            return $this->error($overMoney,'Amount exceeds target goal. You can only add up to',400);

        }

        $goal->saved_amount = $newAmount;
        if ($goal->saved_amount == $goal->target_amount) {
            $goal->status = 'completed';
        }
        $goal->save();

        return $this->success($goal,'Amount added successfully',201);
    }

    public function getKidSaving()
    {
        $kid = auth('kid')->user();
        $goals = Saving::where('kid_id', $kid->id)->orderBy('created_at', 'desc')->get();

        return $this->success($goals, 'saving goals retrieved successfully.',201);
    }

    public function KidProfile()
    {
        $kid = auth('kid')->user();

         $profile = [
                'id' => $kid->id,
                'full_name' => $kid->full_name,
                'avatar' => $kid->kavatar ? url($kid->kavatar) : null,
                'today_can_spend' => (float) $kid->today_can_spend,
                'total_balance' => (float) $kid->balance,
         ];
        return $this->success($profile,'Kid profile retrieved successfully.',201);
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
            return $this->error('','Kid not found',404);
        }

        $kid->today_can_spend = $request->today_can_spend;
        $kid->save();

        $data = [
                'id' => $kid->id,
                'username' => $kid->username,
                'today_can_spend' => $kid->today_can_spend,
                'balance' => $kid->balance,
        ];
        return $this->success($data,'Today spend updated successfully',201);
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
            return $this->success('','kid logged out successfully',201);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

             return $this->error('','Failed to logout, please try again',500);
        }
    }
}
