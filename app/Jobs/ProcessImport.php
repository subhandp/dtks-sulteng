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
                $dtksimport->status_import = 'file-stored';
                $dtksimport->keterangan = '-';
                $dtksimport->save();
                $this->dtksimportId = $dtksimport->id;
                File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
            }
          

            $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
            
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
                    'errors' => substr($e->getMessage(), 0, 100)
                ]);
                return false;
            }

            $jumlahBaris =  DB::table('pmks_data_temp')->where('dtks_import_id', $this->dtksimportId)->count();
            DtksImport::find($this->dtksimportId)
            ->update(['status_import' => 'proses validasi data', 'jumlah_baris' => $jumlahBaris]);
            
            $this->counter = 0;
            $this->metodeImport = 'firstOrCreate';
            try {
                DB::table('pmks_data_temp')->where('dtks_import_id', $this->dtksimportId)
                ->chunkById(500, function ($pmks_datas) {

                    
                    DtksImport::find($this->dtksimportId)
                    ->update(['keterangan' => $this->counter]);

                    foreach ($pmks_datas as $key => $pmks_data) {
                        $this->counter++;
                        if(empty($pmks_data->iddtks) && empty($pmks_data->nomor_nik)){
                            DtksErrorsImport::create([
                                'dtks_import_id' => $this->dtksimportId,
                                'row' => $this->counter,
                                'attribute' => '[iddtks, nomor_nik]',
                                'values' => '-',
                                'errors' => 'data kosong'
                            ]);
                        }
                        else{
                            $checkIddtks = PmksData::where('iddtks', $pmks_data->iddtks)->count();
                            $checkNik = PmksData::where('nomor_nik', $pmks_data->nomor_nik)->count();

                            if($checkIddtks > 0 && $checkNik > 0){
                                DtksErrorsImport::create([
                                    'dtks_import_id' => $this->dtksimportId,
                                    'row' => $this->counter,
                                    'attribute' => '[iddtks, nomor_nik]',
                                    'values' => $pmks_data->iddtks,
                                    'errors' => 'data sudah ada'
                                ]);
                            }
                            else if($checkIddtks > 0){
                                DtksErrorsImport::create([
                                    'dtks_import_id' => $this->dtksimportId,
                                    'row' => $this->counter,
                                    'attribute' => '[iddtks]',
                                    'values' => $pmks_data->iddtks,
                                    'errors' => 'data sudah ada'
                                ]);
                            }
                            // else if($checkNik > 0){
                            //     DtksErrorsImport::create([
                            //         'dtks_import_id' => $this->dtksimportId,
                            //         'row' => $this->counter,
                            //         'attribute' => '[nomor_nik]',
                            //         'values' => $pmks_data->nomor_nik,
                            //         'errors' => 'data sudah ada'
                            //     ]);
                            // }
                            else{
                                PmksData::create([
                                    'dtks_import_id' => $this->dtksimportId,
                                    'iddtks' => $pmks_data->iddtks, 
                                    'provinsi' => $pmks_data->provinsi, 
                                    'kabupaten_kota' => $pmks_data->kabupaten_kota, 
                                    'kecamatan' => $pmks_data->kecamatan, 
                                    'desa_kelurahan' => $pmks_data->desa_kelurahan, 
                                    'alamat' => $pmks_data->alamat, 
                                    'dusun' => $pmks_data->dusun, 
                                    'rt' => $pmks_data->rt, 
                                    'rw' => $pmks_data->rw,
                                    'nomor_kk' => $pmks_data->nomor_kk, 
                                    'nomor_nik' => $pmks_data->nomor_nik, 
                                    'nama' => $pmks_data->nama, 
                                    'tanggal_lahir' => $pmks_data->tanggal_lahir, 
                                    'tempat_lahir' => $pmks_data->tempat_lahir, 
                                    'jenis_kelamin' => $pmks_data->jenis_kelamin, 
                                    'nama_ibu_kandung' => $pmks_data->nama_ibu_kandung,
                                    'hubungan_keluarga' => $pmks_data->hubungan_keluarga, 
                                    'tahun_data' => $pmks_data->tahun_data, 
                                    'jenis_pmks' => $pmks_data->jenis_pmks
                                ]);

                                
                            }

                        }
                    }
                });

                PmksDataTemp::truncate();

                DtksImport::find($this->dtksimportId)
                ->update(['status_import' => 'sukses import']);

        } catch (\Exception  $e) {
            DtksErrorsImport::create([
                'dtks_import_id' => $this->dtksimportId,
                'row' => 0,
                'attribute' => 'line: '.$e->getLine(),
                'values' => 'code: '.$e->getCode(),
                'errors' => substr($e->getMessage(), 0, 200)
            ]);
            return false;
        }
            
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