<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DependentDropdownController extends Controller
{
    public function provinces(Request $request)
    {
        $provinces = [];

        if ($request->has('q')) {
            $search = $request->q;
            $provinces = DB::table('indonesia_provinces')
                ->select('id', 'name')
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            // 72 kode sulawesi tengah
            $provinces = DB::table('indonesia_provinces')
                ->Where('id', '72')
                ->select('id', 'name')
                ->get();
        }

        
        return response()->json($provinces);
     
    }

    public function cities(Request $request)
    {
        $provinceID = $request->provinceID;
        if ($request->has('q')) {
            $search = $request->q;
            $cities = DB::table('indonesia_cities')
                ->select('id', 'name')
                ->Where('province_id', $provinceID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            $cities = DB::table('indonesia_cities')
                ->select('id', 'name')
                ->Where('province_id', '72')
                ->get();
            // dd($cities);
        }
        return response()->json($cities);
    }

    public function districts(Request $request)
    {
        $districts = [];
        $cityID = $request->regencyID;
        if ($request->has('q')) {
            $search = $request->q;
            $districts = DB::table('indonesia_districts')
                ->select('id', 'name')
                ->Where('regency_id', $cityID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();

        } else {
            $districts = DB::table('indonesia_districts')
                ->select('id', 'name')
                ->Where('regency_id', $cityID)
                ->get();
        }
        return response()->json($districts);
    }

    public function villages(Request $request)
    {
        $villages = [];
        $districtID = $request->districtID;
        if ($request->has('q')) {
            $search = $request->q;
            $villages = DB::table('indonesia_villages')
                ->select('id', 'name')
                ->Where('district_id', $districtID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            $villages = DB::table('indonesia_villages')
                ->select('id', 'name')
                ->Where('district_id', $districtID)
                ->get();

        }
        return response()->json($villages);
    }
}