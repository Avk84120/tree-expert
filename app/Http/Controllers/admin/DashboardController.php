<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use App\Models\Project;
use App\Models\Plantation;

class DashboardController extends Controller
{
    public function show()
{
    // echo "hiiii";die();
    $stats = [
        'projects'    => Project::count(),
        'trees'       => Tree::count(),
        'plantations' => Plantation::count(),
    ];

    return view('dashboard.index', compact('stats'));
}
}
