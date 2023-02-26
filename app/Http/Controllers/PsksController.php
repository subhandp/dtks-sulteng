<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksErrorsImport;
use  App\Models\PsksPsm;
use  App\Models\PsksTksk;
use  App\Models\PsksLk3;
use  App\Models\PsksLks;
use  App\Models\PsksKt;
use  App\Models\PsksWksb;
use  App\Models\PsksFcsr;
use  App\Models\PsksFcu;

use App\Jobs\ProcessImportPsks;
use App\Jobs\ProcessDataChartPsks;

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
// use Storage;
use Illuminate\Support\Facades\Storage;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PsksController extends Controller
{
    
    public $counter;
    public $metodeImport;
    public $cell;

    function __construct()
    {
        $this->cell = array('A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' =>11, 'L' =>12, 'M' => 13, 'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' =>20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26, 'AA' => 27, 'AB' => 28, 'AC' => 29, 'AD' => 30, 'AE' => 31, 'AF' => 32, 'AG' => 33);
    }

    public function index()
    {
        $data_psks_import = DB::table('dtks_imports')
        ->where('jenis_dtks','psks')
        ->orderBy('updated_at', 'desc')
        ->get();
        
        $jenisPsks = DB::table('jenis_psks')->select('jenis','detail')->get();
        $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();

        $class_menu_psks = "menu-open";
        $class_menu_psks_import = "sub-menu-open";
        $class_menu_psks_daftar = "";

        return view('psks.index', compact('kabupatenKota', 'jenisPsks','data_psks_import','class_menu_psks','class_menu_psks_import','class_menu_psks_daftar'));

    }

    public function getDownloadExcelList(Request $request){

        $psmDataInstance = DB::table('psks_psms')->orderBy('id');
        $psmDataInstance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
            return $q->where('kabupaten_kota', $request->get('kabupaten_kota'));
        });

        $psmData = $psmDataInstance->paginate(50000);
        
        return $psmData;
    }

    public function downloadExcel(Request $request){
        $jenis = $request->input('psks');
        // $kabupatenKota = $request->input('kabupaten_kota');
        // $kabupatenKota = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();

        $psmDataInstance = DB::table('psks_psms')->orderBy('id');
        $psmDataInstance->when(!empty($request->input('kabupaten_kota')), function ($q) use($request){
            // dd($request->get('kabupaten_kota'));
            return $q->where('kabupaten_kota', $request->input('kabupaten_kota'));
        });

        $psmData = $psmDataInstance->paginate(50000)->items();
        $filename = $request->get('page').".xlsx";
        return $this->laporanPsm(['filename' =>$filename,'jenis'=>$jenis,'kabupaten_kota' => $request->input('kabupaten_kota'),'psmData' => $psmData]);
    }

    public function laporanPsm($param){
        $psmData = $param['psmData'];
        // dd($psmData);
        $filename = $param['filename'];
        $storagePath = storage_path('app/public/template_excel/');
        $pmsLayoutPath = $storagePath.'template_psm.xlsx';
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $pmsSpreadsheet = $reader->load($pmsLayoutPath);
        $no = 0;
        $row = 24;
        $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['G'], 4, $param['kabupaten_kota']);
        foreach ($psmData as $key => $psm) {
            $no++;
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['A'], $row, $no);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['B'], $row, $psm->nama_psm);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['F'], $row, $psm->jenis_kelamin);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['H'], $row, $psm->pendidikan_terakhir);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['J'], $row, $psm->nik_no_ktp);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['M'], $row, $psm->alamat_rumah);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['Q'], $row, $psm->no_hp);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['T'], $row, $psm->email);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['W'], $row, $psm->mulai_aktif);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['Y'], $row, $psm->legalitas_sertifikat);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['AA'], $row, $psm->jenis_diklat_yg_diikuti);
            $pmsSpreadsheet->getActiveSheet()->setCellValueByColumnAndRow($this->cell['AC'], $row, $psm->pendampingan);
            $row++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($pmsSpreadsheet, "Xlsx");
        $writer->save('exports/'.$filename);
        return url('exports/'.$filename);

        // $name = 'hello world.xlsx';
        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Hello World !');

        // $writer = new Xlsx($spreadsheet);
        // $writer->save($name);
        // return url($name);

    }

    public function store (Request $request)
    {
       $request->validate([
            'jenis_psks' => 'required',
            'upload' => 'required'
       ]);

        $jobs = DB::table('jobs')->select("*")->count();
        if($jobs > 0){
            return redirect('/psks/import-data')->with("gagal-jobs", 1);
        }
        else{
            ProcessImportPsks::dispatch(['upload' => $request->input('upload'), 'jenis_psks' => $request->input('jenis_psks'), 'kabupaten_kota' => $request->input('kabupaten_kota'),'jenis_dtks' => 'psks']);

            $batch = Bus::batch([])->dispatch();

            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('id',$request->input('kabupaten_kota'))->first();
            
            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis',$request->input('jenis_psks'))->first();

            $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

            return redirect('/psks/import-data')->with("sukses", 1);
        }
        
    }

    public function psmCreate(Request $request){
        $class_menu_data_psm = "sub-menu-open";
        $class_menu_data_psks = "menu-open";

        return view('psks.createPsm', compact(
            'class_menu_data_psm', 'class_menu_data_psks'
        ));
    }

    public function indexPsm(){

        $class_menu_data_psm = "sub-menu-open";
        $class_menu_data_psks = "menu-open";
        
        $kabupatenKota = DB::table('indonesia_cities')
        ->select('id', 'name')
        ->Where('province_id', '72')
        ->get();

        return view('psks.daftarPsm', compact('kabupatenKota','class_menu_data_psm','class_menu_data_psks'));

    }


    public function datatablesPsm(Request $request){


        $data = PsksPsm::query();
        $dataTable = DataTables::of($data)
            
            ->addIndexColumn()
            
                ->addColumn('action', function($row){
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->encode($row->id);
                    $btn = '<a href="'.route('psks.psm.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit PSM" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.psm.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';

                        return $btn;
                })
                ->filter(function ($instance) use ($request) {

                    if ($request->has('kabupaten_kota')) {

                        $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                            $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                            return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                        });


                    }
                    if (!empty($request->get('search'))) {
                         $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('kabupaten_kota', 'LIKE', "%$search%")
                            ->orWhere('nama_psm', 'LIKE', "%$search%")
                            ->orWhere('nik_no_ktp', 'LIKE', "%$search%")
                            ->orWhere('jenis_kelamin', 'LIKE', "%$search%")
                            ->orWhere('pendidikan_terakhir', 'LIKE', "%$search%")
                            ->orWhere('alamat_rumah', 'LIKE', "%$search%")
                            ->orWhere('no_hp', 'LIKE', "%$search%")
                            ->orWhere('email', 'LIKE', "%$search%")
                            ->orWhere('mulai_aktif', 'LIKE', "%$search%")
                            ->orWhere('legalitas_sertifikat', 'LIKE', "%$search%")
                            ->orWhere('jenis_diklat_yg_diikuti', 'LIKE', "%$search%")
                            ->orWhere('pendampingan', 'LIKE', "%$search%");
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

    public function psmEditCreate(Request $request){

        $class_menu_data_psks = "menu-open";
        $class_menu_data_psm = "sub-menu-open";

        $kabupatenKotaCreate = DB::table('indonesia_cities')
                    ->select('id', 'name')
                    ->Where('province_id', '72')
                    ->get();

        if($request->has('q')){
            $myhashid = $request->input('q');
            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($myhashid);

            if(DB::table('psks_psms')->where('id', $id)->exists()){
                $psmData = DB::table('psks_psms')->select("*")->where('id', $id)->first();

                $kabupatenKotaCreateSelect = DB::table('indonesia_cities')
                    ->Where('province_id', '72')
                    ->Where('name', $psmData->kabupaten_kota)
                    ->select('id', 'name')
                    ->first();
                


                return view('psks.createEditPsm', compact(
                    'psmData', 'myhashid',
                    'class_menu_data_psks', 'class_menu_data_psm', 
                    'kabupatenKotaCreate','kabupatenKotaCreateSelect'
                ));
            }
            

        }

        return view('psks.createEditPsm', compact(
            'class_menu_data_psks', 'class_menu_data_psm', 
            'kabupatenKotaCreate'
        ));

        
    }

    public function psmStore(Request $request){
        
       //jika form edit
        if ($request->has('id')) {

            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($request->input('id'))[0];

            $request->validate([
                'nama_psm' => 'required|unique:psks_psms,nama_psm,'.$id.',id',
                'kabupaten_kota' => 'required',
                'jenis_kelamin' => 'required',
                'pendidikan_terakhir' => 'required',
                'nik_no_ktp' => 'required | max:16',
                'alamat_rumah' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
                'mulai_aktif' => 'required',
                'legalitas_sertifikat' => 'required',
                'jenis_diklat_yg_diikuti' => 'required',
                'pendampingan' => 'required',
           ]);

           $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();

            if(DB::table('psks_psms')->where('id', $id)->exists()){

                $storeData = request()->except(['_token','id']);
                $storeData['kabupaten_kota'] = $kabupatenKota->name;

                DB::table('psks_psms')
                ->where('id', $id)
                ->update($storeData);
                    
                

                $batch = Bus::batch([])->dispatch();
                $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','psm')->first();

                foreach ($kabupatenKota as $kk) {
                        $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                }


                return back()->with('success', 'Data berhasil di update');
            }
            
        }
        else{

            $request->validate([
                'nama_psm' => 'required|unique:psks_psms,nama_psm',
                'kabupaten_kota' => 'required',
                'jenis_kelamin' => 'required',
                'pendidikan_terakhir' => 'required',
                'nik_no_ktp' => 'required | max:16',
                'alamat_rumah' => 'required',
                'no_hp' => 'required',
                'email' => 'required',
                'mulai_aktif' => 'required',
                'legalitas_sertifikat' => 'required',
                'jenis_diklat_yg_diikuti' => 'required',
                'pendampingan' => 'required',
           ]);

            $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();

            $storeData = request()->except(['_token']);
            $storeData['kabupaten_kota'] = $kabupatenKota->name;
            DB::table('psks_psms')
                ->insert($storeData);


            $batch = Bus::batch([])->dispatch();
            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','psm')->first();

            $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));
            


            return back()->with('success', 'Data berhasil di rekam.');
        }

        abort(404);
        
    }


    
    public function indexTksk(){

        $class_menu_data_tksk = "sub-menu-open";
        $class_menu_data_psks = "menu-open";
        
        $kabupatenKota = DB::table('indonesia_cities')
        ->select('id', 'name')
        ->Where('province_id', '72')
        ->get();

        return view('psks.daftarTksk', compact('kabupatenKota','class_menu_data_tksk','class_menu_data_psks'));

    }


    public function datatablesTksk(Request $request){


        $data = PsksTksk::query();
        $dataTable = DataTables::of($data)
            
            ->addIndexColumn()
            
                ->addColumn('action', function($row){
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->encode($row->id);
                    $btn = '<a href="'.route('psks.tksk.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit TKSK" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.tksk.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
        
                        return $btn;
                })
                ->filter(function ($instance) use ($request) {
        
                    if ($request->has('kabupaten_kota')) {
        
                        $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                            $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                            return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                        });
        
        
                    }
                    if (!empty($request->get('search'))) {
                         $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('no_induk_tksk_a', 'LIKE', "%$search%")
                            ->orWhere('no_induk_tksk_b', 'LIKE', "%$search%")
                            ->orWhere('kabupaten_kota', 'LIKE', "%$search%")
                            ->orWhere('kecamatan', 'LIKE', "%$search%")
                            ->orWhere('nama', 'LIKE', "%$search%")
                            ->orWhere('nama_ibu_kandung', 'LIKE', "%$search%")
                            ->orWhere('nomor_nik', 'LIKE', "%$search%")
                            ->orWhere('tanggal_lahir', 'LIKE', "%$search%")
                            ->orWhere('tempat_lahir', 'LIKE', "%$search%")
                            ->orWhere('jenis_kelamin', 'LIKE', "%$search%")
                            ->orWhere('alamat_rumah', 'LIKE', "%$search%")
                            ->orWhere('no_hp', 'LIKE', "%$search%")
                            ->orWhere('pendidikan_terakhir', 'LIKE', "%$search%")
                            ->orWhere('tahun_pengangkatan_tksk', 'LIKE', "%$search%")
                            ->orWhere('mulai_aktif', 'LIKE', "%$search%")
                            ->orWhere('legalitas_sertifikat', 'LIKE', "%$search%")
                            ->orWhere('jenis_diklat_yg_diikuti', 'LIKE', "%$search%")
                            ->orWhere('pendampingan', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action']);
        
                
                $response = $dataTable->make(true);
                $query = $dataTable->getQuery()->toSql();
                return $response;
        
        }
        
        
        public function tkskEditCreate(Request $request){
        
        $class_menu_data_psks = "menu-open";
        $class_menu_data_tksk = "sub-menu-open";
        
        $kabupatenKotaEdit = null;
        $kecamatanEdit = null;

        
        if($request->has('q')){
            $myhashid = $request->input('q');
            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($myhashid);
        
            if(DB::table('psks_tksks')->where('id', $id)->exists()){
                $tkskData = DB::table('psks_tksks')->select("*")->where('id', $id)->first();
        
                $kabupatenKotaEdit = DB::table('indonesia_cities')
                ->Where('province_id', '72')
                ->Where('name', $tkskData->kabupaten_kota)
                ->select('id', 'name')
                ->first();

                $kecamatanEdit = DB::table('indonesia_districts')
                ->Where('name', $tkskData->kecamatan)
                ->select('id', 'name')
                ->first();
        
        
                return view('psks.createEditTksk', compact(
                    'tkskData', 'myhashid',
                    'class_menu_data_psks', 'class_menu_data_tksk', 
                    'kabupatenKotaEdit','kecamatanEdit',
                ));
            }
            
        
        }
        
        return view('psks.createEditTksk', compact(
            'class_menu_data_psks', 'class_menu_data_tksk', 
            'kabupatenKotaEdit','kecamatanEdit',
        ));
        
        
        }
        
        public function tkskStore(Request $request){
        
        //jika form edit
        if ($request->has('id')) {
        
            $hashids = new Hashids('dtks', 15); 
            $id = $hashids->decode($request->input('id'))[0];
        
            $request->validate([
                'no_induk_tksk_a' => 'required',
                'no_induk_tksk_b' => 'required',
                'kabupaten_kota' => 'required',
                'kecamatan' => 'required',
                'nama' => 'required',
                'nama_ibu_kandung' => 'required',
                'nomor_nik' => 'required',
                'tanggal_lahir' => 'required',
                'tempat_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'alamat_rumah' => 'required',
                'no_hp' => 'required',
                'pendidikan_terakhir' => 'required',
                'tahun_pengangkatan_tksk' => 'required',

           ]);
        
           $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
           if(!$kabupatenKota){
            $kabupatenKota = $request->input('kabupaten_kota');
           }
           else{
            $kabupatenKota = $kabupatenKota->name;
           }

           $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
           if(!$kecamatan){
            $kecamatan = $request->input('kecamatan');
           }
           else{
            $kecamatan = $kecamatan->name;
           }

        
            if(DB::table('psks_tksks')->where('id', $id)->exists()){
        
                $storeData = request()->except(['_token','id']);
                $storeData['kabupaten_kota'] = $kabupatenKota;
                $storeData['kecamatan'] = $kecamatan;
        
                DB::table('psks_tksks')
                ->where('id', $id)
                ->update($storeData);
                    
                
        
                $batch = Bus::batch([])->dispatch();
                $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','tksk')->first();
        
                foreach ($kabupatenKota as $kk) {
                        $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                }
        
        
                return back()->with('success', 'Data berhasil di update');
            }
            
        }
        else{
        
            $request->validate([
                'no_induk_tksk_a' => 'required',
                'no_induk_tksk_b' => 'required',
                'kabupaten_kota' => 'required',
                'kecamatan' => 'required',
                'nama' => 'required',
                'nama_ibu_kandung' => 'required',
                'nomor_nik' => 'required',
                'tanggal_lahir' => 'required',
                'tempat_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'alamat_rumah' => 'required',
                'no_hp' => 'required',
                'pendidikan_terakhir' => 'required',
                'tahun_pengangkatan_tksk' => 'required',

           ]);
        
           $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
            $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();

            $storeData = request()->except(['_token']);
            $storeData['kabupaten_kota'] = $kabupatenKota->name;
            $storeData['kecamatan'] = $kecamatan->name;

        
            
            DB::table('psks_tksks')
                ->insert($storeData);
        
        
            $batch = Bus::batch([])->dispatch();
            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','tksk')->first();
        
            $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));
            
        
        
            return back()->with('success', 'Data berhasil di rekam.');
        }
        
        abort(404);
        
        }

        public function dataerrors(){
            $id = request('id');
            $importErrors = DtksErrorsImport::where('dtks_import_id', $id)->orderBy('id', 'desc')->get(['row','values','errors']);
            return json_encode($importErrors);
            // return Datatables::of(DtksErrorsImport::where('dtks_import_id', $id)->select())->make(true);
        }

        //awal lk3

        public function indexLk3(){

            $class_menu_data_lk3 = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarLk3', compact('kabupatenKota','class_menu_data_lk3','class_menu_data_psks'));
    
        }
    
    
        public function datatablesLk3(Request $request){
    
    
            $data = PsksLk3::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.lk3.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit LK3" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.lk3.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('nama_lk3', 'LIKE', "%$search%")
                                ->orWhere('alamat_kantor', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_lk3', 'LIKE', "%$search%")
                                ->orWhere('no_hp_ketua_lk3', 'LIKE', "%$search%")
                                ->orWhere('jenis_lk3', 'LIKE', "%$search%")
                                ->orWhere('legalitas_lk3', 'LIKE', "%$search%")
                                ->orWhere('jumlah_tenaga_professional', 'LIKE', "%$search%")
                                ->orWhere('jumlah_klien', 'LIKE', "%$search%")
                                ->orWhere('jumlah_masalah_kasus', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action']);
            
                    
                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }
            
            
            public function lk3EditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_lk3 = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_lk3s')->where('id', $id)->exists()){
                        $lk3Data = DB::table('psks_lk3s')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $lk3Data->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                     

                
                        return view('psks.createEditLk3', compact(
                            'lk3Data', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_lk3', 
                            'kabupatenKotaEdit',
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditLk3', compact(
                    'class_menu_data_psks', 'class_menu_data_lk3', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function lk3Store(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_lk3' => 'required',
                            'alamat_kantor' => 'required',
                            'no_hp_ketua_lk3' => 'required',
                            'email' => 'required',
                            'nama_ketua_lk3' => 'required',
                            'legalitas_lk3' => 'required',
                            'jenis_lk3' => 'required',
                            'jumlah_tenaga_professional' => 'required|numeric',
                            'jumlah_klien' => 'required|numeric',
                            'jumlah_masalah_kasus' => 'required|numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                   

                        if(DB::table('psks_lk3s')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;

                    
                            DB::table('psks_lk3s')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_lk3' => 'required',
                            'alamat_kantor' => 'required',
                            'no_hp_ketua_lk3' => 'required',
                            'email' => 'required',
                            'nama_ketua_lk3' => 'required',
                            'legalitas_lk3' => 'required',
                            'jenis_lk3' => 'required',
                            'jumlah_tenaga_professional' => 'required|numeric',
                            'jumlah_klien' => 'required|numeric',
                            'jumlah_masalah_kasus' => 'required|numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                    

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                       

                        DB::table('psks_lk3s')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }

            //akhir lk3
        

            //awal lks

        public function indexLks(){

            $class_menu_data_lks = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarLks', compact('kabupatenKota','class_menu_data_lks','class_menu_data_psks'));
    
        }
    
    
        public function datatablesLks(Request $request){
    
    
            $data = PsksLks::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.lks.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit LKS" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.lks.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('nama_lks', 'LIKE', "%$search%")
                                ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                                ->orWhere('kecamatan', 'LIKE', "%$search%")
                                ->orWhere('no_hp', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_lks', 'LIKE', "%$search%")
                                ->orWhere('legalitas_lks', 'LIKE', "%$search%")
                                ->orWhere('ruang_lingkup', 'LIKE', "%$search%")
                                ->orWhere('jenis_kegiatan', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action']);
            
                    
                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }


            public function lksEditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_lks = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_lks')->where('id', $id)->exists()){
                        $lksData = DB::table('psks_lks')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $lksData->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                        $kecamatanEdit = DB::table('indonesia_districts')
                        ->Where('name', $lksData->kecamatan)
                        ->select('id', 'name')
                        ->first();

                        $desaKelurahanEdit = DB::table('indonesia_villages')
                        ->Where('name', $lksData->desa_kelurahan)
                        ->select('id', 'name')
                        ->first();

                
                        return view('psks.createEditLks', compact(
                            'lksData', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_lks', 
                            'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditLks', compact(
                    'class_menu_data_psks', 'class_menu_data_lks', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function lksStore(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_lks' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_lks' => 'required',
                            'legalitas_lks' => 'required',
                            'posisi_lks' => 'required',
                            'ruang_lingkup' => 'required',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                       $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                       if(!$kecamatan){
                        $kecamatan = $request->input('kecamatan');
                       }
                       else{
                        $kecamatan = $kecamatan->name;
                       }

                       $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                       if(!$desaKelurahan){
                        $desaKelurahan = $request->input('desa_kelurahan');
                       }
                       else{
                        $desaKelurahan = $desaKelurahan->name;
                       }

                        if(DB::table('psks_lks')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;
                            $storeData['kecamatan'] = $kecamatan;
                            $storeData['desa_kelurahan'] = $desaKelurahan;

                    
                            DB::table('psks_lks')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_lks' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_lks' => 'required',
                            'legalitas_lks' => 'required',
                            'posisi_lks' => 'required',
                            'ruang_lingkup' => 'required',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                        $storeData['kecamatan'] = $kecamatan->name;
                        $storeData['desa_kelurahan'] = $desaKelurahan->name;

                        DB::table('psks_lks')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }

            //akhir lks


             //awal kt

        public function indexKt(){

            $class_menu_data_kt = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarKt', compact('kabupatenKota','class_menu_data_kt','class_menu_data_psks'));
    
        }
    
    
        public function datatablesKt(Request $request){
    
    
            $data = PsksKt::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.kt.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit KT" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.kt.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                              $w->orWhere('nama_kt', 'LIKE', "%$search%")
                                ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                                ->orWhere('kecamatan', 'LIKE', "%$search%")
                                ->orWhere('no_hp', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_kt', 'LIKE', "%$search%")
                                ->orWhere('klasifikasi_kt', 'LIKE', "%$search%")
                                ->orWhere('jumlah_pengurus', 'LIKE', "%$search%")
                                ->orWhere('jenis_kegiatan', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action']);

                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }


            public function ktEditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_kt = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_kts')->where('id', $id)->exists()){
                        $ktData = DB::table('psks_kts')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $ktData->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                        $kecamatanEdit = DB::table('indonesia_districts')
                        ->Where('name', $ktData->kecamatan)
                        ->select('id', 'name')
                        ->first();

                        $desaKelurahanEdit = DB::table('indonesia_villages')
                        ->Where('name', $ktData->desa_kelurahan)
                        ->select('id', 'name')
                        ->first();

                
                        return view('psks.createEditKt', compact(
                            'ktData', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_kt', 
                            'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditKt', compact(
                    'class_menu_data_psks', 'class_menu_data_kt', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function ktStore(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_kt' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_kt' => 'required',
                            'legalitas_kt' => 'required',
                            'klasifikasi_kt' => 'required',
                            'jumlah_pengurus' => 'required|numeric',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                       $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                       if(!$kecamatan){
                        $kecamatan = $request->input('kecamatan');
                       }
                       else{
                        $kecamatan = $kecamatan->name;
                       }

                       $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                       if(!$desaKelurahan){
                        $desaKelurahan = $request->input('desa_kelurahan');
                       }
                       else{
                        $desaKelurahan = $desaKelurahan->name;
                       }

                        if(DB::table('psks_kts')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;
                            $storeData['kecamatan'] = $kecamatan;
                            $storeData['desa_kelurahan'] = $desaKelurahan;

                    
                            DB::table('psks_kts')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_kt' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_kt' => 'required',
                            'legalitas_kt' => 'required',
                            'klasifikasi_kt' => 'required',
                            'jumlah_pengurus' => 'required|numeric',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                        $storeData['kecamatan'] = $kecamatan->name;
                        $storeData['desa_kelurahan'] = $desaKelurahan->name;

                        DB::table('psks_kts')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }


            //akhir kt



            //awal wkskbm

        public function indexWkskbm(){

            $class_menu_data_wkskbm = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarWkskbm', compact('kabupatenKota','class_menu_data_wkskbm','class_menu_data_psks'));
    
        }
    
    
        public function datatablesWkskbm(Request $request){
            $data = PsksWksb::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.wkskbm.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit WKSKBM" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.wkskbm.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                              $w->orWhere('nama_wksb', 'LIKE', "%$search%")
                                ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                                ->orWhere('kecamatan', 'LIKE', "%$search%")
                                ->orWhere('no_hp', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_wksbm', 'LIKE', "%$search%")
                                ->orWhere('klasifikasi_wksbm', 'LIKE', "%$search%")
                                ->orWhere('jumlah_pengurus', 'LIKE', "%$search%")
                                ->orWhere('jumlah_anggota', 'LIKE', "%$search%")
                                ->orWhere('jenis_kegiatan', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");
            
                            });
                        }
                    })
                    ->rawColumns(['action']);
            

                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }


            public function wkskbmEditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_wkskbm = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_wksbs')->where('id', $id)->exists()){
                        $wkskbmData = DB::table('psks_wksbs')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $wkskbmData->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                        $kecamatanEdit = DB::table('indonesia_districts')
                        ->Where('name', $wkskbmData->kecamatan)
                        ->select('id', 'name')
                        ->first();

                        $desaKelurahanEdit = DB::table('indonesia_villages')
                        ->Where('name', $wkskbmData->desa_kelurahan)
                        ->select('id', 'name')
                        ->first();

                
                        return view('psks.createEditWkskbm', compact(
                            'wkskbmData', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_wkskbm', 
                            'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditWkskbm', compact(
                    'class_menu_data_psks', 'class_menu_data_wkskbm', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function wkskbmStore(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_wksb' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_wksbm' => 'required',
                            'legalitas_wksbm' => 'required',
                            'jumlah_anggota' => 'required|numeric',
                            'jumlah_pengurus' => 'required|numeric',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                       $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                       if(!$kecamatan){
                        $kecamatan = $request->input('kecamatan');
                       }
                       else{
                        $kecamatan = $kecamatan->name;
                       }

                       $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                       if(!$desaKelurahan){
                        $desaKelurahan = $request->input('desa_kelurahan');
                       }
                       else{
                        $desaKelurahan = $desaKelurahan->name;
                       }

                        if(DB::table('psks_wksbs')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;
                            $storeData['kecamatan'] = $kecamatan;
                            $storeData['desa_kelurahan'] = $desaKelurahan;

                    
                            DB::table('psks_wksbs')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_wksb' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_wksbm' => 'required',
                            'legalitas_wksbm' => 'required',
                            'jumlah_anggota' => 'required|numeric',
                            'jumlah_pengurus' => 'required|numeric',
                            'jenis_kegiatan' => 'required',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                        $storeData['kecamatan'] = $kecamatan->name;
                        $storeData['desa_kelurahan'] = $desaKelurahan->name;

                        DB::table('psks_wksbs')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','wkskbm')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }


            //akhir wkskbm


            
            //awal fcsr

        public function indexFcsr(){

            $class_menu_data_fcsr = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarFcsr', compact('kabupatenKota','class_menu_data_fcsr','class_menu_data_psks'));
    
        }
    
    
        public function datatablesFcsr(Request $request){
            $data = PsksFcsr::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.fcsr.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit FCSR" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.fcsr.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                              $w->orWhere('nama_fcsr', 'LIKE', "%$search%")
                                ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                                ->orWhere('kecamatan', 'LIKE', "%$search%")
                                ->orWhere('no_hp', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_pengurus_fcsr', 'LIKE', "%$search%")
                                ->orWhere('legalitas_fcsr', 'LIKE', "%$search%")
                                ->orWhere('jumlah_pengurus', 'LIKE', "%$search%")
                                ->orWhere('jumlah_anggota', 'LIKE', "%$search%")
                                ->orWhere('jumlah_csr_kesos_perusahaan', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");

                            });
                        }
                    })
                    ->rawColumns(['action']);
            

                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }


            public function fcsrEditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_fcsr = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_fcsrs')->where('id', $id)->exists()){
                        $fcsrData = DB::table('psks_fcsrs')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $fcsrData->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                        $kecamatanEdit = DB::table('indonesia_districts')
                        ->Where('name', $fcsrData->kecamatan)
                        ->select('id', 'name')
                        ->first();

                        $desaKelurahanEdit = DB::table('indonesia_villages')
                        ->Where('name', $fcsrData->desa_kelurahan)
                        ->select('id', 'name')
                        ->first();

                
                        return view('psks.createEditFcsr', compact(
                            'fcsrData', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_fcsr', 
                            'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditFcsr', compact(
                    'class_menu_data_psks', 'class_menu_data_fcsr', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function fcsrStore(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_fcsr' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_pengurus_fcsr' => 'required',
                            'legalitas_fcsr' => 'required',
                            'jumlah_csr_kesos_perusahaan' => 'required|numeric',
                            'jumlah_pengurus' => 'required|numeric',
                            'jumlah_anggota' => 'required|numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                       $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                       if(!$kecamatan){
                        $kecamatan = $request->input('kecamatan');
                       }
                       else{
                        $kecamatan = $kecamatan->name;
                       }

                       $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                       if(!$desaKelurahan){
                        $desaKelurahan = $request->input('desa_kelurahan');
                       }
                       else{
                        $desaKelurahan = $desaKelurahan->name;
                       }

                        if(DB::table('psks_fcsrs')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;
                            $storeData['kecamatan'] = $kecamatan;
                            $storeData['desa_kelurahan'] = $desaKelurahan;

                    
                            DB::table('psks_fcsrs')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_fcsr' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'no_hp' => 'required',
                            'email' => 'required',
                            'nama_ketua_pengurus_fcsr' => 'required',
                            'legalitas_fcsr' => 'required',
                            'jumlah_csr_kesos_perusahaan' => 'required|numeric',
                            'jumlah_pengurus' => 'required|numeric',
                            'jumlah_anggota' => 'required|numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                        $storeData['kecamatan'] = $kecamatan->name;
                        $storeData['desa_kelurahan'] = $desaKelurahan->name;

                        DB::table('psks_fcsrs')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }

            //akhir fcsr


              //awal fcu

        public function indexFcu(){

            $class_menu_data_fcu = "sub-menu-open";
            $class_menu_data_psks = "menu-open";
            
            $kabupatenKota = DB::table('indonesia_cities')
            ->select('id', 'name')
            ->Where('province_id', '72')
            ->get();
    
            return view('psks.daftarFcu', compact('kabupatenKota','class_menu_data_fcu','class_menu_data_psks'));
    
        }
    
    
        public function datatablesFcu(Request $request){
            $data = PsksFcu::query();
            $dataTable = DataTables::of($data)
                
                ->addIndexColumn()
                
                    ->addColumn('action', function($row){
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->encode($row->id);
                        $btn = '<a href="'.route('psks.fcu.edit',['q' => $id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit FCU" data-offset="50%, 3"><i class="fas fa-edit"></i></a> <a href="'.route('psks.fcu.delete',['q' => $id]).'" onclick="return confirm(\'Hapus Data ?\')" class="delete btn btn-default btn-sm data-toggle="tooltip" data-placement="left" title="Delete"><i class="fas fa-trash"></i></a>';
            
                            return $btn;
                    })
                    ->filter(function ($instance) use ($request) {
            
                        if ($request->has('kabupaten_kota')) {
            
                            $instance->when(!empty($request->get('kabupaten_kota')), function ($q) use($request){
                                $kabupatenKotaSelect = DB::table('indonesia_cities')->select('name')->where('id',$request->get('kabupaten_kota'))->first();
                                return $q->where('kabupaten_kota', $kabupatenKotaSelect->name);
                            });
            
            
                        }
                        if (!empty($request->get('search'))) {
                             $instance->where(function($w) use($request){
                                $search = $request->get('search');
                              $w->orWhere('nama_fcu', 'LIKE', "%$search%")
                                ->orWhere('desa_kelurahan', 'LIKE', "%$search%")
                                ->orWhere('kecamatan', 'LIKE', "%$search%")
                                ->orWhere('no_hp_ketua_fcu', 'LIKE', "%$search%")
                                ->orWhere('email', 'LIKE', "%$search%")
                                ->orWhere('nama_ketua_fcu', 'LIKE', "%$search%")
                                ->orWhere('legalitas_fcu', 'LIKE', "%$search%")
                                ->orWhere('jumlah_keluarga_pionir', 'LIKE', "%$search%")
                                ->orWhere('jumlah_keluarga_plasma', 'LIKE', "%$search%")
                                ->orWhere('kabupaten_kota', 'LIKE', "%$search%");


                            });
                        }
                    })
                    ->rawColumns(['action']);
            

                    $response = $dataTable->make(true);
                    $query = $dataTable->getQuery()->toSql();
                    return $response;
            
            }

            
            public function fcuEditCreate(Request $request){
            
                $class_menu_data_psks = "menu-open";
                $class_menu_data_fcu = "sub-menu-open";
                
                $kabupatenKotaEdit = null;
                $kecamatanEdit = null;
                $desaKelurahanEdit = null;

                if($request->has('q')){
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
                
                    if(DB::table('psks_fcus')->where('id', $id)->exists()){
                        $fcuData = DB::table('psks_fcus')->select("*")->where('id', $id)->first();
                
                        $kabupatenKotaEdit = DB::table('indonesia_cities')
                            ->Where('province_id', '72')
                            ->Where('name', $fcuData->kabupaten_kota)
                            ->select('id', 'name')
                            ->first();

                        $kecamatanEdit = DB::table('indonesia_districts')
                        ->Where('name', $fcuData->kecamatan)
                        ->select('id', 'name')
                        ->first();

                        $desaKelurahanEdit = DB::table('indonesia_villages')
                        ->Where('name', $fcuData->desa_kelurahan)
                        ->select('id', 'name')
                        ->first();

                
                        return view('psks.createEditFcu', compact(
                            'fcuData', 'myhashid',
                            'class_menu_data_psks', 'class_menu_data_fcu', 
                            'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                        ));
                    }
                    
                
                }
                
                return view('psks.createEditFcu', compact(
                    'class_menu_data_psks', 'class_menu_data_fcu', 
                    'kabupatenKotaEdit','kecamatanEdit','desaKelurahanEdit'
                ));
                
                
                }


                public function fcuStore(Request $request){
            
                    //jika form edit
                    if ($request->has('id')) {
                    
                        $hashids = new Hashids('dtks', 15); 
                        $id = $hashids->decode($request->input('id'))[0];
                    
                        $request->validate([
                            'nama_fcu' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'email' => 'required',
                            'nama_ketua_fcu' => 'required',
                            'no_hp_ketua_fcu' => 'required',
                            'legalitas_fcu' => 'required',
                            'jumlah_keluarga_pionir' => 'required|numeric',
                            'jumlah_keluarga_plasma' => 'required|numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                       $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                       if(!$kabupatenKota){
                        $kabupatenKota = $request->input('kabupaten_kota');
                       }
                       else{
                        $kabupatenKota = $kabupatenKota->name;
                       }

                       $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                       if(!$kecamatan){
                        $kecamatan = $request->input('kecamatan');
                       }
                       else{
                        $kecamatan = $kecamatan->name;
                       }

                       $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();
                       if(!$desaKelurahan){
                        $desaKelurahan = $request->input('desa_kelurahan');
                       }
                       else{
                        $desaKelurahan = $desaKelurahan->name;
                       }

                        if(DB::table('psks_fcus')->where('id', $id)->exists()){
                    
                            $storeData = request()->except(['_token','id']);
                            $storeData['kabupaten_kota'] = $kabupatenKota;
                            $storeData['kecamatan'] = $kecamatan;
                            $storeData['desa_kelurahan'] = $desaKelurahan;

                    
                            DB::table('psks_fcus')
                            ->where('id', $id)
                            ->update($storeData);
                                
                            
                    
                            $batch = Bus::batch([])->dispatch();
                            $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
                            $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                            foreach ($kabupatenKota as $kk) {
                                    $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));
                            }
                    
                    
                            return back()->with('success', 'Data berhasil di update');
                        }
                        
                    }
                    else{
                    
                        $request->validate([
                            'nama_fcu' => 'required',
                            'desa_kelurahan' => 'required',
                            'kecamatan' => 'required',
                            'email' => 'required',
                            'nama_ketua_fcu' => 'required',
                            'no_hp_ketua_fcu' => 'required',
                            'legalitas_fcu' => 'required',
                            'jumlah_keluarga_pionir' => 'required|numeric',
                            'jumlah_keluarga_plasma' => 'required||numeric',
                            'kabupaten_kota' => 'required',
                       ]);
                    
                        $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
                        $kecamatan = DB::table('indonesia_districts')->where('id', $request->input('kecamatan'))->first();
                        $desaKelurahan = DB::table('indonesia_villages')->where('id', $request->input('desa_kelurahan'))->first();

                        $storeData = request()->except(['_token']);
                        $storeData['kabupaten_kota'] = $kabupatenKota->name;
                        $storeData['kecamatan'] = $kecamatan->name;
                        $storeData['desa_kelurahan'] = $desaKelurahan->name;

                        DB::table('psks_fcus')
                            ->insert($storeData);
                    
                    
                        $batch = Bus::batch([])->dispatch();
                        $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis','lk3')->first();
                    
                        $batch->add(new ProcessDataChartPsks($kabupatenKota,$jenisPsks));

                        return back()->with('success', 'Data berhasil di rekam.');
                    }
                    
                    abort(404);
                    
                    }
            //akhir fcu


            //awal delete

            public function psmDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_psms')->where('id', $id)->exists()){
                        DB::table('psks_psms')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data PSM berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function tkskDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_tksks')->where('id', $id)->exists()){
                        DB::table('psks_tksks')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data TKSK berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function lk3Delete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_lk3s')->where('id', $id)->exists()){
                        DB::table('psks_lk3s')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data LK3 berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function lksDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_lks')->where('id', $id)->exists()){
                        DB::table('psks_lks')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data LKS berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function ktDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_kts')->where('id', $id)->exists()){
                        DB::table('psks_kts')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data KT berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function wkskbmDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_wksbs')->where('id', $id)->exists()){
                        DB::table('psks_wksbs')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data WKSBM berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function fcsrDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_fcsrs')->where('id', $id)->exists()){
                        DB::table('psks_fcsrs')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data FCSR berhasil di Hapus.');
                }
        
                abort(404);
            }

            public function fcuDelete(Request $request){
                if($request->has('q')){
        
                    $myhashid = $request->input('q');
                    $hashids = new Hashids('dtks', 15); 
                    $id = $hashids->decode($myhashid);
        
                    if(DB::table('psks_fcus')->where('id', $id)->exists()){
                        DB::table('psks_fcus')->where('id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data FCU berhasil di Hapus.');
                }
        
                abort(404);
            }



            public function deleteImportData(Request $request){
                if($request->has('q')){
        
        
                    $id = $request->input('q');
        
                    if(DB::table('psks_fcsrs')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_fcsrs')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_fcus')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_fcus')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_kts')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_kts')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_lk3s')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_lk3s')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_lks')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_lks')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_psms')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_psms')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_tksks')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_tksks')->where('dtks_import_id', $id)->delete();
                    }

                    if(DB::table('psks_wksbs')->where('dtks_import_id', $id)->exists()){
                        DB::table('psks_wksbs')->where('dtks_import_id', $id)->delete();
                    }
        
                    return back()->with('sukseshapus', 'Data Import PSKS berhasil di Hapus.');
                }
        
                abort(404);
            }

            //akhir delete

}


