<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodTransactionsController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

//register
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/add-user', [UsersController::class, 'add_user'])->name('add_user');
Route::get('/logout', [UsersController::class, 'logout'])->name('logout');
//forgot_password
Route::post('/forgot_Password', [UsersController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('/ForgotPassword', [UsersController::class, 'show_forgot_password'])->name('show_forgot_password');
//verification
Route::get('/verification', [UsersController::class, 'showverification'])->name('showverification');
Route::post('/verification', [UsersController::class, 'verification'])->name('verification');
//confirm-code-forgot
Route::get('/confirm-code-forgot', [UsersController::class, 'show_confirm_code_forgot'])->name('show_confirm_code_forgot');
Route::post('/confirm-code-forgot', [UsersController::class, 'confirm_code_forgot'])->name('confirm_code_forgot');

Route::get('/profice', [UsersController::class, 'show_profice'])->name('show_profice');

Route::get('/new-password', [UsersController::class, 'show_new_password'])->name('show_new_password');
Route::post('/new-password-forgot', [UsersController::class, 'new_password_forgot'])->name('new_password_forgot');
//login
Route::get('/login', function () {
    return view('login');
})->name('showlogin');


Route::post('/login', [UsersController::class, 'login'])->name('login');
Route::middleware(['check.user.session'])->group(function () {
    //update user
    Route::post('/user/newavatar', [UsersController::class, 'newavatar'])->name('newavatar');
    Route::post('/user/update-profile', [UsersController::class, 'updateprofice'])->name('updateprofice');
    Route::post('/user/update-password', [UsersController::class, 'update_password'])->name('update_password');
    //add food
    Route::get('/donate/add-donated-food', [FoodController::class, 'show_add_donated_food'])->name('show_add_donated_food');
    Route::post('/donate/add-donated-food', [FoodController::class, 'add_donated_food'])->name('add_donated_food');
    Route::get('/donate/manage-list', [FoodController::class, 'show_donate_list'])->name('show_donate_list');
    //update food
    Route::get('/donate/update-donate-food/{food_donate_id}', [FoodController::class, 'show_update_donate_food'])->name('show_update_donate_food');
    Route::post('/donate/update-donated-food', [FoodController::class, 'update_donated_food'])->name('update_donated_food');

    Route::Get('/donate/update-donate-food-cancel/{food_donate_id}', [FoodController::class, 'update_donated_food_cancel'])->name('update_donated_food_cancel');
    Route::Get('/donate/update-donate-food-continues/{food_donate_id}', [FoodController::class, 'update_donated_food_continues'])->name('update_donated_food_continues');
    Route::get('/donate/detail-donated-food/{food_donate_id}', [FoodTransactionsController::class, 'show_detail_donated_food'])->name('show_detail_donated_food');
    //list received
    Route::get('/receiving/list', [FoodTransactionsController::class, 'show_received_list'])->name('received_list');
    Route::get('/receiving/cancel_received/{received_id}', [FoodTransactionsController::class, 'cancel_received'])->name('cancel_received');
    Route::get('/receiving/detail-received/{received_id}', [FoodTransactionsController::class, 'detail_received'])->name('detail_received');
    Route::get('/receiving/rollback_receiving_time/{received_id}', [FoodTransactionsController::class, 'rollback_receiving_time'])->name('rollback_receiving_time');
    Route::get('/receiving/cofirm-collect-food/{received_id}', [FoodTransactionsController::class, 'cofirm_collect_food'])->name('cofirm_collect_food');
    Route::get('/donate/history-transactions', [FoodTransactionsController::class, 'history_transactions'])->name('history_transactions');
    //rating
    Route::post('/receiving/rating', [RateController::class, 'rating'])->name('rating');
    //notification
    Route::get('/notifications/detail/{notification_id}', [UsersController::class, 'show_detail_notification'])->name('show_detail_notification');
    Route::get('/get-notification-count', [UsersController::class, 'getNotificationCount'])->name('getNotificationCount');
    Route::get('/get-infomation', [UsersController::class, 'getInfomation'])->name('getInfomation');
    //confirm_notification
    Route::get('/confirm-notification/{transaction_id}', [FoodTransactionsController::class, 'doner_confirm_notification'])->name('doner_confirm_notification');
    Route::get('/cancel-notification/{transaction_id}', [FoodTransactionsController::class, 'doner_cancel_notification'])->name('doner_cancel_notification');
});
Route::post('/donate/collect-food', [FoodTransactionsController::class, 'collect_food'])->name('collect-food');
// Other routes
Route::get('/search', [FoodController::class, 'index'])->name('search');
Route::post('/search/location', [FoodController::class, 'search_with_location'])->name('search_with_location');
Route::post('/search/food-type', [FoodController::class, 'search_with_food_type'])->name('search_with_food_type');
Route::get('/get-district/{province_id}', [FoodController::class, 'get_district'])->name('get_district');
Route::get('/get-ward/{ward_id}', [FoodController::class, 'get_ward'])->name('get_ward');
Route::get('/search/detail/{food_id}', [FoodController::class, 'show_detail_food'])->name('show_detail_food');
Route::get('/index', [FoodController::class, 'food_share_home'])->name('index');
Route::get('/error404', function () {
    return view('error404');
})->name('error404');

//Admin
Route::middleware(['checkAdmin'])->group(function () {
  //logout
  Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin_logout');
  //show_page
  Route::get('/dashboard', [AdminController::class, 'show_dashboard'])->name('show_dashboard');
  Route::get('/manage/donated', [AdminController::class, 'show_manage_donated'])->name('show_manage_donated');
  Route::get('/manage/transactions', [AdminController::class, 'show_manage_transactions'])->name('show_manage_transactions');
  Route::get('/manage/users', [AdminController::class, 'show_manage_users'])->name('show_manage_users');
  //lock_donated
  Route::get('/manage/lock-donated/{lock_id}', [AdminController::class, 'lock_donated'])->name('lock_donated');
  Route::get('/manage/donated/{food_donated_id}', [AdminController::class, 'manage_donated_detail'])->name('manage_donated_detail');
  //lock_user
  Route::get('/manage/user/lock/{lock_id}', [AdminController::class, 'lock_user'])->name('lock_user');
  //role_user
  Route::get('/manage/user-role/{user_id}', [AdminController::class, 'show_role_user'])->name('show_role_user');
  Route::get('/manage/user-role/{user_id}/{role}', [AdminController::class, 'role_user'])->name('role_user');
  
});