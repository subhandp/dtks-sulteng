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
use Storage;
class PsksController extends Controller
{
    
    public $counter;
    public $metodeImport;
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

            // $batch = Bus::batch([])->dispatch();

            // $kabupatenKota = DB::table('indonesia_cities')->select('id','name')->where('province_id','72')->get();
            // $jenisPsks = DB::table('jenis_psks')->select('id', 'jenis')->where('jenis',$request->input('jenis_psks'))->first();

            // foreach ($kabupatenKota as $kk) {

            //         $batch->add(new ProcessDataChartPsks($kk,$jenisPsks));

            // }

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
                    
                // PmksData::create($storeData);
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

            return back()->with('success', 'Data berhasil di rekam.');
        }

        abort(404);
        
    }



}


