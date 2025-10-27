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
         
        if (!$request->kid_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'kid_id is required when parent creates a goal.',
            ], 422);
        }

        $kid = Kid::where('id', $request->kid_id)
            ->where('parent_id', $parent->id)
            ->first();

        if (!$kid) {
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

    return response()->json([
        'status' => 'success',
        'message' => 'Saving goal created successfully.',
        'goal' => $goal,
    ]);
}



    public function AddMoney(Request $request,$goal_id)
    {
        $kid = auth('kid')->user();
        $request->validate([
            'amount'=>'required|numeric|min:0.01',
        ]);

        $goal = Saving::where('id',$goal_id)->where('kid_id',$kid->id)->first();

        if(!$goal){
           return response()->json([
            'status' => 'error',
            'message' => 'Saving goal not found',
        ], 404);
        }

        if($goal->status=='completed'){
             return response()->json([
            'status' => 'error',
            'message' => 'This saving goal is already completed.',
        ], 400);
        }

        $newAmount = $goal->saved_amount + $request->amount;

        if($newAmount>$goal->target_amount){
            return response()->json([
            'status' => 'error',
            'message' => 'Amount exceeds target goal. You can only add up to '
                         . number_format($goal->target_amount - $goal->saved_amount, 2) . ' more.',
        ], 400);

        }

        $goal->saved_amount=$newAmount;
        if($goal->saved_amount==$goal->target_amount){
            $goal->status='completed';
        }
        $goal->save();
        return response()->json([
        'status' => 'success',
        'message' => 'Amount added successfully',
        'goal' => $goal,
    ]);
    }






}
