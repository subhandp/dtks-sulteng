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
    public function __construct($id)
    {
        $this->dtksimportId = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $jumlahBaris =  DB::table('pmks_data_temps')->where('dtks_import_id', $this->dtksimportId)->count();
                
            DtksImport::find($this->dtksimportId)
                ->update(['keterangan' => 'Validasi Data...', 'jumlah_baris' => $jumlahBaris]);
                
                $this->counter = 0;

                $this->metodeImport = 'firstOrCreate';
                try {
                    DB::table('pmks_data_temps')->where('dtks_import_id', $this->dtksimportId)
                    ->orderBy('id', 'asc')
                    ->chunk(1000, function ($pmks_datas) {
                        
                        
                        $pmksData = [];
                        $dtksErrorImport = [];

                        foreach ($pmks_datas as $key => $pmks_data) {
                            $this->counter++;
                            $pmksData[] = [
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
                                'pekerjaan' => $pmks_data->pekerjaan,
                                'nama_ibu_kandung' => $pmks_data->nama_ibu_kandung,
                                'hubungan_keluarga' => $pmks_data->hubungan_keluarga, 
                                'keterangan_padan' => $pmks_data->keterangan_padan,
                                'bansos_bpnt' => $pmks_data->bansos_bpnt,
                                'bansos_pkh' => $pmks_data->bansos_pkh,
                                'bansos_bpnt_ppkm' => $pmks_data->bansos_bpnt_ppkm,
                                'pbi_jkn' => $pmks_data->pbi_jkn,
                                'tahun_data' => $pmks_data->tahun_data, 
                                'jenis_pmks' => $pmks_data->jenis_pmks,
                                
                            ];

                        }

                        DB::table('pmks_data')->insertOrIgnore($pmksData);
                        DtksErrorsImport::insert($dtksErrorImport);

                        DtksImport::find($this->dtksimportId)
                        ->update(['baris_selesai' => $this->counter]);
                        
                    });

                    DtksImport::find($this->dtksimportId)
                    ->update(['status_import' => 'SUKSES POSTING']);

                    PmksDataTemp::where('dtks_import_id',$this->dtksimportId)->delete();

                    if(!PmksDataTemp::select('*')->exists()){
                        PmksDataTemp::truncate();
                    }

                    

            } catch (\Exception  $e) {
                DtksErrorsImport::create([
                    'dtks_import_id' => $this->dtksimportId,
                    'row' => 0,
                    'attribute' => 'posting import line: '.$e->getLine(),
                    'values' => 'posting import code: '.$e->getCode(),
                    'errors' => substr($e->getMessage(), 0, 200)
                ]);

                DtksImport::find($this->dtksimportId)
                ->update(['status_import' => 'GAGAL POSTNG', 'keterangan' => 'Gagal Posting error Exception.']);
                return false;
            }

            DtksImport::find($this->dtksimportId)
                    ->update(['status_import' => 'SUKSES POSTING', 'keterangan' => 'SUKSES POSTING']);
                
        }
}
