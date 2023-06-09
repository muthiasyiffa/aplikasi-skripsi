<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableSPKagingExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $columns = ['pid', 'site_id_tenant', 'site_name', 'status_xl', 'spk_date', 'wo_date', 'aging_spk_to_wo'];

    public function __construct($selectedCategory, $selectedStatus, $tahun)
    {
        $this->selectedCategory = $selectedCategory;
        $this->selectedStatus = $selectedStatus;
        $this->tahun = $tahun;
    }

    public function query()
    {
        $query = SalesOrder::select($this->columns)->where('tahun', $this->tahun);

        if ($this->selectedCategory !== 'all') {
            if ($this->selectedCategory === 'Low Attention') {
                $query->where('aging_spk_to_wo', '<=', 4);
            } elseif ($this->selectedCategory === 'Attention') {
                $query->whereBetween('aging_spk_to_wo', [5, 7]);
            } elseif ($this->selectedCategory === 'Need More Attention') {
                $query->where('aging_spk_to_wo', '>', 7);
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
            'SPK Date',
            'WO Date',
            'Aging SPK to WO',
        ];
    }
}
