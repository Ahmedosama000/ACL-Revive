<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\mobile\SessionController;
use App\Http\Controllers\api\mobile\auth\LoginController;
use App\Http\Controllers\api\mobile\auth\ResetController;
use App\Http\Controllers\api\mobile\auth\ProtocolController;
use App\Http\Controllers\api\mobile\auth\RegisterController;
use App\Http\Controllers\api\mobile\auth\IdentifiesController;
use App\Http\Controllers\api\mobile\auth\AchievementController;
use App\Http\Controllers\api\mobile\ExerciseController;

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

// auth

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

// patients

Route::group(['prefix'=>'mobile'],function(){
    Route::get('protocols',[ProtocolController::class,'GetProtocols']);
    Route::post('set-protocol',[ProtocolController::class,'SetUserProtocol']);
    Route::get('user-protocol',[ProtocolController::class,'GetUserProtocols']);
    Route::get('user-protocol-info/{id}',[ProtocolController::class,'ShowUserProtocolInfo']);
    Route::post('update-user-protocol',[ProtocolController::class,'UpdateUserProtocol']);
    Route::post('remove-user-protocol',[ProtocolController::class,'RemoveUserProtocol']);
    Route::post('move-to-achievements',[ProtocolController::class,'MoveToAchieve']);
    Route::get('user-achievements', AchievementController::class);
    Route::post('reserve-session/{id}',[SessionController::class,'Reserve']);
    Route::get('reserved-sessions',[SessionController::class,'ShowMyReservedSessions']);
    Route::get('session/{id}',[SessionController::class,'GetSessionInfo']);
    Route::get('all-sessions',[SessionController::class,'GetAllSessions']);
    Route::post('set-photo',[RegisterController::class,'SetUserPhoto']);
    Route::get('exercises',[ExerciseController::class,'GetAllExercise']);
    Route::get('exercise/{type}',[ExerciseController::class,'GetExercise']);
    Route::get('get-exercise/{id}',[ExerciseController::class,'GetExerciseByID']);
    Route::get('show-exercise/{protocol}/{type}/{phase}',[ExerciseController::class,'GetExerciseByPhase']);
    Route::post('create-exercise',[ExerciseController::class,'CreateExercise']);


});

// doctors 

Route::group(['prefix'=>'mobile'],function(){
    Route::post('identifies',[IdentifiesController::class,'UploadIDS']);
    Route::post('create-session',[SessionController::class,'Create']);
    Route::get('my-sessions',[SessionController::class,'ShowRelatedSessions']);
    Route::get('my-reserved-sessions',[SessionController::class,'ShowRelatedReservedSessions']);
    Route::post('update-session/{id}',[SessionController::class,'UpdateSession']);
    Route::post('delete-session/{id}',[SessionController::class,'RemoveSession']);
});