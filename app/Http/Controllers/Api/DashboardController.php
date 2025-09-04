<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use App\Models\Project;
use App\Models\Plantation;

class DashboardController extends Controller
{
    public function index(){
        return response()->json([
            'projects' => Project::count(),
            'trees' => Tree::count(),
            'plantations' => Plantation::count(),
        ]);
    }
}
