<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableRFIagingExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $columns = ['pid', 'site_id_tenant', 'site_name', 'status_xl', 'rfi_date', 'aging_rfi_to_bak'];

    public function __construct($selectedCategory, $selectedStatus, $tahun)
    {
        $this->selectedCategory = $selectedCategory;
        $this->selectedStatus = $selectedStatus;
        $this->tahun = $tahun;
    }

    public function query()
    {
        $query = SalesOrder::select($this->columns)->where('tahun', $this->tahun)->whereIn('status_xl', ['RFI-NY BAUF', 'RFI-BAUF DONE']);

        if ($this->selectedCategory !== 'all') {
            if ($this->selectedCategory === 'Low Attention') {
                $query->where('aging_rfi_to_bak', '<=', 14);
            } elseif ($this->selectedCategory === 'Attention') {
                $query->whereBetween('aging_rfi_to_bak', [15, 25]);
            } elseif ($this->selectedCategory === 'Need More Attention') {
                $query->where(function ($q) {
                    $q->where('aging_rfi_to_bak', '>', 25)
                      ->orWhere(function ($q2) {
                          $q2->where('aging_rfi_to_bak', 'Not yet RFI')
                             ->where('tahun', $this->tahun);
                      });
                });
            }
        }
    
        if ($this->selectedStatus !== 'all') {
            $query->where('status_xl', $this->selectedStatus);
        }
    
        return $query;
    }

    public function headings(): array
    {
        return [
            'PID',
            'Site ID Tenant',
            'Site Name',
            'Status XL',
            'RFI Date',
            'Aging RFI to BAK',
        ];
    }
}
