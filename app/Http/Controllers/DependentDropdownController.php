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
                ->select('code', 'name')
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            // 72 kode sulawesi tengah
            $provinces = DB::table('indonesia_provinces')
                ->Where('code', '72')
                ->select('code', 'name')
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
                ->select('code', 'name')
                ->Where('province_code', $provinceID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            $cities = DB::table('indonesia_cities')
                ->select('code', 'name')
                ->Where('province_code', $provinceID)
                ->get();
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
                ->select('code', 'name')
                ->Where('city_code', $cityID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();

        } else {
            $districts = DB::table('indonesia_districts')
                ->select('code', 'name')
                ->Where('city_code', $cityID)
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
                ->select('code', 'name')
                ->Where('district_code', $districtID)
                ->Where('name', 'LIKE', "%$search%")
                ->get();
        } else {
            $villages = DB::table('indonesia_villages')
                ->select('code', 'name')
                ->Where('district_code', $districtID)
                ->get();

        }
        return response()->json($villages);
    }
}