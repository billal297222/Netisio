<?php

use App\Http\Controllers\Backend\BackendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\FqaController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PdfController;
use App\Http\Controllers\Backend\ParentController;
use App\Http\Controllers\Backend\MonthlyController;
use App\Http\Controllers\Backend\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/', [BackendController::class, 'index']);
// Route::get('/',[RegisteredUserController::class,'create']);
Route::get('/', [AuthenticatedSessionController::class, 'create']);
// Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::get('/fqa', [FqaController::class, 'index'])->name('fqa.index');
Route::get('/dynamic_pages', [DynamicPagesController::class, 'index'])->name('dynamic.index');

// Category Routes
Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('category.index');
    Route::get('/get', 'get')->name('category.get');
    Route::post('/category/store','store')->name('category.store');

    Route::get('/destroy/{id}', 'destroy')->name('category.destroy');
    Route::get('/edit/{id}', 'edit')->name('category.edit');
    Route::post('/category/update/{id}', 'update')->name('category.update');
    //
    Route::post('/status/{id}', 'status')->name('category.status');

});


// FAQ Route
Route::controller(FqaController::class)->group(function () {
    Route::get('/faq', 'index')->name('faq.index');
    Route::post('/faq/store', 'store')->name('faq.store');
    Route::post('/faq/update/{id}', 'update')->name('faq.update');
    Route::get('/faq/destroy/{id}', 'destroy')->name('faq.destroy');
    Route::get('/faq/edit/{id}', 'edit')->name('faq.edit');
});


Route::controller(ParentController::class)->group(function () {
    Route::get('/family', 'index')->name('family.index');
    Route::post('/family/delete/{id}', 'delete')->name('family.delete');
    Route::get('/family/show/{id}', 'show')->name('family.show');
    Route::patch('/toggle-status/{type}/{id}', 'toggleStatus')->name('toggle.status');
    // Delete a kid
    Route::delete('/kids/{kid}', 'destroy')->name('kids.destroy');
    Route::get('/admin/monthly-limit',  'edit')->name('backend.edit');
    Route::post('/admin/monthly-limit','update')->name('backend.update');

});
Route::controller(MonthlyController::class)->group(function () {




});





// Dynamic Pages Route
// Route::controller(DynamicPagesController::class)->group(function () {
//     Route::get('/dynamicpages', 'index')->name('dynamicpages.index');
//     Route::get('/dynamicpages/create', 'create')->name('dynamicpages.create');

//     Route::post('/dynamicpages/store', 'store')->name('dynamicpages.store');
//     Route::get('/dynamicpages/destroy/{id}', 'destroy')->name('dynamicpages.destroy');
//     Route::post('/bulk-delete', 'bulkDelete')->name('dynamicpages.bulk-delete');
//     Route::get('/dynamicpages/edit/{id}', 'edit')->name('dynamicpages.edit');
//     Route::post('/dynamicpages/update/{id}', 'update')->name('dynamicpages.update');
//     Route::post('/dynamicpages/status/{id}', 'status')->name('dynamicpages.status');
// });


// role permission routes
Route::prefix('role')->controller(RoleController::class)->group(function () {
    Route::get('/list', 'index')->name('admin.role.list');
    Route::get('/create', 'create')->name('admin.role.create');
    Route::post('/store', 'store')->name('admin.role.store');
    Route::get('/show/{id}', 'show')->name('admin.role.show');
    Route::post('/update/{id}', 'update')->name('admin.role.update');
});

// Settings Route
Route::prefix('system')->controller(SettingController::class)->group(function () {
    Route::get('/setting', 'index')->name('system.setting');
    Route::post('/update', 'update')->name('system.update');
    Route::get('/setting/admin', 'admin_index')->name('admin.setting');
    Route::post('/update/admin', 'admin_update')->name('admin.update');
    Route::get('/setting/mail', 'mail')->name('mail.setting');
    Route::post('/setting/mail', 'mail_store')->name('mail.update');

    Route::get('active/directory', 'adIndex')->name('directory.setting');
    Route::post('active/directory', 'adUpdate')->name('directory.update');
});

// User Route
Route::controller(UserController::class)->group(function () {
    Route::get('/users/list', 'index')->name('user.list');
    Route::get('/users/edit/{id}', 'edit')->name('user.edit');
    Route::post('/update/users/{id}', 'update')->name('user.update');
    Route::get('/delete/{id}', 'destroy')->name('user.destroy');
    Route::get('/user/create', 'create')->name('user.create');
    Route::post('/user/store', 'store')->name('user.store');
    Route::post('/user/status/{id}', 'status')->name('user.status');
});



