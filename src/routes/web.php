<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BoardController as AdminBoardController;
use App\Models\Board;
 
Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 
Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ボード関連リソースルート（RESTful）
    Route::resource('boards', BoardController::class);
    Route::get('/boards/rankings/{type?}', [BoardController::class, 'fetchRanking'])->name('boards.rankings');
    Route::get('/mypage/boards', [BoardController::class, 'myBoards'])->name('boards.my');

    // 人気・閲覧・最新ランキング用ルート（リソースルートの下に置く）
    Route::get('/boards/ranking/{type}', [BoardController::class, 'fetchRanking'])->name('boards.rankings');

    // いいね関連
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{board}', [LikeController::class, 'destroy'])->name('likes.destroy');
    
    // コメント関連
    Route::post('boards/{board}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/boards/{board}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/boards/{board}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/boards/{board}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ユーザー関連
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');

    // 画像投稿機能
    Route::post('/boards/image-upload', [BoardController::class, 'uploadImage'])->name('boards.image-upload');
 
});

// 管理者認証が必要なルート
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::resource('users', AdminUserController::class)->only(['index',  'edit', 'update', 'destroy']);
        Route::resource('boards', AdminBoardController::class)->only(['index', 'edit', 'update', 'destroy']);
     });   
});

require __DIR__.'/auth.php';

// 管理者用ルート（admin.phpを読み込む）
Route::prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});