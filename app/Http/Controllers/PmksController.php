<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksErrorsImport;
use  App\Models\PmksDataTemp;
use  App\Models\PmksData;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;  
// use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\PmksDataImport;
use App\Jobs\ProcessImport;
use App\Jobs\ProcessDataChart;

// use App\Jobs\PostingImport;
use App\Models\DtksImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Hashids\Hashids;
use Illuminate\Support\Facades\File;
use stdClass;
use Illuminate\Support\Facades\Bus;

use App\Exports\PmksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Core\XLSXWriter;
use Illuminate\Support\Facades\Log;
use Storage;
class PmksController extends Controller
{
    
    public $counter;
    public $metodeImport;
    public function index()
    {
        $data_pmks_import = DB::table('dtks_imports')
        ->orderBy('updated_at', 'desc')
        ->get();
        
        $jenisPmks = DB::table('jenis_pmks')->select('jenis','detail')->get();

        $class_menu_pmks = "menu-open";
        $class_menu_pmks_import = "sub-menu-open";
        $class_menu_pmks_daftar = "";
        

        return view('pmks.index', compact('jenisPmks','data_pmks_import','class_menu_pmks','class_menu_pmks_import','class_menu_pmks_daftar'));

    }

    public function dataerrors(){
        $id = request('id');
        $importErrors = DtksErrorsImport::where('dtks_import_id', $id)->orderBy('id', 'desc')->get(['row','values','errors']);
        return json_encode($importErrors);
        // return Datatables::of(DtksErrorsImport::where('dtks_import_id', $id)->select())->make(true);
    }

    public function getExportExcel(Request $request){
        $class_menu_data_pmks = "menu-open";

        $pmksDataInstance = DB::table('pmks_data')->orderBy('id');
        $pmksDataInstance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
            return $q->where('kabupaten_kota', $request->get('kabupaten_kota'));
        });

        $pmksDataInstance->when(!empty($request->get('kecamatan')), function ($q) use($request){

            return $q->where('kecamatan', $request->get('kecamatan'));
        });

        $pmksDataInstance->when(!empty($request->get('desa_kelurahan')), function ($q) use($request){
            
            return $q->where('desa_kelurahan', $request->get('desa_kelurahan'));
        });

        $pmksDataInstance->when(!empty($request->get('jenis_pmks')), function ($q) use($request){
                    
            return $q->where('jenis_pmks', $request->get('jenis_pmks'));
        });

        $pmksDataInstance->when(!empty($request->get('tahun_data')), function ($q) use($request){
                    
            return $q->where('tahun_data', $request->get('tahun_data'));
        });

        $pmksData = $pmksDataInstance->paginate(50000);
        
        return $pmksData;
    }

    public function exportExcel(Request $request){


        $header = [
            "iddtks",
            "provinsi",
            "kabupaten_kota",
            "kecamatan",
            "desa_kelurahan",
            "alamat",
            "dusun",
            "rt",
            "rw",
            "nomor_kk",
            "nomor_nik",
            "nama",
            "tanggal_lahir",
            "tempat_lahir",
            "jenis_kelamin",
            "nama_ibu_kandung",
            "hubungan_keluarga",
            "tahun_data",
            "jenis_pmks"

        ];
        $time_start = microtime(true);
        $writer = new XLSXWriter();
        // $writer->writeSheetHeader('Sheet1', array('c1'=>'string','c2'=>'string','c3'=>'string','c4'=>'string') );//optional

        // $pmksData = DB::table('pmks_data')
        //         ->orderBy('id')
        //         ->limit(50000)
        //         ->get();

        $pmksDataInstance = DB::table('pmks_data')->orderBy('id');
        $pmksDataInstance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
            // dd($request->get('kabupaten_kota'));
            $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
            return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
        });

        $pmksDataInstance->when(!empty($request->get('kecamatan')), function ($q) use($request){
            
            $kecamatanSelect = DB::table('indonesia_districts')->select('name')->where('id',$request->get('kecamatan'))->first();

            return $q->where('kecamatan', $kecamatanSelect->name);
        });

        $pmksDataInstance->when(!empty($request->get('desa_kelurahan')), function ($q) use($request){
            
            $desaKelurahanSelect = DB::table('indonesia_villages')->select('name')->where('id',$request->get('desa_kelurahan'))->first();

            return $q->where('desa_kelurahan', $desaKelurahanSelect->name);
        });

        $pmksDataInstance->when(!empty($request->get('jenis_pmks')), function ($q) use($request){
                    
            return $q->where('jenis_pmks', $request->get('jenis_pmks'));
        });

        $pmksDataInstance->when(!empty($request->get('tahun_data')), function ($q) use($request){
                    
            return $q->where('tahun_data', $request->get('tahun_data'));
        });

        $pmksData = $pmksDataInstance->paginate(50000)->items();

        $styles1 = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'bold', 'halign'=>'center', 'border'=>'left,right,top,bottom', 'border-style' => 'medium');
$styles2 = array( ['font-size'=>6],['font-size'=>8],['font-size'=>10],['font-size'=>16] );
$styles3 = array( ['font'=>'Arial'],['font'=>'Courier New'],['font'=>'Times New Roman'],['font'=>'Comic Sans MS']);
$styles4 = array( ['font-style'=>'bold'],['font-style'=>'italic'],['font-style'=>'underline'],['font-style'=>'strikethrough']);
$styles5 = array( ['color'=>'#f00'],['color'=>'#0f0'],['color'=>'#00f'],['color'=>'#666']);
$styles6 = array( ['fill'=>'#ffc'],['fill'=>'#fcf'],['fill'=>'#ccf'],['fill'=>'#cff']);
$styles7 = array( 'border'=>'left,right,top,bottom');
$styles8 = array( ['halign'=>'left'],['halign'=>'right'],['halign'=>'center'],['halign'=>'none']);
$styles9 = array( array(),['border'=>'left,top,bottom'],['border'=>'top,bottom'],['border'=>'top,bottom,right']);


$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles1 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles2 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles3 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles4 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles5 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles6 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles7 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles8 );
$writer->writeSheetRow('Sheet1', $rowdata = array(300,234,456,789), $styles9 );
$writer->writeToFile('xlsx-styles.xlsx');

        // $filename = "example.xlsx";
        // header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        // header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // header('Content-Transfer-Encoding: binary');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // ob_clean();
        // flush();
        // $writer->writeToStdOut();
        $fileName = $request->get('page').".xlsx";
        $writer->writeToFile($fileName);

        return url($fileName);
        // $filecontent=$writer->writeToString();
        
        // return $filecontent;
        // $writer->writeToFile('pmks-data.xlsx');
//         $headers = [
//             'Content-Type' => 'application/pdf',
//          ];

// return response()->download($file, 'filename.pdf', $headers);
        // response()->download(public_path($fileName));
        // Display Script End time
        // $time_end = microtime(true);

        //dividing with 60 will give the execution time in minutes other wise seconds
        // $execution_time = ($time_end - $time_start)/60;

        //execution time of the script
        // echo '<b>Total Execution Time:</b> '.$execution_time.' Mins'."\n";

        // echo '#'.floor((memory_get_peak_usage())/1024/1024)."MB"."\n";

        // exit(0);

        // return json_encode(['status' => 'sukses']);


        // (new PmksExport)->queue('invoices.xlsx');
        // return (new PmksExport)->download('invoices.xlsx');

        // $name = 'test.csv';
        // (new PmksExport())->queue('public/exports/' . $name)->chain([
        //     new NotifyUserOfExport($request->user(), $name),
        // ]);
    
        // return back()->with('message', 'This export will take some time. You will receive an email when it is ready to download.');
    }

    // public function exportExcel(){
    //     return Excel::download(new PmksExport, 'pmks.xlsx');
    // }

    // public function dataimportpmks(){
        

    //     $data = PmksDataTemp::select('*');

    //     return DataTables::of($data)
    //                     ->addIndexColumn()
    //                                 ->addColumn('no_tiket',function(PmksDataTemp $pmksdatatemp){
    //                                     return $pmksdatatemp->dtksImports->no_tiket;
    //                                     //return DB::raw("SELECT * FROM 'patients' WHERE 'patients_id' = ?", $action->patient_id);
    //                                 })
    //                                 ->addColumn('action', function($row){
                    
    //                                     $btn = '<span class="badge badge-warning">Belum Posting</span><a href="javascript:void(0)" class="edit btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Edit"><i class="fas fa-edit"></i></a> <a href="javascript:void(0)" class="edit btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
                    
    //                                         return $btn;
    //                                 })
    //                                 ->rawColumns(['action'])
    //                                 ->make(true);

    // }

    public function daftarpmks()
    {
        // $data_suratmasuk = SuratMasuk::where('users_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        // $data_daftar_pmks = DB::table('pmks_data_temps')
        // ->orderBy('created_at', 'desc')
        // ->get();
        $class_menu_pmks = "menu-open";
        $class_menu_daftar_pmks = "sub-menu-open";
        $class_menu_pmks_import = "";
        // $class_menu_posting = "";

        return view('pmks.daftar-imported', compact('class_menu_pmks','class_menu_daftar_pmks','class_menu_pmks_import'));

    }

    public function data(){
        $class_menu_data_pmks = "menu-open";
        
        $kabupatenKota = DB::table('indonesia_cities')
        ->select('id', 'name')
        ->Where('province_id', '72')
        ->get();

        $jenisPmks = DB::table('jenis_pmks')->select('jenis','detail')->get();

        return view('pmks.daftar', compact('kabupatenKota','class_menu_data_pmks','jenisPmks'));

    }

    public function datapmks(Request $request){


        $data = PmksData::query();
        $dataTable = DataTables::of($data)
            
            ->addIndexColumn()
            
                ->addColumn('action', function($row){
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->encode($row->id);
                    $btn = '<a href="'.route('pmks.edit-create',['q' => $id]).'" class="edit btn btn-default btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fas fa-edit"></i></a> <a href="'.route('pmks.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';

                        return $btn;
                })
                ->filter(function ($instance) use ($request) {
                    // $query = $instance->query();

                   

                    if ($request->has('kabupaten_kota')) {
                        // if(!empty($request->get('kabupaten_kota'))){
                        //     $instance->where('kabupaten_kota', $request->get('kabupaten_kota'));
                        // }

                        $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                            // dd($request->get('kabupaten_kota'));
                            $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                            return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                        });

                        $instance->when(!empty($request->get('kecamatan')), function ($q) use($request){
                            
                            $kecamatanSelect = DB::table('indonesia_districts')->select('name')->where('id',$request->get('kecamatan'))->first();
        
                            return $q->where('kecamatan', $kecamatanSelect->name);
                        });

                        $instance->when(!empty($request->get('desa_kelurahan')), function ($q) use($request){
                            
                            $desaKelurahanSelect = DB::table('indonesia_villages')->select('name')->where('id',$request->get('desa_kelurahan'))->first();
        
                            return $q->where('desa_kelurahan', $desaKelurahanSelect->name);
                        });

                        $instance->when(!empty($request->get('jenis_pmks')), function ($q) use($request){
                                    
                            return $q->where('jenis_pmks', $request->get('jenis_pmks'));
                        });

                        $instance->when(!empty($request->get('tahun_data')), function ($q) use($request){
                                    
                            return $q->where('tahun_data', $request->get('tahun_data'));
                        });

                        // return($request);

                        
                    }
                    if (!empty($request->get('search'))) {
                         $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('kabupaten_kota', 'LIKE', "%$search%")
                            ->orWhere('jenis_pmks', 'LIKE', "%$search%")
                            ->orWhere('iddtks', 'LIKE', "%$search%")
                            ->orWhere('nomor_nik', 'LIKE', "%$search%")
                            ->orWhere('nama', 'LIKE', "%$search%")
                            ->orWhere('tahun_data', 'LIKE', "%$search%")
                            ->orWhere('kecamatan', 'LIKE', "%$search%")
                            ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                            ->orWhere('alamat', 'LIKE', "%$search%")
                            ->orWhere('nomor_kk', 'LIKE', "%$search%")
                            ->orWhere('jenis_kelamin', 'LIKE', "%$search%")
                            ->orWhere('dusun', 'LIKE', "%$search%");

                        });
                    }
                })
                ->rawColumns(['action']);

                
                $response = $dataTable->make(true);
                $query = $dataTable->getQuery()->toSql();
                // Storage::put('file.txt', $query);
                // Log::info($query);
                // dd($query);
                return $response;
                

                


    }

    
    // private function rrmdir($dir)
    // {
    //     if (is_dir($dir))
    //     {
    //     $objects = scandir($dir);

    //     foreach ($objects as $object)
    //     {
    //         if ($object != '.' && $object != '..')
    //         {
    //             if (filetype($dir.'/'.$object) == 'dir') {rrmdir($dir.'/'.$object);}
    //             else {unlink($dir.'/'.$object);}
    //         }
    //     }

    //     reset($objects);
    //     rmdir($dir);
    //     }
    // }
    
    public function store (Request $request)
    {
       $request->validate([
            'tahun_data' => 'required',
            'jenis_pmks' => 'required',
            'upload' => 'required'
       ]);

        $jobs = DB::table('jobs')->select("*")->count();
        if($jobs > 0){
            return redirect('/pmks/import-data')->with("gagal-jobs", 1);
        }
        else{
            ProcessImport::dispatch(['upload' => $request->input('upload'), 'tahun_data' => $request->input('tahun_data'), 'jenis_pmks' => $request->input('jenis_pmks')]);

            $batch = Bus::batch([])->dispatch();

            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
            $jenisPmks = DB::table('jenis_pmks')->select('id', 'jenis')->where('jenis',$request->input('jenis_pmks'))->first();
            // DB::table('charts')->truncate();
            foreach ($kabupatenKota as $kk) {
                // foreach ($jenisPmks as $pmks) {
                    $batch->add(new ProcessDataChart($kk,$jenisPmks));
                // }
            }

            return redirect('/pmks/import-data')->with("sukses", 1);
        }
        
    }

        
    public function editCreate(Request $request){
        if($request->has('q')){
            $myhashid = $request->input('q');
            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($myhashid);

            if(DB::table('pmks_data')->where('id', $id)->exists()){
                $pmksData = DB::table('pmks_data')->select('iddtks', 'provinsi', 'kabupaten_kota', 'kecamatan', 'desa_kelurahan', 'alamat', 'dusun', 'rt', 'rw',
                'nomor_kk', 'nomor_nik', 'nama', 'tanggal_lahir', 'tempat_lahir', 'jenis_kelamin', 'nama_ibu_kandung',
                'hubungan_keluarga', 'tahun_data', 'jenis_pmks')->where('id', $id)->first();
                // dd($pmksData);
                $provinces = DB::table('indonesia_provinces')
                    ->Where('id', '72')
                    ->select('id', 'name')
                    ->first();

                $kabupatenKotaCreateSelect = DB::table('indonesia_cities')
                    ->Where('province_id', '72')
                    ->Where('name', $pmksData->kabupaten_kota)
                    ->select('id', 'name')
                    ->first();
                
                
                if(!empty($kabupatenKotaCreateSelect)){
                    
                    $kecamatanCreateSelect = DB::table('indonesia_districts')
                    ->Where('regency_id', $kabupatenKotaCreateSelect->id)
                    ->Where('name', $pmksData->kecamatan)
                    ->select('id', 'name')
                    ->first();

                    $kecamatanCreate = DB::table('indonesia_districts')
                    ->select('id', 'name')
                    ->Where('regency_id', $kabupatenKotaCreateSelect->id)
                    ->get();

                    $kabupatenKotaCreate = DB::table('indonesia_cities')
                    ->select('id', 'name')
                    ->Where('province_id', '72')
                    ->get();
                }
                else{
                    $kecamatanCreateSelect = null;
                    $kecamatanCreate = null;
                    $kabupatenKotaCreate = null;
                }
                
                
                if(!empty($kecamatanCreateSelect)){
                    $desaKelurahanCreateSelect = DB::table('indonesia_villages')
                    ->Where('district_id', $kecamatanCreateSelect->id)
                    ->Where('name', $pmksData->desa_kelurahan)
                    ->select('id', 'name')
                    ->first();

                    $desaKelurahanCreate = DB::table('indonesia_villages')
                    ->select('id', 'name')
                    ->Where('district_id', $kecamatanCreateSelect->id)
                    ->get();
                }
                else{
                    $desaKelurahanCreateSelect = null;
                    $desaKelurahanCreate = null;
                 }
                
                
                $jenisPmks = DB::table('jenis_pmks')->select('jenis','detail')->get();
                $class_menu_data_pmks = "menu-open";
                return view('pmks.edit', compact(
                    'pmksData', 'myhashid',
                    'class_menu_data_pmks', 'jenisPmks', 'provinces',
                    'desaKelurahanCreate', 'kecamatanCreate', 'kabupatenKotaCreate',
                    'desaKelurahanCreateSelect', 'kecamatanCreateSelect', 'kabupatenKotaCreateSelect'
                ));
            }
            

        }

        abort(404);
        
    }

    public function storeEdit(Request $request){
        
        $request->validate([
            'iddtks' => 'required',
            'tahun_data' => 'required',
            'jenis_pmks' => 'required',
            'provinsi' => 'required',
            'kabupaten_kota' => 'required',
            'kecamatan' => 'required',
            'desa_kelurahan' => 'required',
            'alamat' => 'required',
            'dusun' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'nomor_kk' => 'required|max:16',
            'nomor_nik' => 'required|max:16',
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'tempat_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nama_ibu_kandung' => 'required',
            'hubungan_keluarga' => 'required',
       ]);

       

        if ($request->has('id')) {

            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($request->input('id'))[0];

            if(DB::table('pmks_data')->where('id', $id)->exists()){
                $provinsi = DB::table('indonesia_provinces')->where('id', $request->input('provinsi'))->first();
                $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                // $id = $request->all()['id'];
            
                $storeData = request()->except(['_token','id']);
                
                $storeData['provinsi'] = $provinsi->name;
                $storeData['kabupaten_kota'] = $kabupatenKota->name;
                $storeData['kecamatan'] = $kecamatan->name;
                $storeData['desa_kelurahan'] = $desaKelurahan->name;

                DB::table('pmks_data')
                ->where('id', $id)
                ->update($storeData);
                    

                // PmksData::create($storeData);
                return back()->with('success', 'Data berhasil di update');
            }
            
        }

        abort(404);
        
    }

    public function create(Request $request){
        $jenisPmks = DB::table('jenis_pmks')->select('jenis','detail')->get();
        $class_menu_data_pmks = "menu-open";
        return view('pmks.create', compact(
            'class_menu_data_pmks', 'jenisPmks'
        ));
    }

    

    public function storeCreate(Request $request){
        // dd($request);
        $request->validate([
            'iddtks' => 'required|unique:pmks_data,iddtks',
            'tahun_data' => 'required',
            'jenis_pmks' => 'required',
            'provinsi' => 'required',
            'kabupaten_kota' => 'required',
            'kecamatan' => 'required',
            'desa_kelurahan' => 'required',
            'alamat' => 'required',
            'dusun' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'nomor_kk' => 'required|max:16',
            'nomor_nik' => 'required|max:16',
            'nama' => 'required',
            'tanggal_lahir' => 'required',
            'tempat_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nama_ibu_kandung' => 'required',
            'hubungan_keluarga' => 'required',
       ]);

        $provinsi = DB::table('indonesia_provinces')->where('id', $request->input('provinsi'))->first();
        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

        $storeData = $request->all();
        
        $storeData['provinsi'] = $provinsi->name;
        $storeData['kabupaten_kota'] = $kabupatenKota->name;
        $storeData['kecamatan'] = $kecamatan->name;
        $storeData['desa_kelurahan'] = $desaKelurahan->name;

        PmksData::create($storeData);
        return back()->with('success', 'Data berhasil di rekam.');
    }


    public function delete(Request $request){
        if($request->has('q')){

            $myhashid = $request->input('q');
            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($myhashid);

            if(DB::table('pmks_data')->where('id', $id)->exists()){
                DB::table('pmks_data')->where('id', $id)->delete();
            }

            return back()->with('sukseshapus', 'Data berhasil di Hapus.');
        }

        abort(404);
    }

}



 // dd($data_pmks_import);

        // $pmks_datas = DB::table('pmks_data_temps')
        //     ->where('dtks_import_id', '=',  1)
        //     ->get();
        // dd($pmks_data);
            // $this->counter = 0;

            // $this->metodeImport = 'firstOrCreate';
            // DB::table('pmks_data_temps')->where('dtks_import_id', 1)
            // ->chunkById(500, function ($pmks_datas) {
            //     foreach ($pmks_datas as $key => $pmks_data) {
            //         $this->counter++;
            //         if(empty($pmks_data->iddtks) && empty($pmks_data->nomor_nik)){
            //             DtksErrorsImport::create([
            //                 'dtks_import_id' => 1,
            //                 'row' => $this->counter,
            //                 'attribute' => '[iddtks, nomor_nik]',
            //                 'values' => '-',
            //                 'errors' => 'data kosong'
            //             ]);
            //         }
            //         else{
            //             $checkIddtks = PmksData::where('iddtks', $pmks_data->iddtks)->count();
            //             $checkNik = PmksData::where('nomor_nik', $pmks_data->nomor_nik)->count();

            //             if($checkIddtks > 0 && $checkNik > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[iddtks, nomor_nik]',
            //                     'values' => $pmks_data->iddtks,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else if($checkIddtks > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[iddtks]',
            //                     'values' => $pmks_data->iddtks,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else if($checkNik > 0){
            //                 DtksErrorsImport::create([
            //                     'dtks_import_id' => 1,
            //                     'row' => $this->counter,
            //                     'attribute' => '[nomor_nik]',
            //                     'values' => $pmks_data->nomor_nik,
            //                     'errors' => 'data sudah ada'
            //                 ]);
            //             }
            //             else{
            //                 PmksData::create([
            //                     'dtks_import_id' => $this->id,
            //                     'iddtks' => $pmks_data->iddtks, 
            //                     'provinsi' => $pmks_data->provinsi, 
            //                     'kabupaten_kota' => $pmks_data->kabupaten_kota, 
            //                     'kecamatan' => $pmks_data->kecamatan, 
            //                     'desa_kelurahan' => $pmks_data->desa_keluarahan, 
            //                     'alamat' => $pmks_data->alamat, 
            //                     'dusun' => $pmks_data->dusun, 
            //                     'rt' => $pmks_data->rt, 
            //                     'rw' => $pmks_data->rw,
            //                     'nomor_kk' => $pmks_data->nomor_kk, 
            //                     'nomor_nik' => $pmks_data->nomor_nik, 
            //                     'nama' => $pmks_data->nama, 
            //                     'tanggal_lahir' => $pmks_data->tanggal_lahir, 
            //                     'tempat_lahir' => $pmks_data->tempat_lahir, 
            //                     'jenis_kelamin' => $pmks_data->jenis_kelamin, 
            //                     'nama_ibu_kandung' => $pmks_data->nama_ibu_kandung,
            //                     'hubungan_keluarga' => $pmks_data->hubungan_keluarga, 
            //                     'tahun_data' => $pmks_data->tahun_data, 
            //                     'jenis_pmks' => $pmks_data->jenis_pmks
            //                 ]);
            //             }

            //         }
                    // $pmks_data = ['pmks_data' => $pmks_data];
                    // // dd($pmks_data);
                    
                    // $validator = Validator::make($pmks_data, [
                    //     'pmks_data.iddtks' => 'required'
                    // ]);

                    // // Check validation failure
                    // if ($validator->fails()) {
                    //     dd($validator->messages()->get('*'));
                    //     // dd($validator->messages()->get('*')['pmks_data.iddtks'][0]);
                    //     // DtksErrorsImport::create([
                    //     //     'dtks_import_id' => 1,
                    //     //     'row' => 0,
                    //     //     'attribute' => 'tes',
                    //     //     'values' => 'tes',
                    //     //     'errors' => $validator->messages()->get('*')['pmks_data.iddtks'][0]
                    //     // ]);
                    // }
                
                    // // Check validation success
                    // if ($validator->passes()) {
                    //     dd('suksess');
                    // }

                

                    // DB::table('users')
                    //     ->where('id', $user->id)
                    //     ->update(['active' => true]);
            //     }
            // });
        
            //                         array:2 [▼
                        //   "pmks_data.iddtks" => array:1 [▼
                        //     0 => "The pmks data.iddtks field is required."
                        //   ]
                        //   "pmks_data.kabupaten_kota" => array:1 [▼
                        //     0 => "The pmks data.kabupaten kota field is required."
                        //   ]
                        // ]