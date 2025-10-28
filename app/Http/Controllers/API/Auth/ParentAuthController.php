<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\Family;
use App\Models\Kid;
use App\Models\ParentModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class ParentAuthController extends Controller
{
    // Register parent (send OTP)
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:200',
            'email' => 'required|email|unique:parents,email',
            'password' => 'required|string|min:1|confirmed',
        ]);

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $cacheKey = 'register_otp_'.Str::random(10);

        Cache::put($cacheKey, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ], $expiresAt);

        try {
            Mail::to($request->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP email: '.$e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent to your email',
            'cache_key' => $cacheKey,
        ]);
    }

    // Verify OTP and create parent account
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'cache_key' => 'required|string',
            'otp' => 'required|digits:6',
        ]);

        $data = Cache::get($request->cache_key);

        if (! $data) {
            return response()->json([
                'status' => 'error',
                'message' => 'OTP expired or invalid',
            ], 400);
        }

        if ($data['otp'] != $request->otp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP',
            ], 400);
        }

        do {
            $p_unique_id = strtoupper(substr(uniqid(), -8));
        } while (ParentModel::where('p_unique_id', $p_unique_id)->exists());

        $parent = ParentModel::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_verified' => true,
            'balance' => 0.00,
            'p_unique_id' => $p_unique_id,
        ]);

        Cache::forget($request->cache_key);

        return response()->json([
            'status' => 'success',
            'message' => 'Parent registered successfully',
            'parent_id' => $parent->id,
            'p_unique_id' => $parent->p_unique_id,
        ]);
    }

    // Parent login
    public function plogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:parents,email',
            'password' => 'required|string|min:1',
        ]);

        $parent = ParentModel::where('email', $request->email)->first();

        if (! $parent || ! Hash::check($request->password, $parent->password)) {
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

    // Create Family
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
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('family_avatars/favatar', $filename, 'public');
            $favatar = Storage::url($path);
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
            'favatar_url' => $favatar,
        ]);
    }

    // Create Kid
    public function createKid(Request $request)
    {

        $request->validate([
            'parent_id' => 'required|exists:parents,id',
            'family_id' => 'required|exists:families,id',
            'username' => 'required|string|max:100|unique:kids,username',
            'password' => 'required|string|min:1',
        ]);

        // Generate unique k_unique_id (8 characters, alphanumeric)
        do {
            $k_unique_id = strtoupper(substr(uniqid(), -8));
        } while (Kid::where('k_unique_id', $k_unique_id)->exists());

        $kid = Kid::create([
            'parent_id' => $request->parent_id,
            'family_id' => $request->family_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'balance' => 0.00,
            'k_unique_id' => $k_unique_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kid account created successfully',
            'kid_id' => $kid->id,
            'k_unique_id' => $kid->k_unique_id,
        ]);
    }

    // Kid login
    public function klogin(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:kids,username',
            'password' => 'required|string|min:1',
        ]);

        $kid = Kid::where('username', $request->username)->first();

        if (! $kid || ! Hash::check($request->password, $kid->password)) {
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
}
