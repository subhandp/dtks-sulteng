<?php

namespace App\Http\Controllers;
// use App\SuratKeluar;
// use App\SuratMasuk;
// use App\Klasifikasi;
// use App\User;
use Illuminate\Support\Facades\Auth;
use App\Charts\DashboardChart1;
use App\Charts\DashboardChart2;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Charts;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
   
    public function index(DashboardChart1 $myChart1,DashboardChart2 $myChart2)
    {
        $myChart1 = $myChart1->build();
        $myChart2 = $myChart2->build();


        $jenisPmksSelect = session('pmks');

        $charts = DB::table('charts')->select('*')->where('jenis_pmks_id','=', null)->orderBy('total','desc')->get();
          
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

        $kabupatenKotaTotalPerJenisPmks = [];
        
        foreach ($kabupatenKota as $key => $kk) {
            $totalPerJenisPmks = DB::table('charts')
            ->where('indonesia_cities_id',$kk->id)
            ->where('jenis_pmks_id','<>',null)
            ->get();
            $kabupatenKotaTotalPerJenisPmks[] = [$kk,$totalPerJenisPmks];
        }
        $chartsKabJenis = Charts::with('jenis_pmks')->with('kabupaten_kota')->where('jenis_pmks_id', '<>', null)->orderBy('total','desc')->get();
        $jenisPmks = DB::table('jenis_pmks')->get();

        // dd($chartMapDatatotalDtks);

        // dd($chartDatatotalDtks);

        $dataGroupColour = [[],[],[]];
        foreach($chartDatatotalDtks as $index => $item){
            if ($grandTotal != 0) {
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
        
        $pmksDataGroup = [];

        $class_menu_data_dashboard = "menu-open";

        $jenisPmksSelect = session('pmks');
        if(!empty($jenisPmksSelect)){
            $jenisPmksSelect = DB::table('jenis_pmks')->select('id','jenis')->where('id',$jenisPmksSelect)->first();
        }
        
        return view('dashboard', compact('kabupatenKotaTotalPerJenisPmks','chartsKabJenis','jenisPmks','chartDataForTooltip','class_menu_data_dashboard', 'myChart1','myChart2', 'pmksDataGroup', 'jenisPmksSelect', 'chartData', 'chartDatatotalDtks','dataGroupColour','chartMapDatatotalDtks'));



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
                $pmksKabupatenList[] = ['no' => $no,'jenis_pmks' => $pmks->jenis_pmks->jenis, 'total' => number_format($pmks->total,0,',','.')];
                $no++;
            }
            return response()->json($pmksKabupatenList);
    }



}
