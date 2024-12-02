<?php

use App\Http\Controllers\api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [ApiController::class, 'register']);
});

Route::prefix('danh-muc')->group(function () {
    Route::post('/add', [ApiController::class, 'add']);
    Route::get('/all', [ApiController::class, 'all']);
    Route::get('/get/{id}', [ApiController::class, 'get']);
    Route::post('/update/{id}', [ApiController::class, 'update']);
    Route::delete('/delete/{id}', [ApiController::class, 'delete']);
});


Route::prefix('san-pham')->group(function () {
    Route::post('/add', [ApiController::class, 'addSanPham']);
    Route::get('/all', [ApiController::class, 'allSanPham']);
    Route::get('/get/{id}', [ApiController::class, 'getSanPham']);
    Route::post('/update/{id}', [ApiController::class, 'updateSanPham']);
    Route::delete('/delete/{id}', [ApiController::class, 'deleteSanPham']);
    Route::get('/anh/{id}', [ApiController::class, 'getAnh']);
    Route::post('/upload-anh', [ApiController::class, 'uploadAnh']);
    Route::delete('/delete-anh/{id}', [ApiController::class, 'delAnh']);

    Route::get('/danhmuc/{id}', [ApiController::class, 'sanphamDanhmucID']);

    Route::prefix('cart')->group(function () {
        Route::post('/add', [ApiController::class, 'addToCart']);
        Route::get('/all/{id}', [ApiController::class, 'allCart']);
    });
});

Route::prefix('trang-thai')->group(function () {
    Route::post('/add', [ApiController::class, 'addTrangThai']);
    Route::get('/all', [ApiController::class, 'allTrangThai']);
    Route::get('/get/{id}', [ApiController::class, 'getTrangThai']);
    Route::put('/update/{id}', [ApiController::class, 'updateTrangThai']);
    Route::delete('/delete/{id}', [ApiController::class, 'deleteTrangThai']);
});

Route::prefix('user')->group(function () {
    Route::post('/add', [ApiController::class, 'addUser']);
    Route::get('/all', [ApiController::class, 'allUser']);
    Route::get('/get/{id}', [ApiController::class, 'getUser']);
    Route::post('/update/{id}', [ApiController::class, 'updateUser']);
});
Route::post('/thanh-toan/{id}', [ApiController::class, 'thanhtoan']);
Route::post('/upload/services', [ApiController::class, 'upload']);