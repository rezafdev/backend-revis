<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDoctorController;
use App\Http\Controllers\Admin\AdminPatientController;
use App\Http\Controllers\Admin\AdminStatsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\Admin\AdminTherapyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'file'], function (Router $router) {
    $router->post('/upload', [AdminFileController::class, 'uploadFile']);
});

Route::group(['prefix' => 'auth'], function (Router $router) {
    $router->post('/login', [AdminAuthController::class, 'login']);
    $router->get('/me', [AdminAuthController::class, 'me']);
});

Route::group(['prefix' => 'doctor'], function (Router $router) {
    $router->get('/', [AdminDoctorController::class, 'getDoctors']);
    $router->post('/', [AdminDoctorController::class, 'addDoctor']);
});

Route::group(['prefix' => 'therapy'], function (Router $router) {
    $router->get('/', [AdminTherapyController::class, 'getTherapies']);
    $router->get('/{id}', [AdminTherapyController::class, 'getTherapyById'])->whereNumber('id');
    $router->post('/', [AdminTherapyController::class, 'addTherapy']);
    $router->get('/popular', [AdminTherapyController::class, 'getPopulars']);
});

Route::group(['prefix' => 'patient'], function (Router $router) {
    $router->get('/', [AdminPatientController::class, 'getPatients']);
    $router->get('/{id}', [AdminPatientController::class, 'getPatientById'])->whereNumber('id');
    $router->post('/', [AdminPatientController::class, 'addPatient']);
});

Route::group(['prefix' => 'appointment'], function (Router $router) {
    $router->get('/', [AdminAppointmentController::class, 'getAppointments']);
    $router->get('/{id}', [AdminAppointmentController::class, 'getAppointmentById'])->whereNumber('id');
    $router->get('/date/{date}', [AdminAppointmentController::class, 'getAppointmentsByDate']);
    $router->get('/from/{startDate}/to/{endDate}', [AdminAppointmentController::class, 'getAppointmentsForRange']);
    $router->post('/', [AdminAppointmentController::class, 'addAppointment']);
    $router->get('/last', [AdminAppointmentController::class, 'lastAppointments']);
});

Route::group(['prefix' => 'statistics'], function (Router $router) {
    $router->get('/', [AdminStatsController::class, 'getStatistics']);
});
