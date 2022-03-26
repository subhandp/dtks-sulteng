<?php

namespace App\Http\Controllers;
// use App\SuratKeluar;
// use App\SuratMasuk;
// use App\Klasifikasi;
// use App\User;
use Illuminate\Support\Facades\Auth;
use App\Charts\DashboardChart;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Charts;
use Yajra\DataTables\Facades\DataTables;


class DashboardController extends Controller
{
   
    public function index(DashboardChart $myChart)
    {
        $myChart = $myChart->build();

        $jenisPmksSelect = session('pmks');

        $charts = DB::table('charts')->select('*')->where('jenis_pmks_id', null)->orderBy('total','desc')->get();
       
        $chartData = [];
        $chartDatatotalDtks = [];
        $chartMapDataId = [];
        $grandTotal = 0;
        $percentages = [];
        foreach ($charts as $key => $chart) {
            if($chart->total > 0){
                
            }
            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id', $chart->indonesia_cities_id)->first();
            $chartData[] = $kabupatenKota->name;
            $chartDatatotalDtks[] = (int)$chart->total;
            $chartMapDatatotalDtks[$kabupatenKota->name] = $kabupatenKota->id;
            $grandTotal += (int)$chart->total;

        }

        // dd($chartMapDatatotalDtks);

        // dd($chartDatatotalDtks);

        $dataGroupColour = [[],[],[]];
        foreach($chartDatatotalDtks as $index => $item){
            $percentages = round($item / ($grandTotal /100),0,PHP_ROUND_HALF_UP);
            
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
        
        $pmksDataGroup = [];

        $class_menu_data_dashboard = "menu-open";

        $jenisPmksSelect = session('pmks');
        if(!empty($jenisPmksSelect)){
            $jenisPmksSelect = DB::table('jenis_pmks')->select('id','jenis')->where('id',$jenisPmksSelect)->first();
        }
        
        
        return view('dashboard', compact('class_menu_data_dashboard', 'myChart', 'pmksDataGroup', 'jenisPmksSelect', 'chartData', 'chartDatatotalDtks','dataGroupColour','chartMapDatatotalDtks'));



    }

    function get_chart_data_map(){

    }

    public function set_session_pmks(Request $request){
        session(['pmks' => $request->input('jenisPmksId')]);
        return response()->json([session('pmks')]);
    }

    public function get_jenis_pmks(Request $request){
        $jenisPmks = DB::table('jenis_pmks')->select('id','jenis')->get();
        return response()->json($jenisPmks);
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

    public function get_pmks_kab(Request $request){
            $pmksKabupaten = Charts::with('jenis_pmks')
                    ->where('indonesia_cities_id', $request->get('q'))
                    ->where('jenis_pmks_id', '<>', null)
                    ->where('total', '>', 0)
                    ->orderBy('total','desc')->get();
            $pmksKabupatenList = [];
            $no = 1;
            foreach ($pmksKabupaten as $key => $pmks) {
                $pmksKabupatenList[] = ['no' => $no,'jenis_pmks' => $pmks->jenis_pmks->jenis, 'total' => $pmks->total];
                $no++;
            }
            return response()->json($pmksKabupatenList);
    }



}
