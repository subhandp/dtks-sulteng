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
// use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PmksController extends Controller
{
    
    public $counter;
    public $metodeImport;
    public function index()
    {
        $data_pmks_import = DB::table('dtks_imports')
        ->orderBy('created_at', 'desc')
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

        return view('pmks.index', compact('data_pmks_import_status','data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

        // return view('suratmasuk.index',['data_suratmasuk'=> $data_suratmasuk]);
    }

    public function daftarpmks()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $data_daftar_pmks = [];
        $class_menu_pmks = "menu-open";
        $class_menu_daftar_pmks = "sub-menu-open";
        $class_menu_pmks_import = "";

        return view('pmks.daftar', compact('data_daftar_pmks','class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));

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
        ProcessImport::dispatch($request->input('upload'));
        return redirect('/pmks/import-data')->with("sukses", 1);
       
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

        // $pmks_datas = DB::table('pmks_data_temp')
        //     ->where('dtks_import_id', '=',  1)
        //     ->get();
        // dd($pmks_data);
            // $this->counter = 0;

            // $this->metodeImport = 'firstOrCreate';
            // DB::table('pmks_data_temp')->where('dtks_import_id', 1)
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