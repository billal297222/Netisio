<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\ParentAuthController;
use  App\Http\Controllers\API\Kids\KidController;
use  App\Http\Controllers\API\Parent\ParentController;


Route::post('/parents/register', [ParentAuthController::class, 'register']);
Route::post('/parents/verify-otp', [ParentAuthController::class, 'verifyOtp']);
Route::post('/parents/family-create', [ParentAuthController::class, 'createFamily']);
Route::post('/parents/create-kid', [ParentAuthController::class, 'createKid']);
Route::post('/parents/login', [ParentAuthController::class, 'plogin']);
Route::post('/kids/login', [ParentAuthController::class, 'klogin']);


Route::middleware('auth:parent')->group(function () {

    Route::post('/parents/profile/edit', [ParentController::class, 'ParentProfileEdit']);
    Route::post('/parents/change-password', [ParentController::class, 'changePassword']);
    Route::get('/parents/my-family', [ParentController::class, 'myFamily']);
   // Route::post('/kids/savings-goal', [KidController::class, 'createGoal']);
});


Route::middleware('auth:kid')->group(function () {
    Route::post('/kids/profile/edits', [KidController::class, 'KidProfileEdit']);
    Route::post('/kids/change/password', [KidController::class, 'changePassword']);
    Route::get('/kids/my-family', [KidController::class, 'myFamily']);
   // Route::post('/kids/savings-goal', [KidController::class, 'createGoal']);
    Route::post('/kids/saving-goals/{goal_id}/add', [KidController::class, 'AddMoney']);
});


Route::middleware(['auth:parent,kid'])->group(function () {
    Route::post('/kids/saving-goals/create', [KidController::class, 'createGoal']);
});

Route::get('/test', function() {
    return response()->json(['status'=>'ok']);

});




