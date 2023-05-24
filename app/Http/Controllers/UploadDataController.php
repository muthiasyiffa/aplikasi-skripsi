<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;
use App\Imports\SalesOperatorImport;
use App\Imports\TotalLeasedImport;

class UploadDataController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('upload-data');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file',
        ]);
        // Ambil file yang diupload
        $file = $request->file('excel_file');
        $import = new DataImport();
        Excel::import($import, $file);

        foreach ($import->getSheetNames() as $index => $sheetName) {
            if ($index == 0) {;
                Excel::import(new TotalLeasedImport($sheetName), $file);
            } else {
                Excel::import(new SalesOperatorImport($sheetName), $file);
            }
        }
        set_time_limit(0);

        return redirect()->back()->with('success', 'Data uploaded and updated successfully.');
    }
}
