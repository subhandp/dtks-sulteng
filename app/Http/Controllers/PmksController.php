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
use App\Jobs\ProcessImport;
use App\Jobs\PostingImport;
use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PmksController extends Controller
{
    
    public $counter;
    public $metodeImport;
    public function index()
    {
        $data_pmks_import = DB::table('dtks_imports')
        ->orderBy('updated_at', 'desc')
        ->get();

        $data_pmks_import_status = [];
        foreach ($data_pmks_import as $import) {
            $persentase = 0;
            $total_rows = 0;
            $current_row = 0;

            $status = [
                'started' => filled(cache("start_date_$import->id")),
                'finished' => filled(cache("end_date_$import->id")),
                'current_row' => $current_row,
                'total_rows' => $total_rows,
                'persentase' =>  $persentase
            ];
            $data_pmks_import_status[] = $status;
        }
        

        $class_menu_pmks = "menu-open";
        $class_menu_pmks_import = "sub-menu-open";
        $class_menu_pmks_daftar = "";
        $class_menu_posting = "";

        return view('pmks.index', compact('data_pmks_import_status','data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

        // return view('suratmasuk.index',['data_suratmasuk'=> $data_suratmasuk]);
    }

    public function dataerrors(){
        $id = request('id');
        $importErrors = DtksErrorsImport::where('dtks_import_id', $id)->orderBy('id', 'desc')->get(['row','values','errors']);
        return json_encode($importErrors);
        // return Datatables::of(DtksErrorsImport::where('dtks_import_id', $id)->select())->make(true);
    }

    public function dataimportpmks(){
        

        $data = PmksDataTemp::select('*');

        return DataTables::of($data)
                        ->addIndexColumn()
                                    ->addColumn('no_tiket',function(PmksDataTemp $pmksdatatemp){
                                        return $pmksdatatemp->dtksImports->no_tiket;
                                        //return DB::raw("SELECT * FROM 'patients' WHERE 'patients_id' = ?", $action->patient_id);
                                    })
                                    ->addColumn('action', function($row){
                    
                                        $btn = '<span class="badge badge-warning">Belum Posting</span><a href="javascript:void(0)" class="edit btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Edit"><i class="fas fa-edit"></i></a> <a href="javascript:void(0)" class="edit btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
                    
                                            return $btn;
                                    })
                                    ->rawColumns(['action'])
                                    ->make(true);

    }

    public function daftarpmks()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        // $data_daftar_pmks = DB::table('pmks_data_temps')
        // ->orderBy('created_at', 'desc')
        // ->get();
        $class_menu_pmks = "menu-open";
        $class_menu_daftar_pmks = "sub-menu-open";
        $class_menu_pmks_import = "";
        $class_menu_posting = "";

        return view('pmks.daftar-imported', compact('class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));

    }

    public function data(){
        $class_menu_data_pmks = "menu-open";
       
        return view('pmks.daftar', compact('class_menu_data_pmks'));

    }

    public function datapmks(){
        $data = PmksData::select('*');

        return DataTables::of($data)
                        ->addIndexColumn()
                                    
                                    ->addColumn('action', function($row){
                    
                                        $btn = '<a href="javascript:void(0)" class="edit btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fas fa-edit"></i></a> <a href="javascript:void(0)" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
                    
                                            return $btn;
                                    })
                                    ->rawColumns(['action'])
                                    ->make(true);

    }

    
    private function rrmdir($dir)
    {
        if (is_dir($dir))
        {
        $objects = scandir($dir);

        foreach ($objects as $object)
        {
            if ($object != '.' && $object != '..')
            {
                if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
                else {unlink($dir.'/'.$object);}
            }
        }

        reset($objects);
        rmdir($dir);
        }
    }
    
    public function store (Request $request)
    {
       $request->validate([
            'tahun_data' => 'required',
            'jenis_pmks' => 'required',
            'upload' => 'required'
       ]);

        $jobs = DB::table('jobs')->select("*")->count();
        if($jobs > 0){
            return redirect('/pmks/import-data')->with("gagal-jobs", 1);
        }
        else{
            ProcessImport::dispatch($request->input('upload'));
            return redirect('/pmks/import-data')->with("sukses", 1);
        }
        
        

    }

    public function posting(){
        $id = request('id');
        $jobs = DB::table('jobs')->select("*")->count();
        if($jobs > 0){
            return redirect('/pmks/import-data')->with("gagal-jobs", 1);
        }
        else{
            PostingImport::dispatch($id);
            return redirect('/pmks/import-data')->with("sukses-posting", 1);
        }

    }
    

    public function status()
    {
        
        $id = request('id');

        return response([
            'started' => filled(cache("start_date_$id")),
            'finished' => filled(cache("end_date_$id")),
            'current_row' => (int) cache("current_row_$id"),
            'total_rows' => (int) cache("total_rows_$id"),
        ]);
    }


}



 // dd($data_pmks_import);

        // $pmks_datas = DB::table('pmks_data_temps')
        //     ->where('dtks_import_id', '=',  1)
        //     ->get();
        // dd($pmks_data);
            // $this->counter = 0;

            // $this->metodeImport = 'firstOrCreate';
            // DB::table('pmks_data_temps')->where('dtks_import_id', 1)
            // ->chunkById(500, function ($pmks_datas) {
            //     foreach ($pmks_datas as $key => $pmks_data) {
            //         $this->counter++;
            //         if(empty($pmks_data->iddtks) && empty($pmks_data->nomor_nik)){
            //             DtksErrorsImport::create([
            //                 'dtks_import_id' => 1,
            //                 'row' => $this->counter,
            //                 'attribute' => '[iddtks, nomor_nik]',
            //                 'values' => '-',
            //                 'errors' => 'data kosong'
            //             ]);
            //         }
            //         else{
            //             $checkIddtks = PmksData::where('iddtks', $pmks_data->iddtks)->count();
            //             $checkNik = PmksData::where('nomor_nik', $pmks_data->nomor_nik)->count();

            //             if($checkIddtks > 0 && $checkNik > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[iddtks, nomor_nik]',
            //                     'values' => $pmks_data->iddtks,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else if($checkIddtks > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[iddtks]',
            //                     'values' => $pmks_data->iddtks,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else if($checkNik > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[nomor_nik]',
            //                     'values' => $pmks_data->nomor_nik,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else{
            //                 PmksData::create([
            //                     'dtks_import_id' => $this->id,
            //                     'iddtks' => $pmks_data->iddtks, 
            //                     'provinsi' => $pmks_data->provinsi, 
            //                     'kabupaten_kota' => $pmks_data->kabupaten_kota, 
            //                     'kecamatan' => $pmks_data->kecamatan, 
            //                     'desa_kelurahan' => $pmks_data->desa_keluarahan, 
            //                     'alamat' => $pmks_data->alamat, 
            //                     'dusun' => $pmks_data->dusun, 
            //                     'rt' => $pmks_data->rt, 
            //                     'rw' => $pmks_data->rw,
            //                     'nomor_kk' => $pmks_data->nomor_kk, 
            //                     'nomor_nik' => $pmks_data->nomor_nik, 
            //                     'nama' => $pmks_data->nama, 
            //                     'tanggal_lahir' => $pmks_data->tanggal_lahir, 
            //                     'tempat_lahir' => $pmks_data->tempat_lahir, 
            //                     'jenis_kelamin' => $pmks_data->jenis_kelamin, 
            //                     'nama_ibu_kandung' => $pmks_data->nama_ibu_kandung,
            //                     'hubungan_keluarga' => $pmks_data->hubungan_keluarga, 
            //                     'tahun_data' => $pmks_data->tahun_data, 
            //                     'jenis_pmks' => $pmks_data->jenis_pmks
            //                 ]);
            //             }

            //         }
                    // $pmks_data = ['pmks_data' => $pmks_data];
                    // // dd($pmks_data);
                    
                    // $validator = Validator::make($pmks_data, [
                    //     'pmks_data.iddtks' => 'required'
                    // ]);

                    // // Check validation failure
                    // if ($validator->fails()) {
                    //     dd($validator->messages()->get('*'));
                    //     // dd($validator->messages()->get('*')['pmks_data.iddtks'][0]);
                    //     // DtksErrorsImport::create([
                    //     //     'dtks_import_id' => 1,
                    //     //     'row' => 0,
                    //     //     'attribute' => 'tes',
                    //     //     'values' => 'tes',
                    //     //     'errors' => $validator->messages()->get('*')['pmks_data.iddtks'][0]
                    //     // ]);
                    // }
                
                    // // Check validation success
                    // if ($validator->passes()) {
                    //     dd('suksess');
                    // }

                

                    // DB::table('users')
                    //     ->where('id', $user->id)
                    //     ->update(['active' => true]);
            //     }
            // });
        
            //                         array:2 [▼
                        //   "pmks_data.iddtks" => array:1 [▼
                        //     0 => "The pmks data.iddtks field is required."
                        //   ]
                        //   "pmks_data.kabupaten_kota" => array:1 [▼
                        //     0 => "The pmks data.kabupaten kota field is required."
                        //   ]
                        // ]