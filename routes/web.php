<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PmksController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/postlogin',[AuthController::class, 'postlogin']);
Route::get('/logout',[AuthController::class ,'logout']);

Route::group(['middleware' => ['auth','checkRole:admin']], function () {
    Route::get('/', function () {
        return view('/dashboard');
    });
    Route::get('/dashboard',[DashboardController::class, 'index']);
    Route::get('/pmks/import-data',[PmksController::class, 'index']);
    Route::get('/pmks/daftar-pmks',[PmksController::class, 'daftarpmks']);


});
