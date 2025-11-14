<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\ParentAuthController;
use  App\Http\Controllers\API\Kids\KidController;
use  App\Http\Controllers\API\Parents\parentsController;
use  App\Http\Controllers\API\Task\TaskController;
use  App\Http\Controllers\API\WeeklyPayment\WeeklyPaymentController;
use  App\Http\Controllers\API\KidMoney\KidTransactionController;
use  App\Http\Controllers\API\KidMoney\AddListController;
use  App\Http\Controllers\API\ParentMoney\ParentTransactionController;
use  App\Http\Controllers\API\SavingGoals\SavingGoalController;


Route::post('/parents/register', [ParentAuthController::class, 'register']);
Route::post('/parents/verify-otp', [ParentAuthController::class, 'verifyOtp']);
Route::post('/parents/family-create', [ParentAuthController::class, 'createFamily']);
Route::post('/parents/family-check', [ParentAuthController::class, 'checkFamily']);
Route::post('/parents/create-kid', [ParentAuthController::class, 'createKid']);
Route::post('/parents/login', [ParentAuthController::class, 'plogin']);
Route::post('/kids/login', [ParentAuthController::class, 'klogin']);
Route::post('/parents/forget-passsword', [ParentAuthController::class, 'forgetPasssword']);
Route::post('/parents/verify-reset-otp', [ParentAuthController::class, 'resetOtpVarify']);
Route::post('/parents/reset-password', [ParentAuthController::class, 'resetPassword']);


// parent route
Route::middleware('auth:parent')->group(function () {
    Route::post('/parents/profile/edit', [parentsController::class, 'ParentProfileEdit']);
    Route::post('/parents/change-password', [parentsController::class, 'changePassword']);
   // Route::get('/parents/my-family', [parentsController::class, 'myFamily']);
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
    Route::post('/kid/payment/request-money/{payment_id}', [WeeklyPaymentController::class, 'requestMoneyPayment']);


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
    Route::post('/kid/request-money', [KidTransactionController::class, 'requestMoney']);
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
    Route::get('/family/my-family', [parentsController::class, 'myFamily']);
    Route::get('/profile/my-profile', [parentsController::class, 'myProfile']);
    Route::get('/family/member', [KidTransactionController::class, 'familyMember']);

});


// parent task payment goals dg
 Route::middleware('auth:parent')->group(function () {
    Route::get('/parent/kid-info/{kid_id}', [parentsController::class, 'getKidInfo']);
    Route::get('/parent/kid-task/{kid_id}', [parentsController::class, 'getAssignTask']);
    Route::get('/parent/kid-goals/{kid_id}', [parentsController::class, 'getAssignGoal']);
    Route::get('/parent/kid-payment/{kid_id}', [parentsController::class, 'getAssignPayment']);
    // all
    Route::get('/parent/assign-task/all', [parentsController::class, 'AssignAllTask']);
    Route::get('/parent/assign-goal/all', [parentsController::class, 'AssignAllGoal']);
    Route::get('/parent/assign-payment/all', [parentsController::class, 'AssignAllPayment']);
    Route::get('/parent/all-membar/assign', [parentsController::class, 'allMemberAssign']);
    Route::get('/parent/kids/recent-activity', [parentsController::class, 'recentActivity']);

});

Route::middleware('auth:kid')->group(function () {
    Route::get('/addlist/family-members', [AddListController::class, 'showFamilyMembers']);
    Route::post('/addlist/add-member', [AddListController::class, 'addMember']);
    Route::delete('/addlist/remove-member/{id}', [AddListController::class, 'removeMember']);
    Route::get('/addlist/list', [AddListController::class, 'addedList']);

});




