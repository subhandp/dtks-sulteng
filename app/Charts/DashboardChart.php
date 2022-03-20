<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    } 
   
    public function build()
    {
       
        $charts = DB::table('charts')->select('*')->get();

        $kabupaten_kota = [];
        $totalDtks = [];
        foreach ($charts as $key => $chart) {
            if($chart->total >0){
                $kabupaten_kota[] = $chart->kabupaten_kota;
                $totalDtks[] = $chart->total;
            }

        }

        return $this->chart->pieChart()
            ->setTitle('Data Keseluruhan PMKS tiap Kabupaten')
            ->addData($totalDtks)
            ->setLabels($kabupaten_kota)
            ->setDataLabels();
    }
}

   //     return $this->chart->barChart()
    //     ->setTitle('San Francisco vs Boston.')
    //     ->setSubtitle('Wins during season 2021.')
    //     ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
    //     ->addData('banggai', [7, 3, 8, 2, 6, 4])
    //     ->addData('poso', [7, 3, 8, 2, 6, 4])
    //     ->addData('luwuk', [7, 3, 8, 2, 6, 4])
    //     ->addData('toli-toli', [7, 3, 8, 2, 6, 4])
    //     ->addData('a', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])
    //     ->addData('Boston', [7, 3, 8, 2, 6, 4])


    //     ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);