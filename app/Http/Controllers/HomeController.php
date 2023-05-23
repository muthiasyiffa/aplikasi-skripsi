<?php

namespace App\Http\Controllers;

use App\Models\TotalLeased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Mengambil total jumlah tower
        $totalTowerCount = TotalLeased::count();
        
        $geojsonFiles = File::files(public_path('js/geojson/province'));

        // Mengambil data jumlah tower per pulau
        $towerCountsByPulau = TotalLeased::select('pulau', DB::raw('count(*) as total'))
            ->groupBy('pulau')
            ->get();

        // Mengambil data jumlah tower per pulau dan sow2
        $towerCountsByPulauSow = TotalLeased::select('pulau','sow2', DB::raw('count(*) as total'))
            ->groupBy('pulau','sow2')
            ->get();

        // Mengambil data jumlah tower per sow2
        $towerCountsBySow = TotalLeased::select('sow2', DB::raw('count(*) as total'))
            ->groupBy('sow2')
            ->get();

        // Mengambil data jumlah tower per sow2 berdasarkan area
        $towerCountsBySowArea = TotalLeased::select('sow2', 'area', DB::raw('count(*) as total'))
        ->where('sow2', 'COLO')
        ->groupBy('sow2', 'area')
        ->get();

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

        return view('home', [
            'geojsonFiles' => $geojsonFiles,
            'totalTowerCount' => $totalTowerCount,
            'towerCountsByPulau' => $towerCountsByPulau,
            'towerCountsByPulauSow' => $towerCountsByPulauSow,
            'towerCountsBySow' => $towerCountsBySow,
            'coloDataByArea' => $coloDataByArea
        ]);
    }
}
