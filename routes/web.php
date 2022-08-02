<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Authenticaed User for VUEX
 */
Route::get('/auth_user', [UserController::class, 'getAuthenticatedUser'])->name('get.authenticated.user');
Auth::routes([
    'register' => false, // Registration Routes...
    //'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);
Route::get('/', function () { return redirect('/d/dashboard');});
Route::get('/home', function () { return redirect('/d/dashboard');});
Route::group(['prefix'=>'d','as'=>'dashboard.', 'middleware' => 'auth'], function(){

    /**
     * vue-router pages
     */
    Route::get('/', [DashboardController::class, 'dashboard']);
    Route::get('/{page}', [DashboardController::class, 'dashboard']);
    Route::get('/{page}/{action}', [DashboardController::class, 'dashboard']);
    Route::get('/{page}/edit/{id}', [DashboardController::class, 'dashboard']);
    
    Route::get('/drivers/{page}/{id}', [DashboardController::class, 'dashboard']); 
    Route::get('/cars/{page}/{id}', [DashboardController::class, 'dashboard']); 
    Route::get('/car/new-incident/{id}', [DashboardController::class, 'dashboard']); 
    
    /**
     * Users
     */
    
    //Route::get('/users/fetch/all', [UserController::class, 'getAllUsers'])->name('users.get.all');
    Route::get('/users/fetch/{perPage}/{search}', [UserController::class, 'getAllUsers'])->name('users.paginate.fetch');
    Route::get('/user/get/{id}', [UserController::class, 'getSingleUser'])->name('user.get.single');
    Route::post('/user/status/update', [UserController::class, 'updateUserStatus'])->name('user.status.update');
    Route::post('/user/save', [UserController::class, 'saveUserData'])->name('user.save.data');
    Route::post('/user/check/email', [UserController::class, 'checkEmail'])->name('user.check.email');
    Route::post('/user/changepassword', [UserController::class, 'changePassword'])->name('user.change.password');

    /**
     * Drivers
     */

    Route::get('/driver/fetch/all', [DriverController::class, 'fetchAll'])->name('drivers.fetch.all');
    Route::get('/drivers/fetch/{perPage}/{search}', [DriverController::class, 'fetch'])->name('driver.paginate.fetch');
    Route::post('/driver/save', [DriverController::class, 'saveData'])->name('driver.save.data');
    Route::get('/driver/get/{id}', [DriverController::class, 'edit'])->name('driver.get.single');


    /**
     * Cars
     */
   
    Route::get('/cars/fetch/{perPage}/{search}/{orderBy}', [CarController::class, 'fetch'])->name('cars.fetch.all');
    Route::post('/car/save', [CarController::class, 'saveData'])->name('car.save.data');
    Route::post('/car/incident/save', [CarController::class, 'saveIncidentData'])->name('car.incident.save'); 
    Route::get('/car/get/{id}', [CarController::class, 'edit'])->name('car.get.single');
    Route::get('/car/delete/{id}', [CarController::class, 'destroy'])->name('car.destroy'); 
  
    
    /**
     * Car incident
     */

    Route::post('/car/fetch/incidents', [CarController::class, 'fetchIncidents'])->name('car.fetch.incidents');


     /**
     * Notifications
     */

    Route::get('/notifications/fetch/all', [NotificationController::class, 'fetchAll'])->name('notification.fetch.all');
    Route::get('/notifications/fetch/{perPage}/{search}', [NotificationController::class, 'fetch'])->name('notification.paginate.fetch');
    Route::post('/notification/save', [NotificationController::class, 'saveData'])->name('notification.save.data');
    Route::get('/notification/get/{id}', [NotificationController::class, 'edit'])->name('notification.get.single');
});