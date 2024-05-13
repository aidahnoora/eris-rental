<?php

use App\Http\Controllers\WebAPI\AuthController;
use App\Http\Controllers\WebAPI\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebAPI\CustomerController;
use App\Http\Controllers\WebAPI\MerkController;
use App\Http\Controllers\WebAPI\MobilController;
use App\Http\Controllers\WebAPI\TransaksiController;
use App\Http\Controllers\WebAPI\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/customers', CustomerController::class);
Route::apiResource('/users', UserController::class);
Route::apiResource('/merks', MerkController::class);
Route::apiResource('/mobils', MobilController::class);
Route::get('/get-merks', [MobilController::class, 'getMerks'])->name('get-merks');

Route::apiResource('/transaksis', TransaksiController::class);
Route::get('/get-customers', [TransaksiController::class, 'getCustomers'])->name('get-customers');
Route::get('/get-mobils', [TransaksiController::class, 'getMobils'])->name('get-mobils');
Route::get('/transaksis/{transaksiId}/items', [TransaksiController::class, 'getItemTransaksis']);
Route::get('/laporan-transaksi-penjualan', [TransaksiController::class, 'report']);

Route::get('/get-transaction', [DashboardController::class, 'getTransaction'])->name('get-transaction');
Route::get('/get-categories', [DashboardController::class, 'getCategories'])->name('get-categories');

Route::get('/transaksi-penyewaan', [TransaksiController::class, 'getByTanggal']);

Route::get('/transaksi-penyewaan/filter', [TransaksiController::class, 'filter']);
Route::get('/transaksi-penyewaan/export-pdf', [TransaksiController::class, 'exportPDF']);

Route::get('/mobils/filter/{status}', [MobilController::class, 'filterByStatus']);
