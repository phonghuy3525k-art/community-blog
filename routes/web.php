<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. NHÓM TRANG CÔNG KHAI (Nhóm A)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/posts/{slug}', [HomeController::class, 'show'])->name('posts.show');

// ==========================================
// 2. NHÓM ĐÃ ĐĂNG NHẬP (Thành viên & Admin)
// ==========================================
Route::middleware('auth')->group(function () {
    // Thêm route dashboard bắt buộc để fix lỗi Route [dashboard] not defined của Breeze
    Route::get('/dashboard', function () {
        return redirect()->route('my-posts.index');
    })->name('dashboard');

    // Quản lý Profile (Mặc định của Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Quản lý bài viết cá nhân (Nhóm B - CRUD bài viết, thùng rác, khôi phục)
    Route::resource('my-posts', PostController::class);
    Route::patch('/my-posts/{id}/restore', [PostController::class, 'restore'])->name('my-posts.restore');
    Route::delete('/my-posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('my-posts.force-delete');

    // Bình luận (Nhóm C)
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// ==========================================
// 3. NHÓM TRANG QUẢN TRỊ (Nhóm D - Admin)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Trang thống kê Dashboard của Admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý danh mục
    Route::resource('categories', CategoryController::class);

    // Quản lý toàn bộ bài viết / bình luận vi phạm
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
});

require __DIR__.'/auth.php';