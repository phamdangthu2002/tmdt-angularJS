<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DanhmucController;
use App\Http\Controllers\admin\DonhangController;
use App\Http\Controllers\admin\SanphamController;
use App\Http\Controllers\admin\TrangthaiController;
use App\Http\Controllers\admin\UserController as AdminUserController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UserController::class, 'index']);

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/login-post', [AuthController::class, 'postLogin'])->name('auth.postLogin');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/trang-chu', [AdminController::class, 'trangchu'])->name('admin.trangchu');

    Route::prefix('danh-muc')->group(function () {
        Route::get('/', [DanhmucController::class, 'index'])->name('admin.danhmuc');// danh muc
        Route::get('/show', [DanhmucController::class, 'show'])->name('admin.showDanhmuc');
    });

    Route::prefix('san-pham')->group(function () {
        Route::get('/', [SanphamController::class, 'index'])->name('admin.sanpham');
        Route::get('/show', [SanphamController::class, 'show'])->name('admin.showSanpham');
    });

    Route::get('/don-hang', [DonhangController::class, 'index'])->name('admin.donhang');

    Route::prefix('trang-thai')->group(function () {
        Route::get('/', [TrangthaiController::class, 'trangthai'])->name('admin.trangthai');
        Route::get('/show', [TrangthaiController::class, 'show'])->name('admin.showTrangthai');
    });

    Route::get('/user', [AdminUserController::class, 'index'])->name('admin.user');
    Route::get('/show', [AdminUserController::class, 'show'])->name('admin.showUser');
});

Route::prefix('user')->group(function () {
    Route::get('/trang-chu', [UserController::class, 'index'])->name('user.trangchu');// trang chu
    Route::get('/danh-muc', [UserController::class, 'danhmuc'])->name('user.danhmuc');// danh muc
    Route::get('/thanh-toan/{id}', [UserController::class, 'thanhtoan'])->name('user.thanhtoan');
    Route::post('/thanh-toan/payment/{id}', [UserController::class, 'payment'])->name('user.payment');

});