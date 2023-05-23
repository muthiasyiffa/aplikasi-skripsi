<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new TotalLeasedImport(),
            new SO22Import(),
            new SO23Import(),
        ];
    }
    
}