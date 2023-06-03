<?php

namespace App\Exports;

use App\Models\TotalLeased;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TotalLeasedExport implements FromCollection, WithHeadings
{
    protected $totalLeased;

    public function __construct($totalLeased)
    {
        $this->totalLeased = $totalLeased;
    }

    /**
     * Retrieve data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->totalLeased->map(function ($item) {
            return [
                'Site ID Tenant' => $item->site_id_tenant,
                'Site Name' => $item->site_name,
                'Regional' => $item->regional,
                'Pulau' => $item->pulau,
                'Area' => $item->area,
                'Kat Jenis Order' => $item->kat_jenis_order,
                'SOW2' => $item->sow2,
                'Longitude' => $item->longitude,
                'Latitude' => $item->latitude
            ];
        });
    }

    /**
     * Set the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Site ID Tenant',
            'Site Name',
            'Regional',
            'Pulau',
            'Area',
            'Kat Jenis Order',
            'SOW2',
            'Longitude',
            'Latitude'
        ];
    }
}
