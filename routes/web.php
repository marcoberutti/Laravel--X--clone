<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;

Auth::routes();
// Visualizzazione del form di login tramite GET
Route::post("/login", [LoginController::class, "login"])->name("login.submit");
Route::get("/", function(){return view("welcome");})->name('vistaWelcome');
Route::get('/home', [UserController::class,'index'])->name('vistaHomeDentro');

// Creazione e memorizzazione di utenti
Route::get('/users/create', [UserController::class, 'index'])->name('users.create');
Route::post('/users/store', [UserController::class,'store'])->name('users.store');


Route::post('/users/find', [UserController::class, 'find'])->name('users.find');
Route::get('/users/find', [UserController::class,'index'])->name('show users');
Route::get('/users/show', [UserController::class, 'destroy'])->name('bringBackafterDelete');


//update or delete
Route::get('delete/{id}', [UserController::class,'destroy'])->name('deleteRow');
Route::get('edit/{id}', [UserController::class,'edit'])->name('updateUser');
Route::put('update/{id}', [UserController::class,'update']);
Route::get('users/show', [UserController::class,'update']);

Route::get('/tweets/create', [TweetsController::class, 'index'])->name('tweets.create');
Route::post('/tweets/store', [TweetsController::class,'store'])->name('tweets.store');

Route::get('tweets/edit/{id}', [TweetsController::class, 'edit']);
Route::put('tweets/update/{id}', [TweetsController::class, 'update']);

Route::get('/tweets/delete/{id}', [TweetsController::class,'destroy'])->name('');

Route::get('/users/find2/{id}', [UserController::class, 'find2'])->name('users.find2');

// simple return view
Route::get("/users/dashboard", function(){return view("users/dashboard");})->name('vistaWelcome');



// Route::resource('like', LikeController::class);
Route::post('/like/store', [LikeController::class, 'store'])->name('like.store');
Route::post('/like/destroy/{tweetId}', [LikeController::class, 'destroy'])->name('like.destroy');

Route::get('/users/chart', [UserController::class, 'showChart']);

Route::post('/follow', [FollowController::class,'store'])->name('follow.store');
Route::post('/follow/destroy/{followed}', [FollowController::class, 'destroy'])->name('follow.destroy');

