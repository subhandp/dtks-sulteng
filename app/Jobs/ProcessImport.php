<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  App\Models\DtksImport;
use  App\Models\PmksData;
use  App\Models\PmksDataTemp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;  
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PmksDataImport;
use App\Models\DtksErrorsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    public $counter;
    public $metodeImport;
    public $dtksimportId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
            foreach($this->request as $uploads){
                $upload = json_decode($uploads, true);
             
                $finalpath = $upload['disk'].'/'.explode('/',$upload['filepath'])[1];
    
                $dtksimport = new DtksImport();
                $dtksimport->no_tiket = 'default';
                $dtksimport->filename = $upload['filename'];
                $dtksimport->filepath = $finalpath;
                $dtksimport->jumlah_baris = '-';
                $dtksimport->baris_selesai = '-';
                $dtksimport->status_import = 'FILE TERSIMPAN';
                $dtksimport->keterangan = '-';
                $dtksimport->save();
                $this->dtksimportId = $dtksimport->id;
                File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
            }
          

            $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
            
            DtksImport::find($this->dtksimportId)
                        ->update(['status_import' => 'PROSES IMPORT']);
            try {
                $pdo = DB::connection()->getPdo();
                $path = str_replace('\\', '/', $path);
                $pdo->exec("LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE pmks_data_temp FIELDS TERMINATED BY '|' enclosed by '\"' lines terminated by '\\n' IGNORE 1 LINES (iddtks, provinsi, kabupaten_kota, kecamatan, desa_kelurahan, alamat, dusun, rt, rw,nomor_kk, nomor_nik, nama, tanggal_lahir, tempat_lahir, jenis_kelamin, nama_ibu_kandung,hubungan_keluarga, @tahun_data, @jenis_pmks, @created_at, @updated_at,@dtks_import_id) SET dtks_import_id = '".$this->dtksimportId."', tahun_data = 2022, jenis_pmks = 'default', created_at = NOW(), updated_at = NOW()");
            } catch (\Exception  $e) {
                DtksErrorsImport::create([
                    'dtks_import_id' => $this->dtksimportId,
                    'row' => 0,
                    'attribute' => 'line: '.$e->getLine(),
                    'values' => 'code: '.$e->getCode(),
                    'errors' => substr($e->getMessage(), 0, 200)
                ]);
                DtksImport::find($this->dtksimportId)
                        ->update(['status_import' => 'GAGAL IMPORT']);
                return false;
            }

            DtksImport::find($this->dtksimportId)
                        ->update(['status_import' => 'SUKSES IMPORT']);
    }
}



            // DB::table('pmks_data_temp')->where('dtks_import_id', 1)
            // ->chunkById(500, function ($pmks_datas) {
            //     foreach ($pmks_datas as $pmks_data) {
                    
            //         $pmks_data = ['pmks_data' => json_decode(json_encode($pmks_data))];
            //         // dd($pmks_data);
                    
            //         $validator = Validator::make($pmks_data, [
            //             'pmks_data.iddtks' => 'required',
            //             'pmks_data.kabupaten_kota' => 'required',
            //             'pmks_data.nama_ibu_kandung' => 'required',
            //         ]);

            //         // Check validation failure
            //         if ($validator->fails()) {
            //             DtksErrorsImport::create([
            //                 'dtks_import_id' => 1,
            //                 'row' => 0,
            //                 'attribute' => 'tes',
            //                 'values' => 'tes',
            //                 'errors' => $validator->messages()->get('*')['pmks_data.iddtks'][0]
            //             ]);

            //             // dd($validator->messages()->get('*'));
            //             // dd($validator->messages()->get('*')['pmks_data.iddtks'][0]);
            //         }
                
            //         // Check validation success
            //         // if ($validator->passes()) {
            //         //     dd('suksess');
            //         // }

            //     }
            // });


            // $pmks_data = DB::table('pmks_data_temp')
            // ->where('dtks_import_id', '=',  $this->id)
            // ->get();

            // DB::table('pmks_data_temp')->where('dtks_import_id', $this->id)
            // ->chunkById(100, function ($pmks_datas) {
            //     foreach ($pmks_datas as $pmks_data) {
            //         Validator::make($pmks_data, [
            //             'user' => 'array:username,locale',
            //         ]);
            //         // DB::table('users')
            //         //     ->where('id', $user->id)
            //         //     ->update(['active' => true]);
            //     }
            // });
            
            
            // $import = new PmksDataImport($dtksimport->id, 2022, 'jenis_pmks');
            
            // $import->import($path);