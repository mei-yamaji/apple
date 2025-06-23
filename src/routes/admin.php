<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

// 管理者ログイン関連（ゲストのみ）
Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('admin.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');
});

// 管理者ログアウト（認証あり）
Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});

// 管理者認証済みかつadminプレフィックス・名前空間グループ
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    // コメント管理（一覧・編集・更新・削除）
    Route::resource('comments', CommentController::class)->only(['index', 'edit', 'update', 'destroy']);

    // カテゴリ管理（show以外全部）
    Route::resource('categories', CategoryController::class)->except(['show']);

    // タグ管理（show以外全部）
    Route::resource('tags', TagController::class)->except(['show']);
});
