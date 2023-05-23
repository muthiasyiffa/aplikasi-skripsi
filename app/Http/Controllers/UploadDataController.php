<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;
use App\Imports\TotalLeasedImport;
use App\Imports\SO22Import;
use App\Imports\SO23Import;

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
        
        Excel::import(new DataImport(), $file);
        

        return redirect()->back()->with('success', 'Data uploaded and updated successfully.');
    }


}
