<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardControllerPsks;
use App\Http\Controllers\PmksController;
use App\Http\Controllers\PsksController;
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
Route::get('/login/pmks',[AuthController::class, 'loginPmks'])->name('login.pmks');
Route::get('/login/psks',[AuthController::class, 'loginPsks'])->name('login.psks');
Route::post('/postlogin-pmks',[AuthController::class, 'postloginPmks'])->name('post.login.pmks');
Route::post('/postlogin-psks',[AuthController::class, 'postloginPsks'])->name('post.login.psks');

Route::get('/logout',[AuthController::class ,'logout']);
Route::get('/pmks/dashboard',[DashboardController::class, 'index']);
Route::group(['middleware' => ['auth','checkRole:operator_psks,operator_pmks']], function () {
    Route::get('/', function () {
        return view('/dashboard');
    });
    
    Route::post('/set-session-pmks', [DashboardController::class, 'set_session_pmks'])->name('dashboard.set-session');
    Route::get('/get-jenis-pmks', [DashboardController::class, 'get_jenis_pmks'])->name('dashboard.get-jenis-pmks');
    Route::get('/get-pmks-kab', [DashboardController::class, 'get_pmks_kab'])->name('dashboard.get-pmks-kab');

    Route::get('/dtks/select-dtksimport',[DtksController::class, 'selectdtksimport'])->name('dtks.selectdtksimport');

    Route::get('/provinces', [DependentDropdownController::class,'provinces'])->name('provinces');
    Route::get('/cities', [DependentDropdownController::class,'cities'])->name('cities');
    Route::get('/districts', [DependentDropdownController::class, 'districts'])->name('districts');
    Route::get('/villages', [DependentDropdownController::class, 'villages'])->name('villages');

    Route::post('filepond/process', [FilepondController::class, 'process'])->name('filepond.process');
    Route::get('filepond/getfile/{id}', [FilepondController::class, 'getfile'])->name('filepond.getfile');
    Route::delete('filepond/revert', [FilepondController::class, 'revert'])->name('filepond.revert');
    Route::get('filepond/remove/{requestid}', [FilepondController::class, 'remove'])->name('filepond.remove');

});

Route::group(['middleware' => ['auth','checkRole:operator_psks']], function () {
    Route::get('/psks/dashboard',[DashboardControllerPsks::class, 'index']);
    Route::get('/get-psks-kab', [DashboardControllerPsks::class, 'get_psks_kab'])->name('dashboard.get-psks-kab');

    Route::get('/psks/import-data',[PsksController::class, 'index']);
    Route::get('/psks/daftar-psks',[PsksController::class, 'daftarpsks']);
    Route::post('/psks/store-import',[PsksController::class, 'store']);

    Route::get('/psks/data',[PsksController::class, 'datapsm'])->name('psks.data');
    Route::get('/psks/data-psks',[PsksController::class, 'datapsks'])->name('psks.datapsks');
    Route::get('/psks/data-errors',[PsksController::class, 'dataerrors'])->name('psks.dataerrors');

    Route::get('/psks/create',[PsksController::class, 'create'])->name('psks.create');
    
    Route::get('/psks/create/edit',[PsksController::class, 'editCreate'])->name('psks.edit-create');
    Route::post('/psks/store-edit',[PsksController::class, 'storeEdit'])->name('psks.store-edit');
    Route::get('/psks/delete',[PsksController::class, 'delete'])->name('psks.delete');

    Route::get('/psks/psm',[PsksController::class, 'indexPsm'])->name('psks.psm.index');
    Route::get('/psks/psm/datatatables',[PsksController::class, 'datatablesPsm'])->name('psks.psm.datatables');

    Route::get('/psks/psm/create',[PsksController::class, 'psmEditCreate'])->name('psks.psm.create');
    Route::get('/psks/psm/edit',[PsksController::class, 'psmEditCreate'])->name('psks.psm.edit');
    Route::get('/psks/psm/delete',[PsksController::class, 'psmDelete'])->name('psks.psm.delete');
    Route::post('/psks/psm/store-create',[PsksController::class, 'psmStore'])->name('psks.psm.store-create');
    Route::post('/psks/psm/store-edit',[PsksController::class, 'psmStore'])->name('psks.psm.store-edit');

    Route::get('/psks/download/excel',[PsksController::class, 'downloadExcel'])->name('psks.download.excel');
    Route::get('/psks/download/get-download-list',[PsksController::class, 'getDownloadExcelList'])->name('psks.download.excel.list');

    Route::get('/psks/tksk',[PsksController::class, 'indexTksk'])->name('psks.tksk.index');
    Route::get('/psks/tksk/datatatables',[PsksController::class, 'datatablesTksk'])->name('psks.tksk.datatables');

    Route::get('/psks/tksk/create',[PsksController::class, 'tkskEditCreate'])->name('psks.tksk.create');
    Route::get('/psks/tksk/edit',[PsksController::class, 'tkskEditCreate'])->name('psks.tksk.edit');
    Route::get('/psks/tksk/delete',[PsksController::class, 'tkskDelete'])->name('psks.tksk.delete');
    Route::post('/psks/tksk/store-create',[PsksController::class, 'tkskStore'])->name('psks.tksk.store-create');
    Route::post('/psks/tksk/store-edit',[PsksController::class, 'tkskStore'])->name('psks.tksk.store-edit');

});

Route::group(['middleware' => ['auth','checkRole:operator_pmks']], function () {
    

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

    Route::post('/pmks/get-export-excel', [PmksController::class, 'getExportExcel'])->name('get-download-excel');
    Route::get('/pmks/export-excel', [PmksController::class, 'exportExcel'])->name('download-excel');
});

