<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\Family;
use App\Models\Kid;
use App\Models\ParentModel;
use App\Models\Backend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\ApiResponse;

class ParentAuthController extends Controller
{
    use ApiResponse;

    // Register parent (send OTP)
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:200',
            'email' => 'required|email|unique:parents,email',
            'password' => 'required|string|min:1|confirmed',
        ]);

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(3);
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
            return $this->error('','Failed to send OTP email: '.$e->getMessage(),500);
        }

        return $this->success($cacheKey,'OTP sent to your email',200);
    }

    // Verify OTP and create parent account
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'cache_key' => 'nullable|string',
            'otp' => 'required|digits:6',
        ]);

        $data = Cache::get($request->cache_key);
        if (! $data) return $this->error('','OTP expired or invalid',400);
        if ($data['otp'] != $request->otp) return $this->error('','Invalid OTP',400);

        do {
            $p_unique_id = '#' . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (ParentModel::where('p_unique_id', $p_unique_id)->exists());

        $parent = ParentModel::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_verified' => true,
            'balance' => 0.00,
            'available_limit' => 0.00,
            'p_unique_id' => $p_unique_id,
        ]);

        $backend = Backend::first();
        $parent->available_limit = $backend ? $backend->monthly_limit : 10000.00;
        $parent->save();

        Cache::forget($request->cache_key);

        $data =[
            'parent_id' => $parent->id,
            'p_unique_id' => $parent->p_unique_id,
            'avatar_url' => $parent->kavatar ? url($parent->kavatar) : null, // added avatar path
        ];
        return $this->success($data,'Parent registered successfully',201);
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
            return $this->error('','Invalid email or password',401);
        }

        $token = JWTAuth::customClaims(['exp' => Carbon::now()->addYear()->timestamp])
                    ->fromUser($parent);

        $family = Family::where('created_by_parent',$parent->id)->get();
        $isFamily = $family->isNotEmpty() ? 'true' : 'false';
        $data = [
            'parent_id' => $parent->id,
            'isFamily' => $isFamily,
            'token' => $token,
            'avatar_url' => $parent->kavatar ? url($parent->kavatar) : null, // added avatar path
        ];

        return $this->success($data,'Login successful',200);
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
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('favatar'), $filename);
            $favatar = 'favatar/' . $filename;
        }

        $family = Family::create([
            'name' => $request->name,
            'favatar' => $favatar,
            'created_by_parent' => $request->parent_id,
        ]);

        $data = [
            'family_id' => $family->id,
            'favatar_url' => $favatar ? asset($favatar) : null,
        ];

        return $this->success($data, 'Family created successfully', 201);
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

        do {
            $k_unique_id = '#' . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        } while (Kid::where('k_unique_id', $k_unique_id)->exists());

        $kid = Kid::create([
            'parent_id' => $request->parent_id,
            'family_id' => $request->family_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'balance' => 0.00,
            'k_unique_id' => $k_unique_id,
        ]);

        $data = [
            'kid_id' => $kid->id,
            'username' => $request->username,
            'k_unique_id' => $kid->k_unique_id,
            'avatar_url' => $kid->kavatar ? url($kid->kavatar) : null, // added avatar path
        ];

        return $this->success($data,'Kid account created successfully',201);
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
            return $this->error('','Invalid username or password',401);
        }

        $token = JWTAuth::customClaims(['exp' => Carbon::now()->addYear()->timestamp])
                    ->fromUser($kid);

        $data = [
            'kid_id' => $kid->id,
            'kidsAvatar' => ! empty($kid->kavatar),
            'kidsName' => ! empty($kid->full_name),
            'kidsPin' => ! empty($kid->pin),
            'token' => $token,
            'avatar_url' => $kid->kavatar ? url($kid->kavatar) : null, // added avatar path
        ];

        return $this->success($data,'Login successful',200);
    }

    // Parent logout
    public function plogout()
    {
        try{
            $token = auth('parent')->getToken();
            auth('parent')->invalidate($token);
            return $this->success('','Parent logged out successfully',200);
        } catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            return $this->error('','Failed to logout, please try again',500);
        }
    }

    public function checkFamily(Request $request){
        $request->validate([
            'parent_id' => 'required|exists:parents,id',
        ]);
        $parentId = $request->parent_id;
        $family = Family::where('created_by_parent',$parentId)->get();

        if($family->isEmpty()){
            return $this->success('','family not created yet',200);
        }
        else {
            return $this->success('','family already created',200);
        }
    }

   public function forgetPasssword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:parents,email',
    ]);

    $parent = ParentModel::where('email', $request->email)->first();

    if (! $parent) {
        return $this->error('', 'You have to register first', 404);
    }

    $otp = rand(100000, 999999);
    $expiresAt = Carbon::now()->addMinutes(3);

    $parent->email_otp = $otp;
    $parent->otp_expires_at = $expiresAt;
    $parent->save();

    Mail::raw("Your password reset OTP is: $otp (valid for 3 minutes)", function ($message) use ($parent) {
        $message->to($parent->email)
                ->subject('Parent Password Reset OTP');
    });

    return $this->success('', 'OTP sent to your email', 200);
}

public function resetOtpVarify(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:parents,email',
        'otp' => 'required|string',
    ]);

    $parent = ParentModel::where('email', $request->email)->first();

    if (!$parent->email_otp || $parent->email_otp != $request->otp) {
        return $this->error('', 'Invalid OTP', 400);
    }

    if (Carbon::now()->gt($parent->otp_expires_at)) {
        return $this->error('', 'OTP expired', 400);
    }

    $parent->update([
    'email_otp' => null,
    'otp_expires_at' => null,
    'is_verified' =>0,
]);


    return $this->success('', 'OTP verified successfully', 200);
}

public function resetPassword(Request $request){
$request->validate([
    'email' => 'required|email|exists:parents,email',
    'password' => 'required|string|min:1|confirmed',
]);

$parent = ParentModel::where('email', $request->email)->first();

if ($parent->is_verified != 0) {
        return $this->error('', 'Please verify OTP first', 403);
    }

$parent->update([
    'password'=> Hash::make($request->password),
    'is_verified' => 1,
]);
return $this->success('', 'Password reset successfully', 200);

}


}

