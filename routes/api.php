<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\HospitalController;
use App\Http\Controllers\API\V1\LocationController;
use App\Http\Controllers\API\V1\SpecialtyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');

        

         Route::middleware('auth:sanctum')->group(function () {
             Route::get('me', 'me');
            Route::post('logout', 'logout');
        //specialty
         Route::prefix('specialty')->controller(SpecialtyController::class)->group(function () {
            Route::get('index', 'index');
            Route::post('create', 'create');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{id}', 'delete');
        });

         Route::prefix('location')->controller(LocationController::class)->group(function () {  
            Route::post('createLocation', 'createLocation');
            Route::put('updateLocation/{id}', 'updateLocation');
            Route::delete('deleteLocation/{id}', 'deleteLocation');
        });
          Route::prefix('hospital')->controller(HospitalController::class)->group(function () {  
            Route::post('createHospital', 'createLocation');
            Route::put('updateHospital/{id}', 'updateHospital');
            Route::delete('deleteHospital/{id}', 'deleteHospital');
            Route::get('getAllHospital', 'deleteLocation');
        });
        });
        
    });
     
}
);

