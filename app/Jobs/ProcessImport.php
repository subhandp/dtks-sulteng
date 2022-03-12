<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  App\Models\DtksImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;  
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PmksDataImport;
use App\Models\DtksErrorsImport;
use Illuminate\Support\Facades\DB;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
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
                $dtksimport->status_import = 'file-stored';
                $dtksimport->keterangan = '-';
                $dtksimport->save();
    
                File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
            }
          

            $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
            
            try {
                $pdo = DB::connection()->getPdo();
                $path = str_replace('\\', '/', $path);
                $pdo->exec("LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE pmks_data FIELDS TERMINATED BY '|' enclosed by '\"' lines terminated by '\\n' IGNORE 1 LINES (iddtks, provinsi, kabupaten_kota, kecamatan, desa_kelurahan, alamat, dusun, rt, rw,nomor_kk, nomor_nik, nama, tanggal_lahir, tempat_lahir, jenis_kelamin, nama_ibu_kandung,hubungan_keluarga, @tahun_data, @jenis_pmks, @created_at, @updated_at,@dtks_import_id) SET dtks_import_id = 1, tahun_data = 2022, jenis_pmks = default, created_at = NOW(), updated_at = NOW()");
            } catch (\Exception  $e) {
                DtksErrorsImport::create([
                    'dtks_import_id' => $this->id,
                    'row' => 0,
                    'attribute' => 'line: '.$e->getLine(),
                    'values' => 'code: '.$e->getCode(),
                    'errors' => substr($e->getMessage(), 0, 100)
                ]);
            }
            
            // $import = new PmksDataImport($dtksimport->id, 2022, 'jenis_pmks');
            
            // $import->import($path);
            
    }
}
