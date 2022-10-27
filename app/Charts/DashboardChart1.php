<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class DashboardChart1
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

     
        $kabupatenKotaList = DB::table('indonesia_cities')->select('id','name')->where('province_id', '72')->get();
        $jenisPmksList = DB::table('jenis_pmks')->select('id','jenis')->get();
        $jenisPsksList = DB::table('jenis_psks')->select('id','jenis')->get();

        
        // foreach ($jenisPmksList as $key => $pmks) {

        // }
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
        
        
        foreach ($kabupatenKotaList as $k1 => $kabupatenKota) {
            $totalJenisPmksKabKota = [];
            $totalJenisPsksKabKota = [];

            $jenisPmksListName = [];
            $jenisPsksListName = [];

            foreach ($jenisPsksList as $k1 => $psks) {

                $jenisPsksListName[] = substr($psks->jenis,0,15);
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

            foreach ($jenisPmksList as $k2 => $pmks) {

                $jenisPmksListName[] = substr($pmks->jenis,0,15);
                $chartSelect = DB::table('charts')->select('id','total')
                    ->where('indonesia_cities_id', $kabupatenKota->id)
                    ->where('jenis_pmks_id', $pmks->id)->first();
                
                if(!empty($chartSelect)){
                    $totalJenisPmksKabKota[] = (int)$chartSelect->total;
                }
                else{
                    $totalJenisPmksKabKota[] = 0;
                }
                
                
            }

            $kabupatenKotaListChart[] = ['name' => $kabupatenKota->name, 'data' => $totalJenisPmksKabKota];
            // $kabupatenKotaListChart[] = ['name' => $kabupatenKota->name, 'data' => $totalJenisPsksKabKota];

        }
        
        // dd($kabupatenKotaListChart);
        
    //     return $this->chart->horizontalBarChart()
    // ->setTitle('Los Angeles vs Miami.')
    // ->setSubtitle('Wins during season 2021.')
    // ->setColors(['#FFC107', '#D32F2F'])
    // ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
    // ->addData('Boston', [7, 3, 8, 2, 6, 4])
    // ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
        
        // return $this->chart->pieChart()
        //     ->setTitle('Penduduk kategori PMKS tiap Kabupaten')
        //     ->addData($totalDtks)
        //     ->setLabels($kabupatenKotaChart)
        //     ->setDataLabels();

        // return $this->chart->horizontalBarChart()
        // ->setTitle('Penduduk kategori PMKS tiap Kabupaten')
        // ->addData('PMKS',$totalDtks)
        // ->setXAxis($kabupatenKotaChart);

        return $this->chart->barChart()
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

    ->setXAxis($jenisPmksListName);


    

    }
}
