<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PmksController;
use App\Http\Controllers\FilepondController;
use App\Http\Controllers\DtksController;
use App\Http\Controllers\DependentDropdownController;






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
    

    Route::get('/pmks/data',[PmksController::class, 'data'])->name('pmks.data');
    Route::get('/pmks/data-pmks',[PmksController::class, 'datapmks'])->name('pmks.datapmks');
    Route::get('/pmks/data-errors',[PmksController::class, 'dataerrors'])->name('pmks.dataerrors');

    Route::get('/pmks/create',[PmksController::class, 'create'])->name('pmks.create');
    Route::post('/pmks/store-create',[PmksController::class, 'storeCreate'])->name('pmks.store-create');
    Route::get('/pmks/create/edit',[PmksController::class, 'editCreate'])->name('pmks.edit-create');
    Route::post('/pmks/store-edit',[PmksController::class, 'storeEdit'])->name('pmks.store-edit');
    Route::get('/pmks/delete',[PmksController::class, 'delete'])->name('pmks.delete');

    Route::get('/dtks/select-dtksimport',[DtksController::class, 'selectdtksimport'])->name('dtks.selectdtksimport');


    Route::get('/provinces', [DependentDropdownController::class,'provinces'])->name('provinces');
    Route::get('/cities', [DependentDropdownController::class,'cities'])->name('cities');
    Route::get('/districts', [DependentDropdownController::class, 'districts'])->name('districts');
    Route::get('/villages', [DependentDropdownController::class, 'villages'])->name('villages');

    Route::post('/set-session-pmks', [DashboardController::class, 'set_session_pmks'])->name('dashboard.set-session');
    Route::get('/get-jenis-pmks', [DashboardController::class, 'get_jenis_pmks'])->name('dashboard.get-jenis-pmks');
    Route::get('/get-pmks-kab', [DashboardController::class, 'get_pmks_kab'])->name('dashboard.get-pmks-kab');

    Route::post('/pmks/get-export-excel', [PmksController::class, 'getExportExcel'])->name('get-download-excel');
    Route::get('/pmks/export-excel', [PmksController::class, 'exportExcel'])->name('download-excel');

});

Route::group(['middleware' => ['auth','checkRole:admin']], function () {
    Route::post('filepond/process', [FilepondController::class, 'process'])->name('filepond.process');
    Route::get('filepond/getfile/{id}', [FilepondController::class, 'getfile'])->name('filepond.getfile');
    Route::delete('filepond/revert', [FilepondController::class, 'revert'])->name('filepond.revert');
    Route::get('filepond/remove/{requestid}', [FilepondController::class, 'remove'])->name('filepond.remove');

});