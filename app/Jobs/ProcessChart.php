<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessChart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $importId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($importId)
    {
        $this->importId = $importId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $kabupatenKota = DB::table('indonesia_cities')->select('*')->where('province_id','72')->get();
            $totalPmks = [];
            foreach ($kabupatenKota as $kk) {
                $totalKabupatenKota = DB::table('pmks_data')
                                        ->select('kabupaten_kota')
                                        ->where('kabupaten_kota', $kk->name)
                                        ->count();
                
                DB::table('charts')
                ->updateOrInsert(
                    ['kabupaten_kota' => $kk->name],
                    ['total' => $totalKabupatenKota]
                );

            }


        } catch (\Illuminate\Database\QueryException $e) {
            // $e->getMessage();
            DB::table('dtks_errors_imports')
            ->create([
                'dtks_import_id' => $this->importId,
                'row' => 0,
                'attribute' => 'line: ',
                'values' => 'code: ',
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