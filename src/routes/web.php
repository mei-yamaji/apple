<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BoardController as AdminBoardController;
use App\Models\Board;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MypageController;
 
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
    Route::get('/profile/{user}/followings', [ProfileController::class, 'followings'])->name('profile.followings');
    Route::get('/profile/{user}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    // ボード関連リソースルート（RESTful）
    Route::resource('boards', BoardController::class);
    Route::get('/boards/rankings/{type?}', [BoardController::class, 'fetchRanking'])->name('boards.rankings');
    Route::get('/mypage/boards', [BoardController::class, 'myBoards'])->name('boards.my');
    Route::get('/user/{id}', [UserController::class, 'show']);

    //投稿作成関連
    Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
    Route::post('/boards/preview', [BoardController::class, 'preview'])->name('boards.preview');
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::match(['get', 'post'], '/boards/{board}/preview', [BoardController::class, 'previewUpdate'])->name('boards.preview.update');
    Route::put('/boards/{board}', [BoardController::class, 'update'])->name('boards.update');


    // 人気・閲覧・最新ランキング用ルート（リソースルートの下に置く）
    Route::get('/boards/ranking/{type}', [BoardController::class, 'fetchRanking'])->name('boards.rankings');

    // いいね関連
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{board}', [LikeController::class, 'destroy'])->name('likes.destroy');
    
    //マイページ関連
    // UserControllerのmypage関連は削除またはコメントアウト
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    // いいね機能もMypageControllerに実装し、ルート追加
    Route::get('/mypage/likes', [MypageController::class, 'likes'])->name('mypage.likes');
});

    
    // コメント関連
    Route::post('boards/{board}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/boards/{board}/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/boards/{board}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/boards/{board}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // ユーザー関連
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::patch('/boards/{board}/toggle-pin', [BoardController::class, 'togglePin'])->name('boards.togglePin');

    // ユーザーのお気に入り登録
    Route::post('/favorites/{user}', [FavoriteController::class, 'toggle'])->name('favorites.toggle')->middleware('auth');

    // 画像投稿機能
    Route::post('/boards/image-upload', [BoardController::class, 'uploadImage'])->name('boards.image-upload');

    // 通知機能
    Route::get('/notifications', [NotificationController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-selected-read', [NotificationController::class, 'markSelectedAsRead'])->name('notifications.markSelectedAsRead');
 
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

