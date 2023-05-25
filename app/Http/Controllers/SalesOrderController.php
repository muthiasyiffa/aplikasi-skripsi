<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

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

    public function show($tahun)
    {
        $totalTowerCount = SalesOrder::where('tahun', $tahun)->count();

        // Mengambil data jumlah tower per pulau
        $towerCountsByPulau = SalesOrder::select('pulau', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('pulau')->get();
        // Mengambil data jumlah tower per sow2
        $towerCountsBySow = SalesOrder::select('sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('sow2')->get();
        // Mengambil data jumlah tower per pulau dan sow2
        $towerCountsByPulauSow = SalesOrder::select('pulau', 'sow2', \DB::raw('count(*) as total'))->where('tahun', $tahun)->groupBy('pulau', 'sow2')->get();
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

        return view('sales-order', [
            'tahun' => $tahun,
            'geojsonFiles' => $geojsonFiles,
            'totalTowerCount' => $totalTowerCount,
            'towerCountsByPulau' => $towerCountsByPulau,
            'towerCountsByPulauSow' => $towerCountsByPulauSow,
            'towerCountsBySow' => $towerCountsBySow,
            'coloDataByKatTower' => $coloDataByKatTower,
            'coloDataByTenantExisting' => $coloDataByTenantExisting,
            'coloDataByArea' => $coloDataByArea,
            'towerCountsByDemography' => $towerCountsByDemography,
            'towerCountsByStatus' => $towerCountsByStatus
        ]);
    }
}
