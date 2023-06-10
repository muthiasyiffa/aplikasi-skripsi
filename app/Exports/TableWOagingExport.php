<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TableWOagingExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $columns = ['pid', 'site_id_tenant', 'site_name', 'sow2', 'status_xl', 'wo_date', 'rfi_date', 'aging_wo_to_rfi'];

    public function __construct($selectedCategory, $selectedStatus, $selectedSOW, $tahun)
    {
        $this->selectedCategory = $selectedCategory;
        $this->selectedStatus = $selectedStatus;
        $this->selectedSOW = $selectedSOW;
        $this->tahun = $tahun;
    }

    public function query()
    {
        $query = SalesOrder::select($this->columns)->where('tahun', $this->tahun);

        if ($this->selectedCategory !== 'all') {
            $query->where(function ($query) {
                if ($this->selectedCategory === 'Low Attention') {
                    if ($this->selectedSOW === 'B2S' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'B2S')->where('aging_wo_to_rfi', '<=', 60);
                        });
                    }
                    if ($this->selectedSOW === 'COLO' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'COLO')->where('aging_wo_to_rfi', '<=', 20);
                        });
                    }
                } elseif ($this->selectedCategory === 'Attention') {
                    if ($this->selectedSOW === 'B2S' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'B2S')->whereBetween('aging_wo_to_rfi', [61, 85]);
                        });
                    }
                    if ($this->selectedSOW === 'COLO' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'COLO')->whereBetween('aging_wo_to_rfi', [21, 40]);
                        });
                    }
                } elseif ($this->selectedCategory === 'Need More Attention') {
                    if ($this->selectedSOW === 'B2S' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'B2S')
                                ->where(function ($query) {
                                    $query->where('aging_wo_to_rfi', '>', 85)
                                        ->orWhere('aging_wo_to_rfi', 'Not yet RFI')
                                        ->orWhere('aging_wo_to_rfi', 'Not yet WO');
                                });
                        });
                    }
                    if ($this->selectedSOW === 'COLO' || $this->selectedSOW === 'all') {
                        $query->orWhere(function ($query) {
                            $query->where('tahun', $this->tahun)->where('sow2', 'COLO')
                                ->where(function ($query) {
                                    $query->where('aging_wo_to_rfi', '>', 40)
                                        ->orWhere('aging_wo_to_rfi', 'Not yet RFI')
                                        ->orWhere('aging_wo_to_rfi', 'Not yet WO');
                                });
                        });
                    }
                }
            });
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
            'SOW2',
            'Status XL',
            'WO Date',
            'RFI Date',
            'Aging WO to RFI',
        ];
    }
}
