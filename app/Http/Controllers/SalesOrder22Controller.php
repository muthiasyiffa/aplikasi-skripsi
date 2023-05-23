<?php

namespace App\Http\Controllers;

use App\Models\SO22;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SalesOrder22Controller extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalTowerCount = SO22::count();
        
        $geojsonFiles = File::files(public_path('js/geojson/province'));

        // Mengambil data jumlah tower per pulau
        $towerCountsByPulau = SO22::select('pulau', DB::raw('count(*) as total'))
            ->groupBy('pulau')
            ->get();

        // Mengambil data jumlah tower per pulau dan sow2
        $towerCountsByPulauSow = SO22::select('pulau','sow2', DB::raw('count(*) as total'))
            ->groupBy('pulau','sow2')
            ->get();

        // Mengambil data jumlah tower per sow2
        $towerCountsBySow = SO22::select('sow2', DB::raw('count(*) as total'))
            ->groupBy('sow2')
            ->get();

        // Mengambil data jumlah tower per sow2 berdasarkan kat_tower
        $towerCountsBySowKatTower = SO22::select('sow2', 'kat_tower', DB::raw('count(*) as total'))
        ->where('sow2', 'COLO')
        ->groupBy('sow2', 'kat_tower')
        ->get();

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

        return view('salesorder22', [
            'geojsonFiles' => $geojsonFiles,
            'totalTowerCount' => $totalTowerCount,
            'towerCountsByPulau' => $towerCountsByPulau,
            'towerCountsByPulauSow' => $towerCountsByPulauSow,
            'towerCountsBySow' => $towerCountsBySow,
            'coloDataByKatTower' => $coloDataByKatTower
        ]);
    }
}
