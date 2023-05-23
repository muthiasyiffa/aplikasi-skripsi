<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Carbon\Carbon;
use App\Models\SO22;

HeadingRowFormatter::default('none');

class SO22Import implements ToModel, WithHeadingRow
{
    /**
     * @var int
     */
    public $headingRow = 1;

    public function model(array $row)
    {
        // Menyimpan nilai "#N/A" dalam variabel $nullValue
        $nullValue = '#N/A';

        // Mendeskripsikan nilai "#N/A" menjadi NULL
        $tenantExisting = ($row['Tenant Existing'] === $nullValue) ? null : $row['Tenant Existing'];
        $statusXL = ($row['Status XL'] === $nullValue) ? null : $row['Status XL'];
        $katTower = ($row['KATEGORI TOWER'] === $nullValue) ? null : $row['KATEGORI TOWER'];
        $rfiDate = ($row['RFI DATE'] === $nullValue) ? null : \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['RFI DATE'])->format('Y-m-d');

        $data = [
            'pid' => $row['PROJECT ID'],
            'site_id_tenant' => $row['SITE ID TENANT'],
            'site_name' => $row['SITE NAME'],
            'regional' => $row['REGIONAL'],
            'pulau' => $row['PULAU'],
            'area' => $row['AREA'],
            'sow2' => $row['SOW2'],
            'kat_tower' => $katTower,
            'demografi' => $row['DEMOGRAFI'],
            'tenant_existing' => $tenantExisting,
            'final_status_site' => $row['FINAL STATUS SITE'],
            'status_xl' => $statusXL,
            'status_lms' => $row['Status LMS'],
            'rfi_date' => $rfiDate,
            'aging_rfi_to_bak' => $this->calculateAging($rfiDate),
        ];

        $searchCriteria = [
            'pid' => $row['PROJECT ID'],
            'site_id_tenant' => $row['SITE ID TENANT'],
        ];

        if ($row['Status XL'] !== null || $row['FINAL STATUS SITE'] !== null) {
            // Tidak ada pemetaan yang dilakukan
        } else {
            switch ($row['Status LMS']) {
                case 'Accepted by TP':
                    $data['status_xl'] = 'On Going';
                    $data['final_status_site'] = 'On Going';
                    break;
                case 'ESR Created':
                    $data['status_xl'] = 'On Going';
                    $data['final_status_site'] = 'On Going';
                    break;
                case 'ESR Submitted':
                    $data['status_xl'] = 'On Going';
                    $data['final_status_site'] = 'On Going';
                    break;
                case 'STO Announced':
                    $data['status_xl'] = 'On Going';
                    $data['final_status_site'] = 'On Going';
                    break;
                case 'RFI Approved':
                    $data['status_xl'] = 'RFI-NY BAUF';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Submitted':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Technical Reviewed':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Technical Rejected':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Technical Approved':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Commercial Reviewed':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'BAK Commercial Rejected':
                    $data['status_xl'] = 'RFI-BAUF DONE';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'Completed':
                    $data['status_xl'] = 'BAK Completed';
                    $data['final_status_site'] = 'RFI';
                    break;
                case 'SPK Returned':
                    $data['status_xl'] = 'DROP';
                    $data['final_status_site'] = 'DROP';
                    break;
                case 'SSR Deleted':
                    $data['status_xl'] = 'DROP';
                    $data['final_status_site'] = 'DROP';
                    break;
                case '#N/A':
                    $data['status_xl'] = 'DROP';
                    $data['final_status_site'] = 'DROP';
                    break;
            }
        }

        SO22::updateOrCreate($searchCriteria, $data);
    }

    private function calculateAging($rfiDate)
    {
        $currentDate = Carbon::now();
        $rfiDate = Carbon::parse($rfiDate);

        // Menghitung selisih hari antara rfi_date dengan currentDate
        $aging = $currentDate->diffInDays($rfiDate);

        return $aging;
    }
}