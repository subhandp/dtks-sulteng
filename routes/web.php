<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PmksController;
use App\Http\Controllers\FilepondController;
use App\Http\Controllers\DtksController;





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
    Route::post('/pmks/store-import',[PmksController::class, 'store']);
    
    Route::get('/pmks/import-status', [PmksController::class, 'status']);
    Route::get('/pmks/impor-data-pmks', [PmksController::class, 'dataimportpmks'])->name('pmks.dataimport');

    Route::get('/pmks/data',[PmksController::class, 'data'])->name('pmks.data');
    Route::get('/pmks/data-pmks',[PmksController::class, 'datapmks'])->name('pmks.datapmks');
    Route::get('/pmks/data-errors',[PmksController::class, 'dataerrors'])->name('pmks.dataerrors');

    Route::post('/pmks/store-posting',[PmksController::class, 'posting']);

    Route::get('/dtks/posting',[DtksController::class, 'posting'])->name('dtks.posting');
    Route::get('/dtks/select-dtksimport',[DtksController::class, 'selectdtksimport'])->name('dtks.selectdtksimport');



});

Route::group(['middleware' => ['auth','checkRole:admin']], function () {
    Route::post('filepond/process', [FilepondController::class, 'process'])->name('filepond.process');
    Route::get('filepond/getfile/{id}', [FilepondController::class, 'getfile'])->name('filepond.getfile');
    Route::delete('filepond/revert', [FilepondController::class, 'revert'])->name('filepond.revert');
    Route::get('filepond/remove/{requestid}', [FilepondController::class, 'remove'])->name('filepond.remove');

});