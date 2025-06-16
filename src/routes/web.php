<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('boards', BoardController::class);
    Route::get('/boards/{type}', [BoardController::class, 'getBoards']);
    Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');

    Route::post('boards/{board}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/boards/{board}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/boards/{board}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/boards/{board}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');

    Route::get('/mypage', function () {
        return view('mypage');
    })->name('mypage');


});




require __DIR__.'/auth.php';
