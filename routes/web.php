<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\DepartmentController;


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
    Route::get('/admin/users/fetch/{perPage}/{search}/{orderBy}', [UserController::class, 'fetch'])->name('users.paginate.fetch');
    Route::get('/admin/user/get/{id}', [UserController::class, 'getSingleUser'])->name('user.get.single');
    Route::post('/admin/user/status/update', [UserController::class, 'updateUserStatus'])->name('user.status.update');
    Route::post('/admin/user/save', [UserController::class, 'saveUserData'])->name('user.save.data');
    Route::post('/admin/user/check/email', [UserController::class, 'checkEmail'])->name('user.check.email');
    Route::post('/admin/user/changepassword', [UserController::class, 'changePassword'])->name('user.change.password'); 

    /**
     * News & Articles
     * Events
     */
    Route::get('/admin/posts-fetch/{posttype}/{perPage}/{search}/{orderBy}', [PostController::class, 'fetch'])->name('posts.paginate.fetch');
    Route::get('/admin/posts-frontend/{perPage}/{search}/{orderBy}', [PostController::class, 'fetchFrontend'])->name('posts.front.paginate.fetch');
    Route::post('/admin/media/save', [PostController::class, 'saveData'])->name('save.post.data');
    Route::get('/admin/media/get/{id}', [PostController::class, 'edit'])->name('show.single.post');
    Route::post('/admin/post-editor/upload', [PostController::class, 'upload'])->name('upload.files'); 
    Route::get('/admin/media/delete/{id}', [PostController::class, 'destroy'])->name('delete.post'); 
    
    // Media Images 
    Route::get('/admin/fetch/all/images', [ImageController::class, 'getMediaFiles'])->name('images.fetch.all');
    Route::get('/admin/fetch/by-user/images/{id}', [ImageController::class, 'getMediaFilesByUser'])->name('images.fetch.by-user');
    Route::post('/admin/file/upload',  [ImageController::class, 'dropzoneUpload'])->name('dropzone.upload')->middleware('auth'); 

    // Comments
    Route::post('/admin/comment/update-status',  [CommentController::class, 'update'])->name('comment.update.status')->middleware('auth'); 

    /**
     * Companies & Departments
     */
    Route::get('/admin/fetch/companies/{perPage}/{search}/{orderBy}', [CompanyController::class, 'fetch'])->name('companies.paginate.fetch');
    Route::get('/admin/fetch/non-paginate/companies', [CompanyController::class, 'fetchNonpaginate'])->name('companies.non-paginate.fetch');
    Route::get('/admin/fetch/departments/{perPage}/{search}/{orderBy}', [DepartmentController::class, 'fetch'])->name('departments.paginate.fetch');
    Route::get('/admin/fetch/non-paginate/departments', [DepartmentController::class, 'fetchNonpaginate'])->name('departments.non-paginate.fetch');

    /**
     * Categories
     */
    Route::get('/admin/fetch/categories/{perPage}/{search}/{orderBy}', [CategoryController::class, 'fetch'])->name('categories.paginate.fetch');
    Route::get('/admin/fetch/non-paginate/categories', [CategoryController::class, 'fetchNonpaginate'])->name('categories.non-paginate.fetch');
    Route::get('/admin/category/get/{id}', [CategoryController::class, 'edit'])->name('show.single.category');
});

Route::group(['prefix'=>'d','as'=>'home.', 'middleware' => 'auth'], function(){

    /**
     * vue-router pages
     */
    Route::get('/', [HomeController::class, 'home']);
    Route::get('/{page}', [HomeController::class, 'home']);
    Route::get('/{page}/{action}', [HomeController::class, 'home']);
    Route::get('/{page}/edit/{id}', [HomeController::class, 'home']); 
     
     /**
     * Home Poll Answer
     */
    Route::post('/home/poll-answer/form', [PostController::class, 'submitPollAnswer'])->name('home.poll.answer'); 
    Route::post('/home/post-comment/form', [CommentController::class, 'submitComment'])->name('home.post.comments'); 

    /**
     * Home Like
     */
    Route::post('/home/post-like/form',[LikeController::class, 'postLike'])->name('home.post.like');

}); 

Route::get('/file/{path}',  [ImageController::class, 'showFile'])->name('file.show')->middleware('auth');