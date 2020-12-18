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

crud('foods', 'FoodController');
crud('categories', 'CategoryController');
crud('tables', 'TableController');

Route::post("tables/{id}/kick", "TableController@kick");


Route::get('a', function () {
    $bot = new LineBot('1RJVFAn7A09mJIUAj3sfgxTvzic1p51CXhP9Mwx8j1xRdjSWUwXTMmkq7TNgLIrcdMHPbjFcFCpDxeU3JQ40o8Vp9EEisJmZEOiK4m0sMBNczICWYZLOHGBG5F+xfYX+uFVrn1CPqjXfxXg8HzLdSgdB04t89/1O/w1cDnyilFU=');
    return $bot->setUser('U98a51562ca53bb6d5f844da8399e2a01')->addText('aad')->getUser();
});