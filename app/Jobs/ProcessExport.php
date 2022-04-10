<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use App\Core\XLSXWriter;

class DtksCsvProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pmks;
    public $endStatus;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pmks,$endStatus)
    {
        $this->pmks = $pmks;
        $this->endStatus = $endStatus;

    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $header = [
            "iddtks",
            "provinsi",
            "kabupatenkota",
            "kecamatan",
            "desakelurahan",
            "alamat",
            "dusun",
            "rt",
            "rw",
            "nomorkk",
            "nomornik",
            "nama",
            "tanggallahir",
            "tempatlahir",
            "jeniskelamin",
            "namaibukandung",
            "hubungankeluarga"
        ];

        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', array('c1'=>'string','c2'=>'string','c3'=>'string','c4'=>'string') );//optional
        
            $s1 =  $this->pmks->iddtks;
            $s2 =  $this->pmks->provinsi;
            $s3 =  $this->pmks->kabupaten_kota;
            $s4 =  $this->pmks->kecamatan;
            $s5 =  $this->pmks->alamat;
            $s6 =  $this->pmks->dusun;
            $s7 =  $this->pmks->rt;
            $s8 =  $this->pmks->rw;
            $s9 =  $this->pmks->nomor_kk;
            $s10 = $this->pmks->nomor_nik;
            $s11 = $this->pmks->nama;
            $s12 = $this->pmks->tanggal_lahir;
            $s13 = $this->pmks->tempat_lahir;
            $s14 = $this->pmks->jenis_kelamin;
            $s15 = $this->pmks->nama_ibu_kandung;
            $s16 = $this->pmks->hubungan_keluarga;
            $s17 = $this->pmks->tahun_data;
            $s18 = $this->pmks->jenis_pmks;
            $writer->writeSheetRow('Sheet1', array($s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $s9, $s10, $s11, $s12, $s13, $s14, $s15, $s16, $s17, $s18) );
        
            if($this->endStatus == TRUE)
                $writer->writeToFile('pmks-data.xlsx');

    }
}
