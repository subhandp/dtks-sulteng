<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksErrorsImport;
use  App\Models\PmksDataTemp;
use  App\Models\PmksData;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;  
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\PmksDataImport;
use App\Jobs\ProcessImportPsks;
use App\Jobs\ProcessDataChartPsks;

// use App\Jobs\PostingImport;
use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Hashids\Hashids;
use Illuminate\Support\Facades\File;
use stdClass;
use Illuminate\Support\Facades\Bus;

use App\Exports\PmksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Core\XLSXWriter;
use Illuminate\Support\Facades\Log;
use Storage;
class PsksController extends Controller
{
    
    public $counter;
    public $metodeImport;
    public function index()
    {
        $data_psks_import = DB::table('dtks_imports')
        ->orderBy('updated_at', 'desc')
        ->get();
        
        $jenisPsks = DB::table('jenis_psks')->select('jenis','detail')->get();
        $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();

        $class_menu_psks = "menu-open";
        $class_menu_psks_import = "sub-menu-open";
        $class_menu_psks_daftar = "";
        

        return view('psks.index', compact('kabupatenKota', 'jenisPsks','data_psks_import','class_menu_psks','class_menu_psks_import','class_menu_psks_daftar'));

    }

    public function store (Request $request)
    {
       $request->validate([
            'jenis_psks' => 'required',
            'upload' => 'required'
       ]);

        $jobs = DB::table('jobs')->select("*")->count();
        if($jobs > 0){
            return redirect('/psks/import-data')->with("gagal-jobs", 1);
        }
        else{
            ProcessImportPsks::dispatch(['upload' => $request->input('upload'), 'jenis_psks' => $request->input('jenis_psks'), 'kabupaten_kota' => $request->input('kabupaten_kota'),'jenis_dtks' => 'psks']);

            // $batch = Bus::batch([])->dispatch();

            // $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
            // $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis',$request->input('jenis_psks'))->first();

            // foreach ($kabupatenKota as $kk) {

            //         $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));

            // }

            return redirect('/psks/import-data')->with("sukses", 1);
        }
        
    }


}


