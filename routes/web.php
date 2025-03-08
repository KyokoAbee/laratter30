<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RecommendationController;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ログイン後一覧
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

    // 投稿作成画面。nameは[]内の短縮系。
    Route::get('/post/create',[PostController::class, 'create'])->name('post.create');
    // 作成処理
    Route::post('/post',[PostController::class, 'store'])->name('post.store');
    // 投稿内容の表示
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    // 編集
    Route::put('/post/{id}', [PostController::class, 'update'])->name('post.update');

    // 検索
    Route::get('/search', [PostController::class, 'search'])->name('search');

    // 検索結果の表示
    Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

    // 返信
    Route::get('/post/{id}/recommendations/create', [RecommendationController::class, 'create'])->name('recommendation.create');
    //返信処理
    Route::post('/post/{id}/recommendations', [RecommendationController::class, 'store'])->name('recommendation.store');
    // 返信の修正
    Route::get('/post/{postId}/recommendations/{recommendationId}/edit', [RecommendationController::class, 'edit'])->name('recommendation.edit')->middleware('auth');
    // 修正したものを更新
    Route::put('/post/{postId}/recommendations/{recommendationId}', [RecommendationController::class, 'update'])->name('recommendation.update')->middleware('auth');


    // ベストレコメンド
    Route::post('/post/{postId}/recommendation/{recommendationId}/best', [RecommendationController::class, 'setBest'])->name('recommendation.setBest')->middleware('auth');
});

require __DIR__.'/auth.php';
