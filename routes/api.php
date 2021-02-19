<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Krit\LineBot;

function crud($name, $controller)
{
    Route::resource("{$name}", "{$controller}");
    Route::post("{$name}/{id}/delete", "{$controller}@destroy");
    Route::post("{$name}/{id}/update", "{$controller}@update");
}

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', 'Auth\UserController@current');
    Route::post('image/upload', 'UploadController@imageUploadPost');

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('email/resend', 'Auth\VerificationController@resend');

    Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});
Route::get('line/user/check/register', 'Line\UserController@checkRegistered');
Route::resource('res', 'RestaurantController');
Route::post('table/add', 'TableController@add');

Route::post('webHook', 'Line\WebhookController@index');

Route::post('addbill', 'BillController@addBill');
Route::post('closebill', 'BillController@closeBill');
Route::get('testjson', 'BillController@test');
Route::get('test', 'Line\WebhookController@test');
Route::get('dashboard', 'DashboardController@index');

crud('foods', 'FoodController');
crud('categories', 'CategoryController');
crud('tables', 'TableController');
crud('orders', 'OrderController');
crud('promotions', 'PromotionController');
crud('restaurant_admins', 'RestaurantAdminController');
crud('sync_accounts', 'SyncAccountController');

crud('sa_promotions', 'PromotionController');
Route::post("sa_promotions/{id}/push", "PromotionController@push");


Route::post("tables/{id}/kick", "TableController@kick");


Route::get('a', function () {
    $bill_id = 133;
    $data = array(
        'to' => 'dqwoiwqd',
        'messages' => [
            [
                'type' => 'text',
                'text' => "ทำการสั่งอาหารสำเร็จ กรุณารออาหารสักครู่ | เลขบิล#{$bill_id}"
            ]
        ]
    );

    return json_encode($data, true);
});