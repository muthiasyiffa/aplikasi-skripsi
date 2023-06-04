<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesOrderExport;

class SalesOrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return '';
    }

    /**
     * Handle the search request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $tahun)
    {
        $keywords = $request->input('keywords');

        session(['search_keyword' => $keywords]);

        // Query data SalesOrder berdasarkan keyword pencarian dan tahun
        $salesOrders = SalesOrder::whereIn('pid', $keywords)->where('tahun', $tahun)->get();

        if (!$keywords) {
            session()->forget('search_keyword');
        }

        // Mengembalikan view partial yang berisi tabel dengan hasil pencarian
        return view('partials.search_resultsSO', compact('salesOrders'));
    }

    public function exportToExcel($tahun)
    {
        // Mendapatkan nilai keyword dari session
        $keywords = session('search_keyword');

        // Cek apakah ada keyword pencarian atau tidak
        if ($keywords) {
            // Query untuk mendapatkan data SalesOrder sesuai dengan keyword pencarian dan tahun
            $salesOrders = SalesOrder::whereIn('pid', $keywords)->where('tahun', $tahun)->get();
        } else {
            // Query untuk mendapatkan semua data SalesOrder berdasarkan tahun
            $salesOrders = SalesOrder::where('tahun', $tahun)->get();
        }

        session()->forget('search_keyword');

        $filename = 'sales_orders_' . $tahun . '.xlsx';

        // Menggunakan SalesOrderExport dengan parameter $salesOrders
        return Excel::download(new SalesOrderExport($salesOrders), $filename);
    }


    public function show($tahun)
    {
        $currentDate = \Carbon\Carbon::now()->toDateString();
        $salesOrders = SalesOrder::where('tahun', $tahun)->get();
        $totalTowerCount = $salesOrders->count();

        foreach ($salesOrders as $salesOrder) {
            $spkDate = $salesOrder->spk_date;
            $woDate = $salesOrder->wo_date;
            $statusSite = $salesOrder->final_status_site;
            if ($spkDate) {
                if ($statusSite === 'DROP'){
                    $salesOrder->aging_spk_to_wo = 'DROP Site';
                } else if ($spkDate == '1900-01-01' || $spkDate == '1970-01-01') {
                    $salesOrder->aging_spk_to_wo = 'Not yet SPK';
                } else if ($woDate == '1900-01-01' || $woDate == '1970-01-01') {
                    $salesOrder->aging_spk_to_wo = 'Not yet WO';
                } else {
                    $aging = \Carbon\Carbon::parse($spkDate)->diffInDays($woDate);
                    $salesOrder->aging_spk_to_wo = (string) $aging;
                }
                $salesOrder->save();
            }
        }

        foreach ($salesOrders as $salesOrder) {
            $woDate = $salesOrder->wo_date;
            $rfiDate = $salesOrder->rfi_date;
            $statusSite = $salesOrder->final_status_site;
            if ($woDate) {
                if ($statusSite === 'DROP') {
                    $salesOrder->aging_wo_to_rfi = 'DROP Site';
                } else if ($woDate == '1900-01-01' || $woDate == '1970-01-01') {
                    $salesOrder->aging_wo_to_rfi = 'Not yet WO';
                } else if ($rfiDate == '1900-01-01'|| $rfiDate == '1970-01-01') {
                    $salesOrder->aging_wo_to_rfi = 'Not yet RFI';
                } else {
                    $aging = \Carbon\Carbon::parse($woDate)->diffInDays($rfiDate);
                    $salesOrder->aging_wo_to_rfi = (string) $aging;
                }
                $salesOrder->save();
            }
        }

        foreach ($salesOrders as $salesOrder) {
            $statusXL = $salesOrder->status_xl;
            $rfiDate = $salesOrder->rfi_date;
            if($statusXL === "RFI-NY BAUF" || $statusXL === "RFI-BAUF DONE") {
                if ($rfiDate) {
                    if ($rfiDate == '1900-01-01' || $rfiDate == '1970-01-01') {
                        $salesOrder->aging_rfi_to_bak = 'Not yet RFI';
                    } else {
                        $aging = \Carbon\Carbon::parse($rfiDate)->diffInDays($currentDate);
                        $salesOrder->aging_rfi_to_bak = (string) $aging;
                    }
                    $salesOrder->save();
                }
            }
        }

        // Mengambil data jumlah tower per pulau
        $towerCountsByPulau = SalesOrder::select('pulau', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('pulau')->get();
        // Mengambil data jumlah tower per sow2
        $towerCountsBySow = SalesOrder::select('sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('sow2')->get();
        // Mengambil data jumlah tower per pulau dan sow2
        $towerCountsByPulauSow = SalesOrder::select('pulau', 'sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('pulau', 'sow2')->get();
        // Mengambil data jumlah tower per sow2 "COLO" berdasarkan area
        $towerCountsByAreaSow = SalesOrder::select('area', 'sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->where('sow2', 'COLO')->groupBy('area', 'sow2')->get();
        // Mengambil data jumlah tower per sow2 "B2S" berdasarkan area
        $towerCountsByAreaB2S = SalesOrder::select('area', 'sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->where('sow2', 'B2S')->groupBy('area', 'sow2')->get();
        // Mengambil data jumlah tower per sow2 "COLO" berdasarkan kat_tower
        $towerCountsBySowKatTower = SalesOrder::select('kat_tower', 'sow2', \DB::raw('count(*) as total'))->where('sow2', 'COLO')->where('tahun', $tahun)->groupBy('sow2', 'kat_tower')->get();
        // Mengambil data jumlah tower per sow2 "COLO" berdasarkan kat_tower
        $towerCountsBySowExisting = SalesOrder::select('tenant_existing', 'sow2', \DB::raw('count(*) as total'))->where('sow2', 'COLO')->where('tahun', $tahun)->groupBy('sow2', 'tenant_existing')->get();
        // Mengambil data jumlah tower per demography
        $towerCountsByDemography = SalesOrder::select('demografi', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('demografi')->get();
        // Mengambil data jumlah tower per sow2 berdasarkan area
        $towerCountsBySowArea = SalesOrder::select('sow2', 'area', \DB::raw('count(*) as total'))->whereIn('sow2', ['COLO'])->whereIn('kat_tower', ['Titan', 'Edelweiss 1A', 'Edelweiss 1B', 'Edelweiss 2', 'Edelweiss 3', 'UNO', 'Akuisisi'])->where('tahun', $tahun)->groupBy('sow2', 'area')->get();
        // Mengambil data jumlah tower per final status site
        $towerCountsByStatus = SalesOrder::select('final_status_site', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('final_status_site')->get();
        // Mengambil data progress RFI
        $towerCountsByStatusRFI = SalesOrder::select('status_xl', 'final_status_site', \DB::raw('count(*) as total'))->where('tahun', $tahun)->where('final_status_site', 'RFI')->groupBy('final_status_site', 'status_xl')->get();
        // Mengambil data jumlah tower per statusXL
        $towerCountsByStatusXL = SalesOrder::select('status_xl', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('status_xl')->get();

        $geojsonFiles = \File::files(public_path('js/geojson/province'));
        
        // Menyiapkan array untuk menyimpan data tower colo per kat_tower
        $coloDataByKatTower = [];

        foreach ($towerCountsBySowKatTower as $towerCount) {
            $katTower = $towerCount->kat_tower;
            $coloCount = $towerCount->total;

            if (!isset($coloDataByKatTower[$katTower])) {
                $coloDataByKatTower[$katTower] = $coloCount;
            } else {
                $coloDataByKatTower[$katTower] += $coloCount;
            }
        }

        // Menyiapkan array untuk menyimpan data tower colo per tenant_existing
        $coloDataByTenantExisting = [];

        foreach ($towerCountsBySowExisting as $towerCount) {
            $tenantExisting = $towerCount->tenant_existing;
            $coloCount = $towerCount->total;

            if (!isset($coloDataByTenantExisting[$tenantExisting])) {
                $coloDataByTenantExisting[$tenantExisting] = $coloCount;
            } else {
                $coloDataByTenantExisting[$tenantExisting] += $coloCount;
            }
        }

        // Menyiapkan array untuk menyimpan data tower colo per area
        $coloDataByArea = [];

        foreach ($towerCountsBySowArea as $towerCount) {
            $area = $towerCount->area;
            $coloCount = $towerCount->total;

            if (!isset($coloDataByArea[$area])) {
                $coloDataByArea[$area] = $coloCount;
            } else {
                $coloDataByArea[$area] += $coloCount;
            }
        }

        // Menghitung total tower COLO
        $coloTotal = array_reduce(array_values($coloDataByKatTower), function ($total, $count) {
            return $total + $count;
        }, 0);

        // Mengkategorikan tower sebagai Tower Akuisisi atau Tower B2S Mitratel
        $akuisisiTowers = ['Titan', 'Edelweiss 1A', 'Edelweiss 1B', 'Edelweiss 2', 'Edelweiss 3', 'UNO', 'Akuisisi'];

        $akuisisiData = [];

        // Memisahkan data tower berdasarkan kategori
        foreach ($coloDataByKatTower as $katTower => $count) {
            if (in_array($katTower, $akuisisiTowers)) {
                $akuisisiData[$katTower] = $count;
            }
        }

        // Menghitung total tower akuisisi
        $akuisisiTotal = array_reduce(array_values($akuisisiData), function ($total, $count) {
            return $total + $count;
        }, 0);

        return view('sales-order', [
            'tahun' => $tahun,
            'geojsonFiles' => $geojsonFiles,
            'totalTowerCount' => $totalTowerCount,
            'towerCountsByPulau' => $towerCountsByPulau,
            'towerCountsByPulauSow' => $towerCountsByPulauSow,
            'towerCountsByAreaSow' => $towerCountsByAreaSow,
            'towerCountsByAreaB2S' => $towerCountsByAreaB2S,
            'towerCountsBySow' => $towerCountsBySow,
            'coloDataByKatTower' => $coloDataByKatTower,
            'coloTotal' => $coloTotal,
            'akuisisiTotal' => $akuisisiTotal,
            'coloDataByTenantExisting' => $coloDataByTenantExisting,
            'coloDataByArea' => $coloDataByArea,
            'towerCountsByDemography' => $towerCountsByDemography,
            'towerCountsByStatus' => $towerCountsByStatus,
            'towerCountsByStatusRFI' => $towerCountsByStatusRFI,
            'towerCountsByStatusXL' => $towerCountsByStatusXL,
            'salesOrders' => $salesOrders
        ]);
    }
}
