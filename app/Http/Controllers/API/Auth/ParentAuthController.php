<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Mail\SendOtpMail;
use App\Models\Family;
use App\Models\Kid;


class ParentAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
        'full_name' => 'required|string|max:200',
        'email' => 'required|email|unique:parents,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $otp = rand(100000, 999999);
    $expiresAt = Carbon::now()->addMinutes(10);

    $cacheKey = 'register_otp_' . Str::random(10);
    Cache::put($cacheKey, [
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'otp' => $otp,
    ], $expiresAt);


   Mail::to($request->email)->send(new SendOtpMail($otp));

    return response()->json([
        'status' => 'success',
        'message' => 'OTP sent to your email',
        'cache_key' => $cacheKey
    ]);
 }


 public function plogin(Request $request)
 {
    $request->validate([
        'email'=>'required|email|exists:parents,email',
        'password' => 'required|string|min:1',
    ]);
    $parent = ParentModel::where('emails',$request->email)->first();
     if (!$parent || !Hash::check($request->password, $parent->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password',
        ], 401);
    }

    $token = JWTAuth::fromUser($parent);
    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'parent_id' => $parent->id,
        'token' => $token,
    ]);

 }


 public function verifyOtp(Request $request)
{
    $request->validate([
        'cache_key' => 'required|string',
        'otp' => 'required|digits:6',
    ]);

    $data = Cache::get($request->cache_key);

    if (!$data) {
        return response()->json(['status'=>'error','message'=>'OTP expired or invalid'], 400);
    }

    if ($data['otp'] != $request->otp) {
        return response()->json(['status'=>'error','message'=>'Invalid OTP'], 400);
    }


    $parent = ParentModel::create([
        'full_name' => $data['full_name'],
        'email' => $data['email'],
        'password' => $data['password'],
        'is_verified' => true,
        'balance' => 0.00,
    ]);


    Cache::forget($request->cache_key);

    return response()->json([
        'status' => 'success',
        'message' => 'Parent registered successfully',
        'parent_id' => $parent->id
    ]);

 }


public function createFamily(Request $request)
{
    $request->validate([
        'parent_id' => 'required|exists:parents,id',
        'name' => 'required|string|max:150|unique:families,name',
        'favatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);


    $favatar = null;
    if ($request->hasFile('favatar')) {
        $file = $request->file('favatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('family_avatars/favatar'), $filename);
        $favatar = 'family_avatars/favatar/' . $filename;
    }

    $family = Family::create([
        'name' => $request->name,
        'favatar' => $favatar,
        'created_by_parent' => $request->parent_id,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Family created successfully',
        'family_id' => $family->id,
        'favatar_url' => $favatar ? url($favatar) : null,
    ]);
}

public function createKid(Request $request)
{
    $request->validate([
        'parent_id' => 'required|exists:parents,id',
        'family_id' => 'required|exists:families,id',
        'username' => 'required|string|max:100|unique:kids,username',
        'password' => 'required|string|min:1',
    ]);

    $kid = Kid::create([
        'parent_id' => $request->parent_id,
        'family_id' => $request->family_id,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'balance'=>0.00,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Kid account created successfully',

    ]);
}

public function klogin(Request $request)
{
    $request->validate([
        'username' => 'required|exists:kids,username',
        'password' => 'required|string|min:1',
    ]);

    $kid = Kid::where('username', $request->username)->first();

    if (!$kid || !Hash::check($request->password, $kid->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid username or password',
        ], 401);
    }

    $token = JWTAuth::fromUser($kid);

    return response()->json([
        'status' => 'success',
        'message' => 'Login successful',
        'kid_id' => $kid->id,
        'token' => $token,
    ]);
}



public function sendResetOtp(Request $request)
{
    $request->validate([
        'email'=>'required|email|exists:'
    ])
}






}
