<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreeRequest;
use App\Models\Tree;
use App\Models\Photo;
use App\Http\Resources\TreeResource;
use Illuminate\Http\Request;

class TreesController extends Controller
{
    public function index(Request $r){
        $q = Tree::with('photos');
        if ($r->project_id) $q->where('project_id',$r->project_id);
        if ($r->q) $q->where('common_name','like','%'.$r->q.'%');
        return TreeResource::collection($q->paginate(25));
    }

    public function store(StoreTreeRequest $r){
        $data = $r->validated();
        $data['user_id'] = $r->user()->id;
        $tree = Tree::create($data);

        if ($r->hasFile('photos')){
            foreach ($r->file('photos') as $file){
                $path = $file->store('trees','public');
                $tree->photos()->create(['path'=>$path]);
            }
        }
        return new TreeResource($tree->load('photos'));
    }

    public function show($id){
        return new TreeResource(Tree::with('photos')->findOrFail($id));
    }

    public function update(Request $r,$id){
        $tree = Tree::findOrFail($id);
        $tree->update($r->all());
        return new TreeResource($tree->load('photos'));
    }

    public function destroy($id){
        $tree = Tree::findOrFail($id);
        $tree->delete();
        return response()->json(['message'=>'deleted']);
    }

    public function exportKml(){
        $trees = Tree::whereNotNull('latitude')->get();
        $kml = '<?xml version="1.0" encoding="UTF-8"?>\n<kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
        foreach($trees as $t){
            $photo = $t->photos->first();
            $photoUrl = $photo ? asset('storage/'.$photo->path) : '';
            $kml .= "<Placemark><name>".htmlspecialchars($t->common_name)."</name>";
            $kml .= "<description>".htmlspecialchars($t->remark).' <img src="'.$photoUrl.'" />'."</description>";
            $kml .= "<Point><coordinates>{$t->longitude},{$t->latitude},0</coordinates></Point></Placemark>";
        }
        $kml .= '</Document></kml>';
        return response($kml,200,['Content-Type'=>'application/vnd.google-earth.kml+xml']);
    }
}
