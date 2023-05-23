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
        // Mengambil data jumlah tower per sow2 berdasarkan kat_tower
        $towerCountsBySowKatTower = SalesOrder::select('kat_tower', 'sow2', \DB::raw('count(*) as total'))->where('sow2', 'COLO')->where('tahun', $tahun)->groupBy('sow2', 'kat_tower')->get();

        $geojsonFiles = \File::files(public_path('js/geojson/province'));

        // Menyiapkan array untuk menyimpan data tower colo per area
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

        return view('sales-order', [
            'geojsonFiles' => $geojsonFiles,
            'totalTowerCount' => $totalTowerCount,
            'towerCountsByPulau' => $towerCountsByPulau,
            'towerCountsByPulauSow' => $towerCountsByPulauSow,
            'towerCountsBySow' => $towerCountsBySow,
            'coloDataByKatTower' => $coloDataByKatTower
        ]);
    }
}
