<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\V1\{
    AuthController, BookAppointmentController, DayController, DoctorController,
    DoctorDaysOffController, DoctorScheduleController, FavoriteDoctorController,
    HospitalController, LocationController, NotificationController, SpecialtyController,
    PaymentGatewayDetailController, ReviewsController, TransicationController,
    PatientController, PaymentMethodController
};

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');

        

         Route::middleware('auth:sanctum')->group(function () {
             Route::get('me', 'me');
            Route::post('logout', 'logout');
            Route::post('/patients', [PatientController::class, 'store']);
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
        Route::prefix('paymenGatwayDetail')->controller(PaymentGatewayDetailController::class)->group(function () {  
            Route::post('createGatway', 'createGatway');
            Route::delete('deleteGatway/{id}','deleteGatway');
        });
        
          Route::prefix('review')->controller(ReviewsController::class)->group(function () {  
            Route::post('createReview', 'createReview');
            Route::delete('deleteReview/{id}','deleteReview');
            Route::get('getReviewByDoctor/{doctorId}','getReviewByDoctor');
        });
        Route::prefix('favorite')->controller(FavoriteDoctorController::class)->group(function () {  
            Route::post('addFavoriteDoctor', 'addFavoriteDoctor');
            Route::delete('deleteFavoriteDoctor/{id}','deleteFavoriteDoctor');
            Route::get('getUserFavoriteDoctor','getUserFavoriteDoctor');
        });
        Route::prefix('notification')->controller(NotificationController::class)->group(function () {  
            Route::post('sendNotification', 'sendNotification');

        });
            Route::prefix('appointment')->controller(BookAppointmentController::class)->group(function () {  
            Route::post('createAppointment', 'createAppointment');
            Route::delete('deleteAppointment/{id}','deleteAppointment');
            Route::get('getUserAppointment','getUserAppointment');
            Route::put('updateAppointmentStatus/{doctor_id}','updateAppointmentStatus');
        });

          Route::prefix('paymentMethod')->controller(PaymentMethodController::class)->group(function () {  
           Route::get('paymentmethods', 'index');
        Route::post('paymentmethod/create', 'store');
          });
          Route::prefix('transcation')->controller(TransicationController::class)->group(function () {  
            Route::post('createTranscation', 'createTranscation');
            Route::delete('deleteTranscation/{id}','deleteTranscation');
        });
     

        


    });
     
}
);
});
