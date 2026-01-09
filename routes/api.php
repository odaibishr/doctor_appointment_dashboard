<?php

use App\Http\Controllers\AiDoctorController;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\BookAppointmentController;
use App\Http\Controllers\API\V1\DayController;
use App\Http\Controllers\API\V1\DoctorController;
use App\Http\Controllers\API\V1\DoctorDaysOffController;
use App\Http\Controllers\API\V1\DoctorScheduleController;
use App\Http\Controllers\API\V1\FavoriteDoctorController;
use App\Http\Controllers\API\V1\HospitalController;
use App\Http\Controllers\API\V1\LocationController;
use App\Http\Controllers\API\V1\NotificationController;
use App\Http\Controllers\API\V1\PatientController;
use App\Http\Controllers\API\V1\PaymentGatewayDetailController;
use App\Http\Controllers\API\V1\ReviewsController;
use App\Http\Controllers\API\V1\SpecialtyController;
use App\Http\Controllers\API\V1\TransicationController;
use App\Http\Controllers\API\V1\WaitlistController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(
        function () {
            Route::post('register', 'register');
            Route::post('login', 'login');

            // specialty
            Route::prefix('specialty')->controller(SpecialtyController::class)->group(function () {
                Route::get('index', 'index');
                Route::post('create', 'create');
                Route::put('update/{id}', 'update');
                Route::delete('delete/{id}', 'delete');
            });
            Route::prefix('ai')->controller(AiDoctorController::class)->group(function () {
                Route::post('ask', 'ask');
            });
            // Route::post('/ai/doctor/ask', [AiDoctorController::class, 'ask']);
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('me', 'me');
                Route::post('logout', 'logout');
                Route::post('/patients', [PatientController::class, 'store']);
                Route::get('/patients', [PatientController::class, 'getPatientData']);

                Route::prefix('doctor')->controller(DoctorController::class)->group(function () {
                    Route::post('createDoctor', 'createDoctor');
                    Route::put('updateDoctor/{id}', 'updateDoctor');
                    Route::put('deleteDoctor/{id}', 'deleteDoctor');
                    Route::get('AllDoctors', 'AllDoctors');
                    Route::get('getSearchDoctors', 'getSearchDoctors');
                    Route::get('getDoctor/{id}', 'getDoctorById');
                    Route::get('getDoctorsBySpecialtyName', 'getDoctorsBySpecialtyName');
                });


                Route::prefix('location')->controller(LocationController::class)->group(function () {
                    Route::post('createLocation', 'createLocation');
                    Route::put('updateLocation/{id}', 'updateLocation');
                    Route::delete('deleteLocation/{id}', 'deleteLocation');
                });
                Route::prefix('hospital')->controller(HospitalController::class)->group(function () {
                    Route::post('createHospital', 'createHospital');
                    Route::get('getHospital/{id}', 'getHospitalDetails');
                    Route::put('updateHospital/{id}', 'updateHospital');
                    Route::delete('deleteHospital/{id}', 'deleteHospital');
                    Route::get('getAllHospitals', 'getAllHospitals');
                    Route::get('getSearchHospital', 'getSearchHospital');
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
                    Route::delete('deleteGatway/{id}', 'deleteGatway');
                });
                Route::prefix('transcation')->controller(TransicationController::class)->group(function () {
                    Route::post('createTranscation', 'createTranscation');
                    Route::delete('deleteTranscation/{id}', 'deleteTranscation');
                });
                Route::prefix('review')->controller(ReviewsController::class)->group(function () {
                    Route::post('createReview', 'createReview');
                    Route::delete('deleteReview/{id}', 'deleteReview');
                    Route::get('getReviewByDoctor/{doctorId}', 'getReviewByDoctor');
                });
                Route::prefix('favorite')->controller(FavoriteDoctorController::class)->group(function () {
                    Route::post('toggle', 'ToggleFavoriteDoctor');
                    Route::get('getUserFavoriteDoctor', 'getUserFavoriteDoctor');
                });
                Route::prefix('notification')->controller(NotificationController::class)->group(function () {
                    Route::post('sendNotification', 'sendNotification');
                });
                Route::prefix('appointment')->controller(BookAppointmentController::class)->group(function () {
                    Route::post('createAppointment', 'createAppointment');
                    Route::delete('deleteAppointment/{id}', 'deleteAppointment');
                    Route::get('getUserAppointment', 'getUserAppointment');
                    Route::put('updateAppointmentStatus/{doctor_id}', 'updateAppointmentStatus');
                });

                // Waitlist Routes
                Route::prefix('waitlist')->controller(WaitlistController::class)->group(function () {
                    Route::post('join', 'join');
                    Route::delete('leave/{id}', 'leave');
                    Route::get('my-waitlists', 'myWaitlists');
                    Route::get('position/{doctorId}', 'getPosition');
                    Route::post('accept/{id}', 'acceptSlot');
                    Route::post('decline/{id}', 'declineSlot');
                    Route::get('check-availability/{doctorId}', 'checkDoctorAvailability');
                });
            });
        }
    );
});
