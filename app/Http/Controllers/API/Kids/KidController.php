<?php

namespace App\Http\Controllers\API\Kids;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use App\Models\Family;
use App\Models\Kid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Mail\SendOtpMail;

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

    if($request->has('full_name')){
        $kid->full_name = $request->full_name;
    }

    if($request->filled('pin')){
        $kid->pin=Hash::make($request->pin);
    }

    if($request->hasFile('kavatar')){
        $file=$request->file('kavatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('kids/avatar'), $filename);
        $kid->kavatar = 'kids/avatar/' . $filename;
    }

    $kid->save();
    return response()->json([
        'status' => 'success',
        'message' => 'Profile updated successfully',
        'kid' => $kid
    ]);

   }


   public function changePassword(Request $request)
   {
     $kid = auth('kid')->user();
     $request->validate([
        'current_password'=>'required|string|min:1',
        'new_password' => 'required|string|min:1|confirmed',
     ]);

     if(!Hash::check($request->current_password, $kid->password)){
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
}
