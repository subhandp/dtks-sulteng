<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\DtksErrorsImport;
use  App\Models\PsksPsm;
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
        $writer->save($filename);
        return url($filename);

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

            // $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
            $kabupatenKota = DB::table('indonesia_cities')->where('id', $request->input('kabupaten_kota'))->first();
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



}


