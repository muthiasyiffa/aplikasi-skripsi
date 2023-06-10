<?php

namespace App\Imports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

HeadingRowFormatter::default('none');

class SalesOperatorImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    /**
     * @var int
     */
    public $headingRow = 1;
    public $sheetName;
    public $year;

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
        $this->year = (int) str_replace([' ', 'detail', 'data'], '', strtolower($sheetName));
    }

    public function model(array $row)
    {
        // Menyimpan nilai "#N/A" dalam variabel $nullValue
        $nullValue = '#N/A';

        // Mendeskripsikan nilai "#N/A" menjadi NULL
        $tenantExisting = ($row['Tenant Existing'] === $nullValue || $row['Tenant Existing'] == null) ? null : $row['Tenant Existing'];
        $statusXL = ($row['Status XL'] === $nullValue || $row['Status XL'] == null) ? null : $row['Status XL'];
        $katTower = ($row['KATEGORI TOWER'] === $nullValue || $row['KATEGORI TOWER'] == null) ? null : $row['KATEGORI TOWER'];
        $spkDate = null;
        if ($row['SPK DATE'] === '#N/A') {
            $spkDate = '1900-01-01';
        } else if (is_numeric($row['SPK DATE'])) {
            $spkDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['SPK DATE'])->format('Y-m-d');
        } else if (is_string($row['SPK DATE'])) {
            $dateTime = \DateTime::createFromFormat('d/m/Y', $row['SPK DATE']);
            if ($dateTime !== false) {
                $spkDate = $dateTime->format('Y-m-d');
            } else {
                echo "Invalid date format";
            }
        }

        $woDate = null;
        if ($row['WO DATE'] === null) {
            $woDate = '1900-01-01';
        } else if (is_numeric($row['WO DATE'])) {
            $woDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['WO DATE'])->format('Y-m-d');
        } else if (is_string($row['WO DATE'])) {
            $dateTime = \DateTime::createFromFormat('d/m/Y', $row['WO DATE']);
            if ($dateTime !== false) {
                $woDate = $dateTime->format('Y-m-d');
            } else {
                echo "Invalid date format";
            }
        }

        $rfiDate = null;
        if ($row['RFI DATE'] === null || $row['RFI DATE'] === '1/0/1900') {
            $rfiDate = '1900-01-01';
        } else if (is_numeric($row['RFI DATE'])) {
            $rfiDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['RFI DATE'])->format('Y-m-d');
        } else if (is_string($row['RFI DATE'])) {
            $dateTime = \DateTime::createFromFormat('d/m/Y', $row['RFI DATE']);
            if ($dateTime !== false) {
                $rfiDate = $dateTime->format('Y-m-d');
            } else {
                echo "Invalid date format";
            }
        }

        $data = [
            'tahun' => $this->year,
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
            'status_lms' => $row['Status LMS'],
            'status_xl' => $statusXL,
            'final_status_site' => $row['FINAL STATUS SITE'],
            'spk_date' => $spkDate,
            'wo_date' => $woDate,
            'rfi_date' => $rfiDate,
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
                case 'ESR Ready':
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
                    $data['status_xl'] = 'BAK Compeleted';
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

        SalesOrder::updateOrCreate($searchCriteria, $data);
    }

    public function sheets(): array
    {
        return [
            $this->sheetName => $this,
        ];
    }
}
