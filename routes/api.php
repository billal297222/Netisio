<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\ParentAuthController;
use  App\Http\Controllers\API\Kids\KidController;
use  App\Http\Controllers\API\Parent\ParentController;
use  App\Http\Controllers\API\Task\TaskController;
use  App\Http\Controllers\API\WeeklyPayment\WeeklyPaymentController;
use  App\Http\Controllers\API\KidMoney\KidTransactionController;
use  App\Http\Controllers\API\ParentMoney\ParentTransactionController;
use  App\Http\Controllers\API\SavingGoals\SavingGoalController;


Route::post('/parents/register', [ParentAuthController::class, 'register']);
Route::post('/parents/verify-otp', [ParentAuthController::class, 'verifyOtp']);
Route::post('/parents/family-create', [ParentAuthController::class, 'createFamily']);
Route::post('/parents/create-kid', [ParentAuthController::class, 'createKid']);
Route::post('/parents/login', [ParentAuthController::class, 'plogin']);
Route::post('/kids/login', [ParentAuthController::class, 'klogin']);


// parent route

Route::middleware('auth:parent')->group(function () {
    Route::post('/parents/profile/edit', [ParentController::class, 'ParentProfileEdit']);
    Route::post('/parents/change-password', [ParentController::class, 'changePassword']);
   // Route::get('/parents/my-family', [ParentController::class, 'myFamily']);
   // Route::post('/kids/savings-goal', [KidController::class, 'createGoal']);
   Route::post('/parent/tasks/today', [TaskController::class, 'createTask']);
   Route::post('/parent/weekly-payments', [WeeklyPaymentController::class, 'createWeeklyPayment']);
   Route::post('/parent/kids/{kidId}/today-spend', [KidController::class, 'updateTodayCanSpend']);
   Route::post('/parents/logout', [ParentAuthController::class, 'plogout']);

});


//kids route
Route::middleware('auth:kid')->group(function () {
    Route::post('/kids/profile/edits', [KidController::class, 'KidProfileEdit']);
    Route::post('/kids/change/password', [KidController::class, 'changePassword']);
   // Route::get('/kids/my-family', [KidController::class, 'myFamily']);
   // Route::post('/kids/savings-goal', [KidController::class, 'createGoal']);
    Route::post('/kids/saving-goals/{goal_id}/add', [KidController::class, 'AddMoney']);
    Route::get('/kid/saving-goal/all', [KidController::class, 'getKidSaving']);
    Route::post('/kid/weekly-payments/{id}/pay', [WeeklyPaymentController::class, 'payWeeklyPayment']);
    Route::get('/kid/weekly-payment/all', [WeeklyPaymentController::class, 'getKidPayment']);
    Route::get('/kid/profile', [KidController::class, 'KidProfile']);
    Route::post('/kids/logout', [KidController::class, 'klogout']);

});

// task related
Route::middleware('auth:kid')->group(function () {
    Route::post('/kid/tasks/{id}/start', [TaskController::class, 'startTask']);
    Route::post('/kid/tasks/{id}/complete', [TaskController::class, 'completeTask']);
    Route::post('/kid/tasks/{id}/reward_collected', [TaskController::class, 'rewardCollected']);
    Route::get('/kid/tasks/all', [TaskController::class, 'getKidTasks']);
});


// kids money
Route::middleware('auth:kid')->group(function () {
    Route::post('/kid/send-money', [KidTransactionController::class, 'sendMoney']);
    Route::get('/kid/sent-users', [KidTransactionController::class, 'sendUsers']);
    Route::get('/kid/wallet', [KidTransactionController::class, 'wallet']);
    Route::get('/kid/{kid_id}/transactions', [KidTransactionController::class, 'getKidTransaction']);


});

// parents money
Route::middleware('auth:parent')->group(function () {
    Route::post('/parent/deposite-money', [ParentTransactionController::class, 'deposite']);
    Route::get('/parent/deposite-limit', [ParentTransactionController::class, 'depositeLimite']);
    Route::get('/parent/wallet', [ParentTransactionController::class, 'wallet']);
    Route::post('/parent/transfer-money', [ParentTransactionController::class, 'transferMoney']);
    Route::get('/parent/{parent_id}/transactions', [ParentTransactionController::class, 'getParentTransactions']);

});

// both
Route::middleware(['auth:parent,kid'])->group(function () {
    Route::post('/kids/saving-goals/create', [SavingGoalController::class, 'createGoal']);
    Route::post('/kids/saving-goals/{goal_id}/addMoney', [SavingGoalController::class, 'AddMoneyToGoal']);
    Route::post('/kids/saving-goals/{goal_id}/collect', [SavingGoalController::class, 'collectGoal']);
    Route::get('/family/my-family', [ParentController::class, 'myFamily']);
     Route::get('/profile/my-profile', [ParentController::class, 'myProfile']);

});


// parent task payment goals dg
 Route::middleware('auth:parent')->group(function () {
    Route::get('/parent/kid-info/{kid_id}', [ParentController::class, 'getKidInfo']);
    Route::get('/parent/kid-task/{kid_id}', [ParentController::class, 'getAssignTask']);
    Route::get('/parent/kid-goals/{kid_id}', [ParentController::class, 'getAssignGoal']);
    Route::get('/parent/kid-payment/{kid_id}', [ParentController::class, 'getAssignPayment']);
    // all
    Route::get('/parent/assign-task/all', [ParentController::class, 'AssignAllTask']);
    Route::get('/parent/assign-goal/all', [ParentController::class, 'AssignAllGoal']);
    Route::get('/parent/assign-payment/all', [ParentController::class, 'AssignAllPayment']);
    Route::get('/parent/all-membar/assign', [ParentController::class, 'allMemberAssign']);

});




