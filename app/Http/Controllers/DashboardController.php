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

class DashboardController extends Controller
{
   
    public function index(DashboardChart $chart)
    {
        $chart = $chart->build();

        $jenisPmksSelect = session('pmks');
        if(!empty($jenisPmksSelect)){
            // $jenisPmksSelect = DB::table('jenis_pmks')->select('id','jenis')->where('id',$jenisPmksSelect)->first();
            // $kabupaten_kota = DB::table('indonesia_cities')->select('id', 'name')->where('province_id','72')->get();
            // $chartData = [];
            // foreach ($kabupaten_kota as $key => $kk) {
            //     $totalJenis = DB::table('pmks_data')
            //                 ->select('iddtks')
            //                 ->where('kabupaten_kota', $kk->name)
            //                 ->where('jenis_pmks', $jenisPmksSelect->jenis)
            //                 ->count();
            //     $chartData[] = ['kab_kota' => $kk->name, 'total' => $totalJenis];
            // }
            
            // $pmksDataGroup = DB::table('pmks_data')
            //      ->where('jenis_pmks', '=', $jenisPmksSelect->jenis)
            //     //  ->select('kabupaten_kota', DB::raw('count(*) as total'))
            //      ->groupBy('kabupaten_kota')
            //      ->get(array(DB::raw('COUNT(id) as total'),'kabupaten_kota'));

            // dd($jenisPmksSelect->jenis);
        }
        else{
            // $pmksDataGroup = DB::table('pmks_data')
            //      ->select('kabupaten_kota', DB::raw('count(*) as total'))
            //      ->groupBy('kabupaten_kota')
            //      ->get();
        }

        // $pmksDataGroup = DB::table('pmks_data')
        //          ->select('kabupaten_kota', DB::raw('count(*) as total'))
        //          ->groupBy('kabupaten_kota')
        //          ->get();
        
        // dd($pmksDataGroup);

        $chartData =  DB::table('charts')->select('*')->get();

        $pmksDataGroup = [];

        $class_menu_data_dashboard = "menu-open";

        $jenisPmksSelect = session('pmks');
        if(!empty($jenisPmksSelect)){
            $jenisPmksSelect = DB::table('jenis_pmks')->select('id','jenis')->where('id',$jenisPmksSelect)->first();
        }
        
        
        return view('dashboard', compact('class_menu_data_dashboard', 'chart', 'pmksDataGroup', 'jenisPmksSelect', 'chartData'));



    }

    public function set_session_pmks(Request $request){
        session(['pmks' => $request->input('jenisPmksId')]);
        return response()->json([session('pmks')]);
    }

    public function get_jenis_pmks(Request $request){
        $jenisPmks = DB::table('jenis_pmks')->select('id','jenis')->get();
        return response()->json($jenisPmks);
    }
}
