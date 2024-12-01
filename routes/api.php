<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\MatchesController;
use App\Http\Controllers\API\ProfileController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('sendSms', 'sendSms');
    Route::post('verifyOtp', 'verifyOtp');
    Route::post('checkIsExist', 'checkIsExist');
    Route::post('updatePassword', 'updatePassword');
});
         
// Route::middleware('auth:sanctum')->group( function () {
    Route::get('matches', [MatchesController::class, 'index']);
    Route::Post('image_upload', [ProfileController::class, 'profile_img_store']);
    Route::Post('shortlist', [ProfileController::class, 'shortlist']);

// });
