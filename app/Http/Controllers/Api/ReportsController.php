<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function masterReport(){
        $trees = Tree::with('project')->get();
        return response()->json($trees);
    }

    public function exportExcel(){
        // implement with maatwebsite/excel
        return response()->json(['message'=>'Excel export not implemented yet']);
    }

    public function exportPdf(){
        // implement PDF generation
        return response()->json(['message'=>'PDF export not implemented yet']);
    }
}
