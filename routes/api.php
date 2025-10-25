<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\ParentAuthController;


Route::post('/parents/register', [ParentAuthController::class, 'register']);
Route::post('/parents/verify-otp', [ParentRegistrationController::class, 'verifyOtp']);
Route::post('/parents/family-create', [ParentRegistrationController::class, 'createFamily']);
Route::post('/parents/create-kid', [ParentRegistrationController::class, 'createKid']);
Route::post('/parents/login', [ParentAuthController::class, 'plogin']);
Route::post('/kids/login', [ParentAuthController::class, 'klogin']);




Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthAPIController::class, 'logout']);

    Route::get('/user/info', [UserAPIController::class, 'userInfo']);
});




