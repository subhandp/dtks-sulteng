<?php

namespace App\Imports;

use App\Models\PmksData;
use App\Models\DtksErrorsImport;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
// use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;




// WithCustomCsvSettings,
// WithHeadingRow,

// ToModel, WithBatchInserts,
class PmksDataImport implements OnEachRow, WithHeadingRow, WithValidation, WithEvents, WithChunkReading, ShouldQueue,  SkipsOnError, SkipsOnFailure
{
    use Importable,SkipsErrors, SkipsFailures;
    // use SkipsErrors, SkipsFailures;

    public $id;
    public $tahun_data;
    public $jenis_pmks;

    public function __construct($id, $tahun_data, $jenis_pmks)
    {
        $this->id = $id;
        $this->tahun_data = $tahun_data;
        $this->jenis_pmks = $jenis_pmks;
    }

    public function rules(): array
    {
        return [
            'id_dtks' => [
                'required',
                Rule::unique('pmks_data', 'iddtks')
            ],
            'provinsi' => ['required'],
            'kabupatenkota' => ['required'],
            'nama' => ['required']
        ];
    }

   
    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $totalRows = $event->getReader()->getTotalRows();

                if (filled($totalRows)) {
                    cache()->forever("total_rows_{$this->id}", array_values($totalRows)[0]);
                    cache()->forever("start_date_{$this->id}", now()->unix());
                }
            },
            AfterImport::class => function (AfterImport $event) {
                cache(["end_date_{$this->id}" => now()], now()->addMinute());
                cache()->forget("total_rows_{$this->id}");
                cache()->forget("start_date_{$this->id}");
                cache()->forget("current_row_{$this->id}");
            },
        ];
    }

    // public function model(array $row)
    // {
    //     PmksData::create([
    //         'iddtks' => $row[0], 
    //         'provinsi' => $row[1], 
    //         'kabupaten_kota' => $row[2], 
    //         'kecamatan' => $row[3], 
    //         'desa_kelurahan' => $row[4], 
    //         'alamat' => $row[5], 
    //         'dusun' => $row[6], 
    //         'rt' => $row[7], 
    //         'rw' => $row[8],
    //         'nomor_kk' => $row[9], 
    //         'nomor_nik' => $row[10], 
    //         'nama' => $row[11], 
    //         'tanggal_lahir' => $row[12], 
    //         'tempat_lahir' => $row[13], 
    //         'jenis_kelamin' => $row[14], 
    //         'nama_ibu_kandung' => $row[15],
    //         'hubungan_keluarga' => $row[16], 
    //         'tahun_data' => $this->tahun_data, 
    //         'jenis_pmks' => $this->jenis_pmks
    //     ]);
    // }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row      = array_map('trim', $row->toArray());
        cache()->forever("current_row_{$this->id}", $rowIndex);
        PmksData::create([ 
        'iddtks' => $row['id_dtks'], 
        'provinsi' => $row['provinsi'], 
        'kabupaten_kota' => $row['kabupatenkota'], 
        'kecamatan' => $row['kecamatan'], 
        'desa_kelurahan' => $row['desakeluarahan'], 
        'alamat' => $row['alamat'], 
        'dusun' => $row['dusun'], 
        'rt' => $row['rt'], 
        'rw' => $row['rw'],
        'nomor_kk' => $row['nomor_kk'], 
        'nomor_nik' => $row['nomor_nik'], 
        'nama' => $row['nama'], 
        'tanggal_lahir' => $row['tanggal_lahir'], 
        'tempat_lahir' => $row['tempat_lahir'], 
        'jenis_kelamin' => $row['jenis_kelamin'], 
        'nama_ibu_kandung' => $row['nama_ibu_kandung'],
        'hubungan_keluarga' => $row['hubungan_keluarga'], 
        'tahun_data' => $this->tahun_data, 
        'jenis_pmks' => $this->jenis_pmks
        ]);
    }

    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
        $failures = json_decode(json_encode($failures));
        foreach ($failures as $key => $val) {
            
            DtksErrorsImport::create([
                'dtks_import_id' => $this->id,
                'row' => $val->row,
                'attribute' => $val->attribute,
                'values' => 'value',
                'errors' => $val->errors[0]
            ]);
        }

    }

    public function onError(\Throwable $e)
    {
        $error = json_decode(json_encode($e));
        DtksErrorsImport::create([
            'dtks_import_id' => $this->id,
            'row' => 0,
            'attribute' => 'header error',
            'values' => 'line: '.$error->line,
            'errors' => substr($error->message, 0, 100)
        ]);
    }


}
