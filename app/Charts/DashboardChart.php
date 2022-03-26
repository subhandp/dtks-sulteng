<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChart
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

     
        // $kabupatenKotaList = DB::table('indonesia_cities')->select('id','name')->where('province_id', '72')->get();
        // $jenisPmksList = DB::table('jenis_pmks')->select('id','jenis')->get();
        // $jenisPmksListGroupBy = DB::table('charts')
        //     ->select('jenis_pmks_id')
        //     ->groupBy('jenis_pmks_id')
        //     ->get();

        // $pmksLists = [];
        // foreach ($jenisPmksListGroupBy as $key => $jpl) {
        //     if($jpl->jenis_pmks_id){
        //         $pmksJenisFromId = DB::table('jenis_pmks')->select('jenis')->where('id', $jpl->jenis_pmks_id)->first();
        //         $pmksLists[] = $pmksJenisFromId->jenis;
        //     }
            
        // }

        // foreach ($kabupatenKotaList as $k1 => $kabupatenKota) {
        //     $totalJenisPmksKabKota = [];
        //     foreach ($jenisPmksList as $k2 => $pmks) {
        //         $chartSelect = DB::table('charts')->select('id','total')
        //             ->where('indonesia_cities_id', $kabupatenKota->id)
        //             ->where('jenis_pmks_id', $pmks->id)->first();
                 
        //         if(!empty($chartSelect)){
        //             $totalJenisPmksKabKota[] = (int)$chartSelect->total;
        //         }
                
        //     }

        //     $kabupatenKotaListChart[] = ['name' => $kabupatenKota->name, 'data' => $totalJenisPmksKabKota];
        // }
        
        // dd($kabupatenKotaListChart);

        return $this->chart->pieChart()
            ->setTitle('Penduduk kategori PMKS tiap Kabupaten')
            ->addData($totalDtks)
            ->setLabels($kabupatenKotaChart)
            ->setDataLabels();
    }
}
