<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;  
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PmksDataImport;

class PmksController extends Controller
{
    
    public function index()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $data_pmks_import = [];
        $class_menu_pmks = "menu-open";
        $class_menu_pmks_import = "sub-menu-open";
        $class_menu_pmks_daftar = "";

        return view('pmks.index', compact('data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

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
       ]);

       
    //    $pmksdata = new PmksData();
    //    $suratkeluar->no_surat     = $request->input('no_surat');
    //    $suratkeluar->tujuan_surat = $request->input('tujuan_surat');
    //    $suratkeluar->isi          = $request->input('isi');
    //    $suratkeluar->kode         = $request->input('kode');
    //    $suratkeluar->tgl_surat    = $request->input('tgl_surat');
    //    $suratkeluar->tgl_catat    = $request->input('tgl_catat');
    //    $suratkeluar->keterangan   = $request->input('keterangan');
    //    $suratkeluar->users_id = Auth::id();
    //    $suratkeluar->save();

       if($request->input('upload')){
        foreach($request->input('upload') as $uploads){
            $upload = json_decode($uploads, true);
         
            $finalpath = $upload['disk'].'/'.explode('/',$upload['filepath'])[1];

            $dtksimport = new DtksImport();
            $dtksimport->no_tiket = 'default';
            $dtksimport->filename = $upload['filename'];
            $dtksimport->filepath = $finalpath;
            $dtksimport->jumlah_baris = '-';
            $dtksimport->status_import = 'file-stored';
            $dtksimport->keterangan = '-';
            $dtksimport->save();

            // pindahkan file upload di tmp folder ke final path (public)
            File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
        }

        // session([ 'import' => $dtksimport->id ]);
        $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
        // dd($path);
        // $import =  );
        // $import->import($path);

        Excel::import(new PmksDataImport($dtksimport->id, $request->input('tahun_data'), $request->input('jenis_pmks')), $path);



        // if($import->failures()->isNotEmpty()){
        //     return back()->withFailures($import->failures());
        // }

        // return back()->withStatus('Excel file imported successfully');

        return redirect('/pmks/import-data')->with("sukses", $dtksimport->id);

    }

    return 'gagal';
       
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
