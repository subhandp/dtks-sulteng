<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChart2
{
    protected $chart;
    public $chartData;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    } 
   
    public function build()
    {
       
        $charts = DB::table('charts')->select('*')->where('jenis_pmks_id', null)->get();
       
        $kabupatenKotaChart = [];
        $totalDtks = [];
        foreach ($charts as $key => $crt) {
            if($crt->total > 0){
                $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id', $crt->indonesia_cities_id)->first();
                
                $kabupatenKotaChart[] = $kabupatenKota->name;
                $totalDtks[] = (int)$crt->total;
            }

        }

     
        
        return $this->chart->pieChart()
            ->setTitle('Penduduk kategori PMKS tiap Kabupaten')
            ->addData($totalDtks)
            ->setLabels($kabupatenKotaChart)
            ->setDataLabels();

    

    }
}
