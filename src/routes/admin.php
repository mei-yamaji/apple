<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');
});

Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('comments', CommentController::class)->only(['index', 'edit', 'update', 'destroy']);
});

Route::middleware('auth:admin')->name('admin.')->prefix('admin')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['show']);
});