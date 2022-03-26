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

class ProcessDataChart implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $kabupatenKota;
    public $jenisPmks;
    public $dtksImportId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kabupatenKota, $jenisPmks)
    {
        $this->kabupatenKota = $kabupatenKota;
        $this->jenisPmks = $jenisPmks;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
            $totalPmksJenisKabupatenKota = DB::table('pmks_data')
                                        ->select('id')
                                        ->where('kabupaten_kota', $this->kabupatenKota->name)
                                        ->where('jenis_pmks', $this->jenisPmks->jenis)
                                        ->count();
                
            DB::table('charts')
                ->updateOrInsert(
                    ['indonesia_cities_id' => $this->kabupatenKota->id, 'jenis_pmks_id' => $this->jenisPmks->id],
                    ['total' => $totalPmksJenisKabupatenKota]
                );

            $totalPmksKabupatenKota = DB::table('pmks_data')
                                        ->select('id')
                                        ->where('kabupaten_kota', $this->kabupatenKota->name)
                                        ->count();

            DB::table('charts')
                    ->updateOrInsert(
                    ['indonesia_cities_id' => $this->kabupatenKota->id, 'jenis_pmks_id' => null],
                    ['total' => $totalPmksKabupatenKota]
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
                'attribute' => 'line: '.$e->getLine(),
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