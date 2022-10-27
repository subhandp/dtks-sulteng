<?php

namespace App\Http\Controllers;
// use App\SuratKeluar;
// use App\SuratMasuk;
// use App\Klasifikasi;
// use App\User;
use Illuminate\Support\Facades\Auth;
use App\Charts\DashboardChartPsks1;
use App\Charts\DashboardChartPsks2;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// use App\Models\Charts;
use App\Models\ChartsPsks;

use Yajra\DataTables\Facades\DataTables;

class DashboardControllerPsks extends Controller
{
   
    public function index(DashboardChartPsks1 $myChart1,DashboardChartPsks2 $myChart2)
    {
        $myChart1 = $myChart1->build();
        $myChart2 = $myChart2->build();

        $jenisPsksSelect = session('psks');

        $charts = DB::table('charts_psks')->select('*')->where('jenis_psks_id', null)->orderBy('total','desc')->get();
       
        $chartData = [];
        $chartDataForTooltip = [];
        $chartDatatotalDtks = [];
        $chartMapDataId = [];
        $grandTotal = 0;
        $percentages = [];
        $chartMapDatatotalDtks = [];

        foreach ($charts as $key => $chart) {
            if($chart->total > 0){
                
            }
            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id', $chart->indonesia_cities_id)->first();
            $chartData[] = $kabupatenKota->name;
            $chartDatatotalDtks[] = (int)$chart->total;
            $chartMapDatatotalDtks[$kabupatenKota->name] = $kabupatenKota->id;
            $grandTotal += (int)$chart->total;
            $chartDataForTooltip[$kabupatenKota->name] = number_format($chart->total,0,',','.');

        }


        $charts = DB::table('charts_psks')->select('*')->where('jenis_psks_id', null)->orderBy('total','desc')->get();
       
        $chartData = [];
        $chartDataForTooltip = [];
        $chartDatatotalDtks = [];
        $chartMapDataId = [];
        $grandTotal = 0;
        $percentages = [];
        $chartMapDatatotalDtks = [];
        
        foreach ($charts as $key => $chart) {
            if($chart->total > 0){
                
            }
            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id', $chart->indonesia_cities_id)->first();
            $chartData[] = $kabupatenKota->name;
            $chartDatatotalDtks[] = (int)$chart->total;
            $chartMapDatatotalDtks[$kabupatenKota->name] = $kabupatenKota->id;
            $grandTotal += (int)$chart->total;
            $chartDataForTooltip[$kabupatenKota->name] = number_format($chart->total,0,',','.');

        }


        $kabupatenKota = DB::table('indonesia_cities')
        ->select('id', 'name')
        ->Where('province_id', '72')
        ->get();

        $kabupatenKotaTotalPerJenisPsks = [];
        
        foreach ($kabupatenKota as $key => $kk) {
            $totalPerJenisPsks = DB::table('charts_psks')
            ->where('indonesia_cities_id',$kk->id)
            ->where('jenis_psks_id','<>',null)
            ->get();
            $kabupatenKotaTotalPerJenisPsks[] = [$kk,$totalPerJenisPsks];
        }

        // $chartsKabJenis = ChartsPsks::with('jenis_psks')->with('kabupaten_kota')->where('jenis_psks_id', '<>', null)->orderBy('total','desc')->get();
        $jenisPsks = DB::table('jenis_psks')->get();
        
        // dd($chartMapDatatotalDtks);

        // dd($chartDatatotalDtks);

        $dataGroupColour = [[],[],[]];
        foreach($chartDatatotalDtks as $index => $item){
            if($grandTotal <= 0 ){
                $percentages = 0;
            }
            else{
                $percentages = round($item / ($grandTotal /100),0,PHP_ROUND_HALF_UP);
            }
            
            
            if($percentages > 90){
                $dataGroupColour[3][] = $chartData[$index];
            }
            else if($percentages > 70){
                $dataGroupColour[2][] = $chartData[$index];
            }
            else if($percentages > 50){
                $dataGroupColour[1][] = $chartData[$index];
            }
            else if($percentages != 0){
                $dataGroupColour[0][] = $chartData[$index]; 
            }
 
        }
        
        $psksDataGroup = [];

        $class_menu_data_dashboard = "menu-open";

        $jenisPsksSelect = session('psks');
        if(!empty($jenisPsksSelect)){
            $jenisPsksSelect = DB::table('jenis_psks')->select('id','jenis')->where('id',$jenisPsksSelect)->first();
        }
        
        return view('psks.dashboardPsks', compact('jenisPsks','kabupatenKotaTotalPerJenisPsks', 'chartDataForTooltip','class_menu_data_dashboard', 'myChart2','myChart1', 'psksDataGroup', 'jenisPsksSelect', 'chartData', 'chartDatatotalDtks','dataGroupColour','chartMapDatatotalDtks'));



    }


    public function set_session_psks(Request $request){
        session(['psks' => $request->input('jenisPsksId')]);
        return response()->json([session('psks')]);
    }

    public function get_jenis_psks(Request $request){
        $jenisPsks = DB::table('jenis_psks')->select('id','jenis')->get();
        return response()->json($jenisPsks);
    }

    // public function get_pmks_kab(Request $request){
    //         $data = Charts::with('jenis_pmks')
    //         ->where('indonesia_cities_id', $request->get('q'))
    //         ->where('jenis_pmks_id', '<>', null)
    //         ->orderBy('total','desc');
    //         // dd($data);
    //         return DataTables::of($data)
    //                     ->addIndexColumn()
    //                                 ->addColumn('jenis',function(Charts $charts){
    //                                     return 'subhan';
    //                                 })
    //                                 ->make(true);

    // }

    public function get_psks_kab(Request $request){
            $psksKabupaten = ChartsPsks::with('jenis_psks')
                    ->where('indonesia_cities_id', $request->get('q'))
                    ->where('jenis_psks_id', '<>', null)
                    ->where('total', '>', 0)
                    ->orderBy('total','desc')->get();
            $psksKabupatenList = [];
            $no = 1;
            foreach ($psksKabupaten as $key => $psks) {
                $psksKabupatenList[] = ['no' => $no,'jenis_psks' => $psks->jenis_psks->detail.' ('.strtoupper($psks->jenis_psks->jenis).')', 'total' => number_format($psks->total,0,',','.')];
                $no++;
            }
            return response()->json($psksKabupatenList);
    }



}
