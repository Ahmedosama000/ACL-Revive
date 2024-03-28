<?php

use App\Http\Controllers\api\mobile\auth\AchievementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\mobile\auth\LoginController;
use App\Http\Controllers\api\mobile\auth\ProtocolController;
use App\Http\Controllers\api\mobile\auth\RegisterController;
use App\Http\Controllers\api\mobile\auth\ResetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix'=>'mobile'],function(){
    Route::get('register',[RegisterController::class,'ShowType']);
    Route::post('register',[RegisterController::class,'Register']);;
    Route::post('login',[LoginController::class,'Login']);
    Route::delete('logout',[LoginController::class,'Logout']);
    Route::delete('logout-all-devices',[LoginController::class,'AllLogout']);
    Route::get('check/{email}',[ResetController::class,'CheckMail']);
    Route::post('send-code',[ResetController::class,'SendCode']);
    Route::post('check-code',[ResetController::class,'CheckCode']);
    Route::post('reset-password',[ResetController::class,'ResetPassword']);
});

Route::group(['prefix'=>'mobile'],function(){
    Route::get('protocols',[ProtocolController::class,'GetProtocols']);
    Route::post('set-protocol',[ProtocolController::class,'SetUserProtocol']);
    Route::get('user-protocol',[ProtocolController::class,'GetUserProtocols']);
    Route::post('update-user-protocol',[ProtocolController::class,'UpdateUserProtocol']);
    Route::post('remove-user-protocol',[ProtocolController::class,'RemoveUserProtocol']);
    Route::post('move-to-achievements',[ProtocolController::class,'MoveToAchieve']);
    Route::get('user-achievements', AchievementController::class);

});  