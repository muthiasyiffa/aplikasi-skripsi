<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use App\Models\TotalLeased;

HeadingRowFormatter::default('none');

class TotalLeasedImport implements ToModel, WithHeadingRow
{
    /**
     * @var int
     */
    public $headingRow = 1;

    public function model(array $row)
    {
        $data = [
            'site_id_tenant' => $row['SITE ID TENANT'],
            'site_name' => $row['SITE NAME'],
            'regional' => $row['REGIONAL'],
            'pulau' => $row['PULAU'],
            'area' => $row['AREA'],
            'kat_jenis_order' => $row['KAT JENIS ORDER'],
            'sow2' => $row['SOW2'],
        ];

        $searchCriteria = [
            'site_id_tenant' => $row['SITE ID TENANT'],
        ];

        TotalLeased::updateOrCreate($searchCriteria, $data);
    }
}