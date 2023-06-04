<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesOrderExport implements FromCollection, WithHeadings
{
    protected $salesOrder;

    public function __construct($salesOrder)
    {
        $this->salesOrder = $salesOrder;
    }

    /**
     * Retrieve data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->salesOrder->map(function ($item) {
            return [
                'PID' => $item->pid,
                'Site ID Tenant' => $item->site_id_tenant,
                'Site Name' => $item->site_name,
                'Regional' => $item->regional,
                'Pulau' => $item->pulau,
                'Area' => $item->area,
                'SOW2' => $item->sow2,
                'Kategori Tower' => $item->kat_tower,
                'Demografi' => $item->demografi,
                'Tenant Existing' => $item->tenant_existing,
                'Status LMS' => $item->status_lms,
                'Status XL' => $item->status_xl,
                'Final Status Site' => $item->final_status_site,
                'SPK Date' => $item->spk_date,
                'WO Date' => $item->wo_date,
                'RFI Date' => $item->rfi_date,
                'Aging SPK to WO' => $item->aging_spk_to_wo,
                'Aging WO to RFI' => $item->aging_wo_to_rfi,
                'Aging RFI to BAK' => $item->aging_rfi_to_bak,
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
            'PID',
            'Site ID Tenant',
            'Site Name',
            'Regional',
            'Pulau',
            'Area',
            'SOW2',
            'Kategori Tower',
            'Demografi',
            'Tenant Existing',
            'Status LMS',
            'Status XL',
            'Final Status Site',
            'SPK Date',
            'WO Date',
            'RFI Date',
            'Aging SPK to WO',
            'Aging WO to RFI',
            'Aging RFI to BAK',
        ];
    }
}
