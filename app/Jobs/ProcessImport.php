<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use  App\Models\DtksImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;  
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PmksDataImport;
use App\Models\DtksErrorsImport;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $request;
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
        
            foreach($this->request as $uploads){
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
    
                File::moveDirectory(storage_path('app/'.$upload['filepath']), storage_path('app/'.$finalpath));
            }
          

            $path = storage_path('app/'.$finalpath).'/'.$upload['filename'];
            
            $import = new PmksDataImport($dtksimport->id, 2022, 'jenis_pmks');
            
            $import->import($path);
            // return true;
            // try {
            //     $import->import($path);
            // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //     $dtkserror = new DtksErrorsImport();
            //     $dtkserror->dtks_import_id = 1;
            //     $dtkserror->row =0;
            //     $dtkserror->attribute = 'error';
            //     $dtkserror->values = 'error';
            //     $dtkserror->errors = 'error';
            //     $dtkserror->save();
            // }

        //     foreach ($import->failures() as $failure) {
        //         $dtkserror = new DtksErrorsImport();
        //         $dtkserror->dtks_import_id = 1;
        //         $dtkserror->row =0;
        //         $dtkserror->attribute = 'failure';
        //         $dtkserror->values = 'failure';
        //         $dtkserror->errors = 'failure';
        //         $dtkserror->save();
        //    }

        //    foreach ($import->errors() as $error) {
        //     $dtkserror = new DtksErrorsImport();
        //     $dtkserror->dtks_import_id = 1;
        //     $dtkserror->row =0;
        //     $dtkserror->attribute = 'error';
        //     $dtkserror->values = 'error';
        //     $dtkserror->errors = 'error';
        //     $dtkserror->save();
            
        //     }

    
    }
}
