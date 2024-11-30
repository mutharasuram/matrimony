<?php
  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\MatchesController;
   
Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('sendSms', 'sendSms');
    Route::post('verifyOtp', 'verifyOtp');
    Route::post('checkIsExist', 'checkIsExist');
    Route::post('updatePassword', 'updatePassword');
});
         
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
    Route::get('matches', [MatchesController::class, 'index']);
});
