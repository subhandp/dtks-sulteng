<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksErrorsImport;
use  App\Models\PmksDataTemp;
use  App\Models\PmksData;
use App\Jobs\ProcessImport;
use App\Jobs\PostingImport;
use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;


class DtksController extends Controller
{
    
    
    public function posting()
    {   
        $class_menu_pmks = "menu-open";
        $class_menu_posting = "sub-menu-open";
        $class_menu_daftar_pmks = "";
        $class_menu_pmks_import = "";
        return view('dtks.posting', compact('class_menu_posting','class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));


    }

    public function selectdtksimport(Request $request)
    {
        $dtksImport = [];

        if ($request->has('q')) {
            $search = $request->q;
            $dtksImport = DtksImport::select("id", "no_tiket")
                ->where('status_import', '=', "SUKSES IMPORT")
                ->where('jumlah_baris', '=', "-")
                ->where('no_tiket', 'LIKE', "%$search%")
                ->get();
        } else {
            $dtksImport = DtksImport::select("id", "no_tiket")
                ->where('status_import', '=', "SUKSES IMPORT")
                ->where('jumlah_baris', '=', "-")
                ->get();
        }
        return response()->json($dtksImport);
    }


   


}


