<?php

namespace App\Http\Controllers\API\Parent;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Kid;
use App\Models\Saving;
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

    public function myFamily(Request $request)
    {
        $parent = auth('parent')->user();

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


}
