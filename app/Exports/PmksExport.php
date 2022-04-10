<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomQuerySize;
use App\Models\User;
use App\Models\PmksData;



class PmksExport implements FromQuery,WithCustomQuerySize
{
    use Exportable;

    public function __construct()
    {
        
    }

    public function query()
    {
        return User::query();
        // return PmksData::query()->limit(10);

        // $query = $this->getQuery();

        // return $query;
    }

    public function querySize(): int
    {
        $size = $this->getQuery()->count();
        return $size;
    }

    public function getQuery()
    {
        return User::query();
    }

    
}
