<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Batchable;
use App\Models\DtksErrorsImport;


class ProcessDataChartPsks implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $kabupatenKota;
    public $jenisPsks;
    public $dtksImportId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kabupatenKota, $jenisPsks)
    {
        $this->kabupatenKota = $kabupatenKota;
        $this->jenisPsks = $jenisPsks;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
            $tablePsks = [
                'psm' => 'psks_psms', 
                'tksk' => 'psks_tksks', 
                'lk3' => 'psks_lk3s',
                'lks' => 'psks_lks',
                'kt' => 'psks_kts',
                'wkskbm' => 'psks_wksbs',
                'fcsr' => 'psks_fcsrs',
                'fcu' => 'psks_fcus'
            ];

            $totalPsksJenisKabupatenKota = DB::table($tablePsks[$this->jenisPsks->jenis])
                                        ->select('id')
                                        ->where('kabupaten_kota', $this->kabupatenKota->name)
                                        ->count();
                
            DB::table('charts_psks')
                ->updateOrInsert(
                    ['indonesia_cities_id' => $this->kabupatenKota->id, 'jenis_psks_id' => $this->jenisPsks->id],
                    ['total' => $totalPsksJenisKabupatenKota]
                );

            $totalPsksKabupatenKota = 0;

            foreach ($tablePsks as $key => $psks) {
                $total = DB::table($psks)
                        ->select('id')
                        ->where('kabupaten_kota', $this->kabupatenKota->name)
                        ->count();
                $totalPsksKabupatenKota += $total;
            }

            DB::table('charts_psks')
                    ->updateOrInsert(
                    ['indonesia_cities_id' => $this->kabupatenKota->id, 'jenis_psks_id' => null],
                    ['total' => $totalPsksKabupatenKota]
                );

                // DB::table('charts')
                // ->insert(
                //     [
                //         'indonesia_cities_id' => $this->kabupatenKota->id, 
                //         'jenis_pmks_id' => $this->jenisPmks->id,
                //         'total' => $totalKabupatenKota
                //     ]
                // );


        } catch (\Illuminate\Database\QueryException $e) {
            // $e->getMessage();
            DtksErrorsImport::create([
                'dtks_import_id' => 1,
                'row' => 0,
                'attribute' => 'line: '.$e->getLine().' [ProcessDataChartPsks]',
                'values' => 'code: '.$e->getCode(),
                'errors' => substr($e->getMessage(), 0, 200)
            ]);
        }
    }
}


                // $totalJenisPmks = [];
            //    foreach ($jenisPmks as $pmks) {
            //         $total =  DB::table('pmks_data')
            //             ->select('kabupaten_kota, jenis_pmks')
            //             ->where('kabupaten_kota', $kk->name)
            //             ->where('jenis_pmks', $pmks->jenis)
            //             ->count();
                    
            //             $totalJenisPmks[] = ['kabupaten_kota' => $kk->name,'jenis_pmks' => $pmks->jenis, 'total' => $total];
            //         DB::table('charts')
            //             ->updateOrInsert(
            //                 ['kabupaten_kota' => $kk->name,'jenis_pmks' => $pmks->jenis],
            //                 ['total' => $total]
            //             );
            //    }