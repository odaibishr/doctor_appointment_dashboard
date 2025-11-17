<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\DayController;
use App\Http\Controllers\API\V1\DoctorController;
use App\Http\Controllers\API\V1\DoctorDaysOffController;
use App\Http\Controllers\API\V1\DoctorScheduleController;
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
            Route::post('createHospital', 'createHospital');
            Route::put('updateHospital/{id}', 'updateHospital');
            Route::delete('deleteHospital/{id}', 'deleteHospital');
            Route::get('getAllHospital', 'getAllHospital');
            Route::get('getSearchHospital','getSearchHospital');
        });
        Route::prefix('doctor')->controller(DoctorController::class)->group(function () {  
            Route::post('createDoctor','createDoctor');
            Route::put('updateDoctor/{id}','updateDoctor');
            Route::put('deleteDoctor/{id}','deleteDoctor');
            Route::get('AllDoctors','AllDoctors');
            Route::get('getSearchDoctors','getSearchDoctors');

        });
        Route::prefix('day')->controller(DayController::class)->group(function () {  
            Route::post('createDay', 'createDay');
            Route::delete('deleteDay/{id}', 'deleteDay');
            Route::get('getDay', 'getDay');

        });
         Route::prefix('dayOff')->controller(DoctorDaysOffController::class)->group(function () {  
            Route::post('createDayOff', 'createDayOff');
            Route::delete('deleteDay/{id}', 'deleteDay');

        });
         Route::prefix('doctorScedule')->controller(DoctorScheduleController::class)->group(function () {  
            Route::post('createDoctorSchedule', 'createDoctorSchedule');
            Route::put('updateDoctorSchedule/{id}', 'updateDoctorSchedule');
            Route::get('getDoctorSchedule/{id}', 'getDoctorSchedule');
            Route::delete('deleteDoctorSchedule/{id}', 'deleteDoctorSchedule');

        });
        
    });
     
}
);
});
