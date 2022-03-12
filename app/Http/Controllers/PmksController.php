<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use  App\Models\DtksImport;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;  
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\PmksDataImport;
use App\Jobs\ProcessImport;
// use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;

class PmksController extends Controller
{
    
    public function index()
    {
        $data_pmks_import = DB::table('dtks_imports')
        ->orderBy('created_at', 'desc')
        ->get();
        
        $data_pmks_import_status = [];
        foreach ($data_pmks_import as $import) {
            $current_row = DB::table('pmks_data')
            ->where('dtks_import_id', '=', $import->id)
            ->count();
            $total_rows = (int) cache("total_rows_$import->id");
            $persentase = ceil(($current_row / $total_rows) * 100);
            $status = [
                'started' => filled(cache("start_date_$import->id")),
                'finished' => filled(cache("end_date_$import->id")),
                'current_row' => $current_row,
                'total_rows' => $total_rows,
                'persentase' =>  $persentase
            ];
            $data_pmks_import_status[] = $status;
        }
        

        $class_menu_pmks = "menu-open";
        $class_menu_pmks_import = "sub-menu-open";
        $class_menu_pmks_daftar = "";

        return view('pmks.index', compact('data_pmks_import_status','data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

        // return view('suratmasuk.index',['data_suratmasuk'=> $data_suratmasuk]);
    }

    public function daftarpmks()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $data_daftar_pmks = [];
        $class_menu_pmks = "menu-open";
        $class_menu_daftar_pmks = "sub-menu-open";
        $class_menu_pmks_import = "";

        return view('pmks.daftar', compact('data_daftar_pmks','class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));

    }

    
    private function rrmdir($dir)
    {
        if (is_dir($dir))
        {
        $objects = scandir($dir);

        foreach ($objects as $object)
        {
            if ($object != '.' && $object != '..')
            {
                if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
                else {unlink($dir.'/'.$object);}
            }
        }

        reset($objects);
        rmdir($dir);
        }
    }
    

//     public function import()
// {
//     request()->validate([
//         'file' => ['required', 'mimes:xlsx'],
//     ]);

//     $id = now()->unix()
//     session([ 'import' => $id ]);

//     Excel::queueImport(new ProductsImport($id), request()->file('file')->store('temp'));

//     return redirect()->back();
// }

    public function store (Request $request)
    {
       $request->validate([
            'tahun_data' => 'required',
            'jenis_pmks' => 'required',
            'upload' => 'required'
       ]);
        ProcessImport::dispatch($request->input('upload'));
        return redirect('/pmks/import-data')->with("sukses", 1);
       
    }
    

    public function status()
    {
        
        $id = request('id');

        return response([
            'started' => filled(cache("start_date_$id")),
            'finished' => filled(cache("end_date_$id")),
            'current_row' => (int) cache("current_row_$id"),
            'total_rows' => (int) cache("total_rows_$id"),
        ]);
    }


}
