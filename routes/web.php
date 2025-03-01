<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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

});

require __DIR__.'/auth.php';
