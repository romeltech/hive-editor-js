<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ModeratorController;


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
Route::get('/', function () { return redirect('/d/home');});
Route::get('/home', function () { return redirect('/d/home');});


Route::group(['prefix'=>'d','as'=>'moderator.', 'middleware' => 'auth'], function(){

    /**
     * vue-router pages
     */
    Route::get('/', [ModeratorController::class, 'moderator']);
    Route::get('/admin/{page}', [ModeratorController::class, 'moderator']);
    Route::get('/admin/{page}/{action}', [ModeratorController::class, 'moderator']);
    Route::get('/admin/{page}/edit/{id}', [ModeratorController::class, 'moderator']); 
    
    /**
     * Users
     */
    
    //Route::get('/users/fetch/all', [UserController::class, 'getAllUsers'])->name('users.get.all');
    Route::get('/admin/users/fetch/{perPage}/{search}', [UserController::class, 'getAllUsers'])->name('users.paginate.fetch');
    Route::get('/admin/user/get/{id}', [UserController::class, 'getSingleUser'])->name('user.get.single');
    Route::post('/admin/user/status/update', [UserController::class, 'updateUserStatus'])->name('user.status.update');
    Route::post('/admin/user/save', [UserController::class, 'saveUserData'])->name('user.save.data');
    Route::post('/admin/user/check/email', [UserController::class, 'checkEmail'])->name('user.check.email');
    Route::post('/admin/user/changepassword', [UserController::class, 'changePassword'])->name('user.change.password'); 

    /**
     * News & Articles
     */
    Route::get('/admin/posts-fetch/{perPage}/{search}/{orderBy}', [PostController::class, 'fetch'])->name('posts.paginate.fetch');
    Route::post('/admin/media/save', [PostController::class, 'saveData'])->name('save.post.data');
    // Route::get('/drivers/fetch/{perPage}/{search}', [DriverController::class, 'fetch'])->name('driver.paginate.fetch');
    Route::post('/admin/post-editor/upload', [PostController::class, 'upload'])->name('upload.files'); 
    
    // Media Images 
    Route::get('/admin/fetch/all/images', [ImageController::class, 'getMediaFiles'])->name('images.fetch.all');
    Route::post('/admin/file/upload',  [ImageController::class, 'dropzoneUpload'])->name('dropzone.upload')->middleware('auth');
    
});

Route::group(['prefix'=>'d','as'=>'home.', 'middleware' => 'auth'], function(){

    /**
     * vue-router pages
     */
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/{page}', [HomeController::class, 'home']);
    Route::get('/{page}/{action}', [HomeController::class, 'home']);
    Route::get('/{page}/edit/{id}', [HomeController::class, 'home']); 
     
     
}); 

Route::get('/file/{path}',  [ImageController::class, 'showFile'])->name('file.show')->middleware('auth');