<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tree;

class MapController extends Controller
{
    public function treesOnMap()
    {
        $trees = Tree::select('id','tree_name','latitude','longitude','ward','project_id')
            ->get()
            ->groupBy(fn($tree) => $tree->latitude.'_'.$tree->longitude);

        return response()->json($trees);
    }
}
