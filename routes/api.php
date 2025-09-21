<?php

use App\Http\Controllers\API\InterestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\MatchesController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\OccupationController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\AstrologyController;

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('sendSms', 'sendSms');
    Route::post('verifyOtp', 'verifyOtp');
    Route::post('checkIsExist', 'checkIsExist');
    Route::post('updatePassword', 'updatePassword');
    Route::post('updateProfile', 'updateProfile');
});
         
// Route::middleware('auth:sanctum')->group( function () {
    Route::get('matches', [MatchesController::class, 'index']);
    Route::Post('image_upload', [ProfileController::class, 'profile_img_store']);
    Route::Post('profile/update', [ProfileController::class, 'update']);
    Route::Post('shortlist', [ProfileController::class, 'shortlist']);
    Route::Post('delete_account', [ProfileController::class, 'delete_account']);
    Route::Post('intrested', [InterestController::class, 'store']);
    Route::get('/user/{id}', [RegisterController::class, 'getUserDetails']);
    Route::Post('user-details', [ProfileController::class, 'getUserDetails']);
// });

Route::get('/countries', [LocationController::class, 'getCountries']);
Route::get('/states/{country_id}', [LocationController::class, 'getStates']);
Route::get('/cities/{state_id}', [LocationController::class, 'getCities']);

Route::get('/occupations', [OccupationController::class, 'index']);

Route::get('/education', [EducationController::class, 'index']);
Route::get('/education/categories', [EducationController::class, 'getCategories']);
Route::get('/education-by-category', [EducationController::class, 'getByCategoryQuery']);

Route::get('/rasi', [AstrologyController::class, 'getRasi']);
Route::get('/star', [AstrologyController::class, 'getStar']);