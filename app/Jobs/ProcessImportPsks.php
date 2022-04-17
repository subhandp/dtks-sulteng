<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  App\Models\DtksImport;

// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;  
// use Maatwebsite\Excel\Facades\Excel;
use App\Models\DtksErrorsImport;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
// use Hashids\Hashids;


class ProcessImportPsks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
    public $counter;
    public $metodeImport;
    public $dtksimportId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            try {
                

                foreach($this->request['upload'] as $uploads){
                    $upload = json_decode($uploads, true);
                
                    $finalpath = $upload['disk'].'/'.explode('/',$upload['filepath'])[1];
        
                    $dtksimport = new DtksImport();
                    $dtksimport->no_tiket = 'default';
                    $dtksimport->filename = $upload['filename'];
                    $dtksimport->filepath = $finalpath;
                    $dtksimport->jumlah_baris = '-';
                    $dtksimport->baris_selesai = '-';
                    $dtksimport->status_import = 'FILE TERSIMPAN';
                    $dtksimport->keterangan = $upload['size'];
                    $dtksimport->save();
                    $this->dtksimportId = $dtksimport->id;
                    File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
                }

                $jenis_psks = $this->request['jenis_psks'];

                $kabupaten_kota = DB::table('indonesia_cities')->select('name')
                                    ->where('id',$this->request['kabupaten_kota'])
                                    ->first()->name;

                $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
                
                $digits = 4;
                $ra =  rand(pow(10, $digits-1), pow(10, $digits)-1);
                $noTiket= $ra.$dtksimport->id;

                DtksImport::find($this->dtksimportId)
                            ->update(['status_import' => 'Prosess Import...', 'no_tiket' => $noTiket]);
                
                    
                    //ambil line pertama dari csv untuk header
                    $file = fopen($path,"r");
                        $headerCsv = fgetcsv($file,0,';');
                        $rowTotalCsv = 0;
                        if($file){
                            while(!feof($file)){
                                  $content = fgets($file);
                              if($content)    $rowTotalCsv++;
                            }
                        }
                    fclose($file);  
                                        
                    $headerFcsr = [
                        'nama_fcsr',
                        'desa_kelurahan',
                        'kecamatan',
                        'no_hp',
                        'email',
                        'nama_ketua_pengurus_fcsr',
                        'legalitas_fcsr',
                        'jumlah_pengurus',
                        'jumlah_anggota',
                        'jumlah_csr_kesos_perusahaan'
                    ];
                
                $headerFcu = [
                        'nama_fcu',
                        'desa_kelurahan',
                        'kecamatan',
                        'email',
                        'nama_ketua_fcu',
                        'no_hp_ketua_fcu',
                        'legalitas_fcu',
                        'jumlah_keluarga_pionir',
                        'jumlah_keluarga_plasma'
                    ];
                
                $headerKt = [
                        'nama_kt',
                        'desa_kelurahan',
                        'kecamatan',
                        'no_hp',
                        'email',
                        'nama_ketua_kt',
                        'legalitas_kt',
                        'klasifikasi_kt',
                        'jumlah_pengurus',
                        'jenis_kegiatan'
                    ];
                
                $headerLk3 = [
                        'nama_lk3',
                        'alamat_kantor',
                        'email',
                        'nama_ketua_lk3',
                        'no_hp_ketua_lk3',
                        'jenis_lk3',
                        'legalitas_lk3',
                        'jumlah_tenaga_professional',
                        'jumlah_klien',
                        'jumlah_masalah_kasus'
                    ];

                $headerLks = [
                        'nama_lks',
                        'desa_kelurahan',
                        'kecamatan',
                        'no_hp',
                        'email',
                        'nama_ketua_lks',
                        'legalitas_lks',
                        'posisi_lks',
                        'ruang_lingkup',
                        'jenis_kegiatan'
                    ];
                
                $headerPsm = [
                        'nama_psm',
                        'jenis_kelamin',
                        'pendidikan_terakhir',
                        'nik_no_ktp',
                        'alamat_rumah',
                        'no_hp',
                        'email',
                        'mulai_aktif',
                        'legalitas_sertifikat',
                        'jenis_diklat_yg_diikuti',
                        'pendampingan'
                    ];
                
                $headerTksk = [
                        'nama_tksk',
                        'jenis_kelamin',
                        'pendidikan_terakhir',
                        'nik_no_ktp',
                        'alamat_rumah',
                        'no_hp',
                        'email',
                        'mulai_aktif',
                        'legalitas_sertifikat',
                        'jenis_diklat_yg_diikuti',
                        'pendampingan'
                    ];
                
                $headerWksb = [
                        'nama_wksb',
                        'desa_kelurahan',
                        'kecamatan',
                        'no_hp',
                        'email', 
                        'nama_ketua_wksbm',
                        'legalitas_wksbm',
                        'jumlah_pengurus',
                        'jumlah_anggota',
                        'jenis_kegiatan'
                    ];

                    switch ($jenis_psks) {
                        case 'psm':
                            $header = $headerPsm;
                            break;
                        case 'tksk':
                            $header = $headerTksk;
                            break;
                        case 'lk3':
                            $header = $headerLk3;
                            break;
                        case 'lks':
                            $header = $headerLks;
                            break;
                        case 'kt':
                            $header = $headerKt;
                            break;
                        case 'wkskbm':
                            $header = $headerWksb;
                            break;
                        case 'fcsr':
                            $header = $headerFcsr;
                            break;
                        case 'fcu':
                            $header = $headerFcu;
                            break;
                        
                        default:
                            $header = [];
                            break;
                    }

                    if(count($header) > 0){
                        $validHeader = true;
                        foreach ($header as $key => $h) {
                            $h = Str::slug($h);
                            $hCompare = Str::slug($headerCsv[$key]);
                            if( $h != $hCompare ){
                                $validHeader = false;
                                break;
                            }
                        }
                    }
                    else{
                        $validHeader = false;
                    }
                    

                    // bandingkan apa header csv yang di upload sesuai jika tidak catat error
                    if(!$validHeader){
                        DtksErrorsImport::create([
                            'dtks_import_id' => $this->dtksimportId,
                            'row' => 0,
                            'attribute' => $jenis_psks.' [ProcessImportPsks]',
                            'values' => 'Format CSV Tidak Valid.',
                            'errors' => 'Header CSV: '.implode(',',$headerCsv)
                        ]);

                        DtksImport::find($this->dtksimportId)
                            ->update(['status_import' => 'GAGAL IMPORT']);

                        return False;
                    }
                    else{
                        
                        DtksImport::find($this->dtksimportId)
                            ->update(['jumlah_baris' => $rowTotalCsv]);

                        try { 

                            $pdo = DB::connection()->getPdo();
                            $path = str_replace('\\', '/', $path);
                        

                            $pdo->exec("
                            DROP TEMPORARY TABLE IF EXISTS temporary_table;
                            
                            CREATE TEMPORARY TABLE temporary_table SELECT * FROM psks_psms WHERE 1=0;

                            LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE temporary_table FIELDS TERMINATED BY ';' enclosed by '\"' lines terminated by '\\n' IGNORE 1 LINES (
                                nama_psm,
                                jenis_kelamin,
                                pendidikan_terakhir,
                                nik_no_ktp,
                                alamat_rumah,
                                no_hp,
                                email,
                                mulai_aktif,
                                legalitas_sertifikat,
                                jenis_diklat_yg_diikuti,
                                pendampingan, 
                                @created_at, @updated_at,@dtks_import_id,@kabupaten_kota) 
                                SET kabupaten_kota = '".$kabupaten_kota."', dtks_import_id = '".$this->dtksimportId."', created_at = NOW(), updated_at = NOW();
                            
                            SHOW COLUMNS FROM psks_psms;

                            INSERT INTO psks_psms
                                SELECT * FROM temporary_table
                                ON DUPLICATE KEY UPDATE 
                                dtks_import_id = '".$this->dtksimportId."',
                                nama_psm = VALUES(nama_psm), 
                                jenis_kelamin = VALUES(jenis_kelamin),
                                pendidikan_terakhir = VALUES(pendidikan_terakhir), 
                                nik_no_ktp = VALUES(nik_no_ktp),
                                alamat_rumah = VALUES(alamat_rumah), 
                                no_hp = VALUES(no_hp),
                                email = VALUES(email), 
                                mulai_aktif = VALUES(mulai_aktif),
                                legalitas_sertifikat = VALUES(legalitas_sertifikat), 
                                jenis_diklat_yg_diikuti = VALUES(jenis_diklat_yg_diikuti),
                                pendampingan = VALUES(pendampingan), 
                                kabupaten_kota = VALUES(kabupaten_kota);
                            
                            DROP TEMPORARY TABLE temporary_table;
                            
                            ");
                            
                           
                        } catch(\Illuminate\Database\QueryException $ex){ 
                            DtksErrorsImport::create([
                                'dtks_import_id' => $this->dtksimportId,
                                'row' => 0,
                                'attribute' => 'line: '.$ex->getLine().' [ProcessImportPsks]',
                                'values' => 'code: '.$ex->getCode(),
                                'errors' => $ex->getMessage()
                            ]);
                            DtksImport::find($this->dtksimportId)
                                    ->update(['status_import' => 'GAGAL IMPORT']);
                            return false;
                          }
                          
                        
                        
                    }
                
            } catch (\Exception  $e) {
                DtksErrorsImport::create([
                    'dtks_import_id' => $this->dtksimportId,
                    'row' => 0,
                    'attribute' => 'line: '.$e->getLine().' [ProcessImportPsks]',
                    'values' => 'code: '.$e->getCode(),
                    'errors' => $e->getMessage()
                ]);
                DtksImport::find($this->dtksimportId)
                        ->update(['status_import' => 'GAGAL IMPORT']);
                return false;
            }

            DtksImport::find($this->dtksimportId)
                        ->update(['status_import' => 'SUKSES IMPORT']);
    }
}
