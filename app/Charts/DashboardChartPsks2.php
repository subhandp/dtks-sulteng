<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChartPsks2
{
    protected $chart;
    public $chartData;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    } 
   
    public function build()
    {
       
        $charts = DB::table('charts_psks')->select('*')->where('jenis_psks_id', null)->get();
       
        $kabupatenKotaChart = [];
        $totalDtks = [];
        foreach ($charts as $key => $crt) {
            if($crt->total > 0){
                $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id', $crt->indonesia_cities_id)->first();
                
                $kabupatenKotaChart[] = $kabupatenKota->name;
                $totalDtks[] = (int)$crt->total;
            }

        }


        $pieChart= $this->chart->pieChart()
            ->setTitle('Penduduk kategori PSKS tiap Kabupaten')
            ->addData($totalDtks)
            ->setLabels($kabupatenKotaChart)
            ->setDataLabels();
        
        return $pieChart;


    }
}
