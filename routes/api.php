<?php

use App\Http\Controllers\API\V1\AuthController;
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
        });
        
    });
     
}
);

