<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);

Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/ {token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

Route::get('/post/download', [PostController::class, 'downloadPostCSV'])->name('downloadPostCSV');
Route::get('/post/list', [PostController::class, 'showPostList'])->name('postlist');
Route::get('/post/search', [PostController::class, 'showPostList'])->name('postlist.search');
Route::post('/post/search', [PostController::class, 'filterPost'])->name('postSearch');
Route::get('user/search', [UserController::class, 'showUserList'])->name('userlist');
Route::post('user/search', [UserController::class, 'userSearch'])->name('userSearch');
Route::get('/register', [RegisterController::class, 'showUserRegistration'])->name('userRegister');
Route::post('/register', [RegisterController::class, 'submitUserRegistration'])->name('userRegister');

Route::get('/', function () {
    return redirect()->route('postlist');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
//visitor, user, admin authorized

// 'Post\PostController@showPostList'
Route::group(['middleware' => ['auth']], function () {
    Route::get('post/create', [PostController::class, 'showPostCreateView'])->name('create.post');
    Route::post('/post/create', [PostController::class, 'submitPostCreateView'])->name('create.post');
    Route::get('/post/create/confirm', [PostController::class, 'showPostCreateConfirmView'])->name('post.create.confirm');
    Route::post('/post/create/confirm', [PostController::class, 'submitPostCreateConfirmView'])->name('post.create.confirm');
    Route::delete('/post/delete/{postId}', [PostController::class, 'deletePostById'])->name('post.delete');
    Route::get('/post/edit/{postId}', [PostController::class, 'showPostEdit'])->name('posts.edit');
    Route::post('/post/edit/{postId}', [PostController::class, 'submitPostEditView'])->name('posts.edit');
    Route::get('/post/edit/{postId}/confirm', [PostController::class, 'showPostEditConfirmView'])->name('posts.edit.confirm');
    Route::post('/post/edit/{postId}/confirm', [PostController::class, 'submitPostEditConfirmView'])->name('submitPostEditConfirmView');
    Route::get('/post/upload', [PostController::class, 'showPostUploadView'])->name('post.upload');
    Route::post('/post/upload', [PostController::class, 'submitPostUploadView'])->name('post.upload');
});

//profile routes
Route::get('/profile/{userId}/{profileName}', [FileController::class, 'getUserProfile'])->name('profile.photo');
Route::get('/user/profile', [UserController::class, 'showUserProfile'])->name('profile');
Route::get('/user/profile/edit', [UserController::class, 'showUserProfileEdit'])->name('profile.edit');
Route::post('/user/profile/edit', [UserController::class, 'submitEditProfileView'])->name('profile.edit');
Route::get('/user/profile/edit/confirm', [UserController::class, 'showEditProfileConfirmView'])->name('profile.edit.confirm');
Route::post('/user/profile/edit/confirm', [UserController::class, 'submitProfileEditConfirmView'])->name('profile.edit.confirm');
Route::get('/user/change-password', [UserController::class, 'showChangePasswordView'])->name('change.password');
Route::post('/user/change-password', [UserController::class, 'savePassword'])->name('change.password');

//admin authorized
//Route::group(['middleware' => ['admin']], function () {}
Route::group(['middleware' => ['auth']], function () {
    Route::get('user/list', [UserController::class, 'showUserList'])->name('userlist');
    Route::get('user/register', [RegisterController::class, 'showRegistrationView'])->name('register');
    Route::post('user/register', [RegisterController::class, 'submitRegistrationView'])->name('register');
    Route::get('user/register/confirm', [RegisterController::class, 'showRegistrationConfirmView'])->name('register.confirm');
    Route::post('user/register/confirm', [RegisterController::class, 'submitRegistrationConfirmView'])->name('registerConfirm');
    Route::delete('/user/delete', [UserController::class, 'deleteUserById'])->name('user.delete');
});
