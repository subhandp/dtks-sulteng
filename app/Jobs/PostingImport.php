<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\DtksImport;
use App\Models\PmksData;
use App\Models\PmksDataTemp;
use Illuminate\Support\Facades\Auth;
use App\Imports\PmksDataImport;
use App\Models\DtksErrorsImport;
use Illuminate\Support\Facades\DB;


class PostingImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $counter;
    public $metodeImport;
    public $dtksimportId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $jumlahBaris =  DB::table('pmks_data_temp')->where('dtks_import_id', $this->dtksimportId)->count();
                DtksImport::find($this->dtksimportId)
                ->update(['status_import' => 'proses validasi data', 'jumlah_baris' => $jumlahBaris]);
                
                $this->counter = 0;
                $this->metodeImport = 'firstOrCreate';
                try {
                    DB::table('pmks_data_temp')->where('dtks_import_id', $this->dtksimportId)
                    ->chunkById(500, function ($pmks_datas) {
                        
                        DtksImport::find($this->dtksimportId)
                        ->update(['baris_selesai' => $this->counter]);
                        
                        $pmksData = [];

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
                                // else{

                                    
                                    
                                //     $pmksData[] = [
                                //         'dtks_import_id' => $this->dtksimportId,
                                //         'iddtks' => $pmks_data->iddtks, 
                                //         'provinsi' => $pmks_data->provinsi, 
                                //         'kabupaten_kota' => $pmks_data->kabupaten_kota, 
                                //         'kecamatan' => $pmks_data->kecamatan, 
                                //         'desa_kelurahan' => $pmks_data->desa_kelurahan, 
                                //         'alamat' => $pmks_data->alamat, 
                                //         'dusun' => $pmks_data->dusun, 
                                //         'rt' => $pmks_data->rt, 
                                //         'rw' => $pmks_data->rw,
                                //         'nomor_kk' => $pmks_data->nomor_kk, 
                                //         'nomor_nik' => $pmks_data->nomor_nik, 
                                //         'nama' => $pmks_data->nama, 
                                //         'tanggal_lahir' => $pmks_data->tanggal_lahir, 
                                //         'tempat_lahir' => $pmks_data->tempat_lahir, 
                                //         'jenis_kelamin' => $pmks_data->jenis_kelamin, 
                                //         'nama_ibu_kandung' => $pmks_data->nama_ibu_kandung,
                                //         'hubungan_keluarga' => $pmks_data->hubungan_keluarga, 
                                //         'tahun_data' => $pmks_data->tahun_data, 
                                //         'jenis_pmks' => $pmks_data->jenis_pmks
                                //     ];

                                    
                                // }

                            }
                        }

                        // PmksData::insert($pmksData);
                    });

                    // PmksDataTemp::truncate();

                    DtksImport::find($this->dtksimportId)
                    ->update(['status_import' => 'sukses import', 'keterangan' => 'imported']);

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
