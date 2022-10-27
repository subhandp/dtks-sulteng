<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChartPsks1
{
    protected $chart;
    public $chartData;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    } 
   
    public function build()
    {
       
        

        $kabupatenKotaList = DB::table('indonesia_cities')->select('id','name')->where('province_id', '72')->get();
        $jenisPsksList = DB::table('jenis_psks')->select('id','jenis')->get();

        foreach ($kabupatenKotaList as $k1 => $kabupatenKota) {
   
            $totalJenisPsksKabKota = [];

            $jenisPsksListName = [];

            foreach ($jenisPsksList as $k1 => $psks) {

                $jenisPsksListName[] = strtoupper($psks->jenis);
                $chartSelect = DB::table('charts_psks')->select('id','total')
                    ->where('indonesia_cities_id', $kabupatenKota->id)
                    ->where('jenis_psks_id', $psks->id)->first();
                
                if(!empty($chartSelect)){
                    $totalJenisPsksKabKota[] = (int)$chartSelect->total;
                }
                else{
                    $totalJenisPsksKabKota[] = 0;
                }
                
                
            }

            // $kabupatenKotaListChart[] = ['name' => $kabupatenKota->name, 'data' => $totalJenisPmksKabKota];
            $kabupatenKotaListChart[] = ['name' => $kabupatenKota->name, 'data' => $totalJenisPsksKabKota];

        }


        
        $barChart = $this->chart->barChart()

            ->addData($kabupatenKotaListChart[0]['name'], $kabupatenKotaListChart[0]['data'])
            ->addData($kabupatenKotaListChart[1]['name'], $kabupatenKotaListChart[1]['data'])
            ->addData($kabupatenKotaListChart[2]['name'], $kabupatenKotaListChart[2]['data'])
            ->addData($kabupatenKotaListChart[3]['name'], $kabupatenKotaListChart[3]['data'])
            ->addData($kabupatenKotaListChart[4]['name'], $kabupatenKotaListChart[4]['data'])
            ->addData($kabupatenKotaListChart[5]['name'], $kabupatenKotaListChart[5]['data'])
            ->addData($kabupatenKotaListChart[6]['name'], $kabupatenKotaListChart[6]['data'])
            ->addData($kabupatenKotaListChart[7]['name'], $kabupatenKotaListChart[7]['data'])
            ->addData($kabupatenKotaListChart[8]['name'], $kabupatenKotaListChart[8]['data'])
            ->addData($kabupatenKotaListChart[9]['name'], $kabupatenKotaListChart[9]['data'])
            ->addData($kabupatenKotaListChart[10]['name'], $kabupatenKotaListChart[10]['data'])
            ->addData($kabupatenKotaListChart[11]['name'], $kabupatenKotaListChart[11]['data'])
            ->addData($kabupatenKotaListChart[12]['name'], $kabupatenKotaListChart[12]['data'])
        
            ->setXAxis($jenisPsksListName);

        return $barChart;


    }
}
