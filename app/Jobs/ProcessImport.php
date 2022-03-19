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
use Illuminate\Support\Str;
use Hashids\Hashids;

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
            try {
            
                foreach($this->request['upload'] as $uploads){
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
                
                
                // $hashids = new Hashids();
                // $hashids = new Hashids('', 5, '123456789abcdefghijklmnopqrstuvwxyz'); 
                // $noTiket = $hashids->encode($this->dtksimportId);
                // $noTiket = str_pad($this->dtksimportId,5,"0",STR_PAD_LEFT);

                $digits = 4;
                $ra =  rand(pow(10, $digits-1), pow(10, $digits)-1);
                $noTiket= $ra.$dtksimport->id;

                

                DtksImport::find($this->dtksimportId)
                            ->update(['status_import' => 'Prosess Import...', 'no_tiket' => $noTiket]);
                
                    
                    //ambil line pertama dari csv untuk header
                    $file = fopen($path,"r");
                        $headerCsv = fgetcsv($file,0,'|');
                    fclose($file);  

                    
                    $header = ["ID DTKS","PROVINSI","KABUPATEN/KOTA","KECAMATAN","DESA/KELUARAHAN","ALAMAT","DUSUN","RT","RW","NOMOR KK","NOMOR NIK","NAMA","TANGGAL LAHIR","TEMPAT LAHIR","JENIS KELAMIN","NAMA IBU KANDUNG","HUBUNGAN KELUARGA"];
                    // Str::slug('Laravel 5 Framework', '-');
                    $validHeader = true;
                    foreach ($header as $key => $h) {
                        $h = Str::slug($h);
                        $hCompare = Str::slug($headerCsv[$key]);
                        if( $h != $hCompare ){
                            $validHeader = false;
                            break;
                        }
                    }

                    // bandingkan apa header csv yang di upload sesuai jika tidak catat error
                    if(!$validHeader){
                        DtksErrorsImport::create([
                            'dtks_import_id' => $this->dtksimportId,
                            'row' => 0,
                            'attribute' => 'Format CSV',
                            'values' => 'Format CSV Tidak Valid.',
                            'errors' => "GAGAL IMPORT"
                        ]);

                        DtksImport::find($this->dtksimportId)
                            ->update(['status_import' => 'GAGAL IMPORT']);

                        return False;
                    }
                    else{
                        $jenis_pmks = $this->request['jenis_pmks'];
                        $tahun_data = $this->request['tahun_data'];
                        $pdo = DB::connection()->getPdo();
                        $path = str_replace('\\', '/', $path);
                        $pdo->exec("LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE pmks_data_temps FIELDS TERMINATED BY '|' enclosed by '\"' lines terminated by '\\n' IGNORE 1 LINES (iddtks, provinsi, kabupaten_kota, kecamatan, desa_kelurahan, alamat, dusun, rt, rw,nomor_kk, nomor_nik, nama, tanggal_lahir, tempat_lahir, jenis_kelamin, nama_ibu_kandung,hubungan_keluarga, @tahun_data, @jenis_pmks, @created_at, @updated_at,@dtks_import_id) SET dtks_import_id = '".$this->dtksimportId."', tahun_data = '".$tahun_data."', jenis_pmks = '".$jenis_pmks."', created_at = NOW(), updated_at = NOW()");
                    }
                
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



            // DB::table('pmks_data_temps')->where('dtks_import_id', 1)
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


            // $pmks_data = DB::table('pmks_data_temps')
            // ->where('dtks_import_id', '=',  $this->id)
            // ->get();

            // DB::table('pmks_data_temps')->where('dtks_import_id', $this->id)
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