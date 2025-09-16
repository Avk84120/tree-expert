<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTreeRequest;
use App\Models\Tree;
use App\Models\Photo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\TreeResource;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel; 
use App\Imports\TreesImport;
use App\Exports\TreesExport;
use Rap2hpoutre\FastExcel\FastExcel;

// use Maatwebsite\Excel\Facades\Excel;

class TreesController extends Controller
{
    public function index(Request $r)
    {
        $q = Tree::with('photos');

        // Filter by project
        if ($r->project_id) {
            $q->where('project_id', $r->project_id);
        }

        // Filter by ward
        if ($r->ward) {
            $q->where('ward', $r->ward);
        }

        // Filter by officer/user
        if ($r->user_id) {
            $q->where('user_id', $r->user_id);
        }

        // Filter by tree name (partial match)
        if ($r->tree_name) {
            $q->where('tree_name', 'like', '%' . $r->tree_name . '%');
        }

        // Filter by condition
        if ($r->condition) {
            $q->where('condition', $r->condition);
        }

        // --- Tree Search by name or ID ---
    if ($r->search) {
        $q->where(function($query) use ($r) {
            $query->where('tree_name', 'like', '%' . $r->search . '%')
                  ->orWhere('id', $r->search);
        });
    }

        // Add more filters as needed...

        return TreeResource::collection($q->paginate(25));
    }

    // public function store(StoreTreeRequest $r){
    //     $data = $r->validated();
    //     $data['user_id'] = $r->user()->id;
    //     $tree = Tree::create($data);

    //     if ($r->hasFile('photos')){
    //         foreach ($r->file('photos') as $file){
    //             $path = $file->store('trees','public');
    //             $tree->photos()->create(['path'=>$path]);
    //         }
    //     }
    //     return new TreeResource($tree->load('photos'));
    // }
public function store(Request $request)
    {
        $data = $request->validate([
            'project_id'          => 'required|exists:projects,id',
            'user_id'             => 'nullable|exists:users,id',
            'tree_name_id'        => 'nullable|exists:tree_names,id',
            'ward'                => 'nullable|string',
            'tree_no'             => 'nullable|string',
            'tree_name'           => 'required|string',
            'scientific_name'     => 'nullable|string',
            'family'              => 'nullable|string',
            'girth_cm'            => 'nullable|numeric',
            'height_m'            => 'nullable|numeric',
            'canopy_m'            => 'nullable|numeric',
            'age'                 => 'nullable|integer',
            'condition'           => 'nullable|in:Poor,Medium,Good,Diseased,Dead',
            'address'             => 'nullable|string',
            'landmark'            => 'nullable|string',
            'ownership' => 'nullable|in:Private,Government,Park,Road,Open Space,Riverside',
            'concern_person_name' => 'nullable|string',
            'remark'              => 'nullable|string',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'accuracy'            => 'nullable|string',
            'continue'            => 'nullable|string',
            'photo'               => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trees', 'public');
        }

        $tree = Tree::create($data);

        return response()->json([
            'message' => 'Tree added successfully',
            'data'    => $tree
        ], 201);
    }


    public function show($id){
        return new TreeResource(Tree::with('photos')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $tree = Tree::findOrFail($id);

        $data = $request->validate([
            'project_id'          => 'sometimes|exists:projects,id',
            'user_id'             => 'nullable|exists:users,id',
            'tree_name_id'        => 'nullable|exists:tree_names,id',
            'ward'                => 'nullable|string',
            'tree_no'             => 'nullable|string',
            'tree_name'           => 'sometimes|string',
            'scientific_name'     => 'nullable|string',
            'family'              => 'nullable|string',
            'girth_cm'            => 'nullable|numeric',
            'height_m'            => 'nullable|numeric',
            'canopy_m'            => 'nullable|numeric',
            'age'                 => 'nullable|integer',
            'condition'           => 'nullable|in:Poor,Medium,Good,Diseased,Dead',
            'address'             => 'nullable|string',
            'landmark'            => 'nullable|string',
            'ownership'           => 'nullable|in:Private,Government,Park,Road,Open Space,Riverside',
            'concern_person_name' => 'nullable|string',
            'remark'              => 'nullable|string',
            'latitude'            => 'nullable|numeric',
            'longitude'           => 'nullable|numeric',
            'accuracy'            => 'nullable|string',
            'continue'            => 'nullable|string',
            'photo'               => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('trees', 'public');
        }

        $tree->update($data);

        return new TreeResource($tree->fresh()->load('photos'));
    }

    public function destroy($id){
        $tree = Tree::findOrFail($id);
        $tree->delete();
        return response()->json(['message'=>'deleted']);
    }

public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');

        try {
            $collection = (new FastExcel)->import($file);

            foreach ($collection as $row) {
                // Map the row data to your Tree model attributes.
                // Ensure the column names in your Excel file match the keys used here.
                Tree::create([
                    'project_id'          => $row['project_id'] ?? null,
                    'user_id'             => $row['user_id'] ?? $request->user()->id,
                    'tree_name_id'        => $row['tree_name_id'] ?? null,
                    'ward'                => $row['ward'] ?? null,
                    'tree_no'             => $row['tree_no'] ?? null,
                    'tree_name'           => $row['tree_name'] ?? 'Unnamed Tree',
                    'scientific_name'     => $row['scientific_name'] ?? null,
                    'family'              => $row['family'] ?? null,
                    'girth_cm'            => $row['girth_cm'] ?? null,
                    'height_m'            => $row['height_m'] ?? null,
                    'canopy_m'            => $row['canopy_m'] ?? null,
                    'age'                 => $row['age'] ?? null,
                    // 'condition'           => $row['condition'] ?? null,
                    'address'             => $row['address'] ?? null,
                    'landmark'            => $row['landmark'] ?? null,
                    // 'ownership'           => $row['ownership'] ?? null,
                    'concern_person_name' => $row['concern_person_name'] ?? null,
                    'remark'              => $row['remark'] ?? null,
                    'latitude'            => $row['latitude'] ?? null,
                    'longitude'           => $row['longitude'] ?? null,
                    'accuracy'            => $row['accuracy'] ?? null,
                ]);
            }

            return response()->json(['message' => 'Tree data imported successfully'],201);

        } catch (\Exception $e) {
            Log::error('Excel import failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to import Excel file. Please check the file format and data.'], 500);
        }
    }

    public function exportExcel(Request $request, $projectId = null)
    {
        $query = Tree::query();

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        $trees = $query->get();

        $fileName = 'trees_export_' . now()->format('Ymd_His') . '.xlsx';

        // The export method returns a string containing the file contents,
        // so we create a download response manually.
        $export = (new FastExcel($trees))->export($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }


 public function geotag(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
            'photo'      => 'required|image|mimes:jpg,jpeg,png|max:8192',
            'accuracy'   => 'nullable|numeric',
        ]);

        // 1. Reverse geocode to get address
        $address = null;
        $apiKey = config('services.google.maps_api_key');
        if ($apiKey) {
            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'latlng' => "{$data['latitude']},{$data['longitude']}",
                    'key' => $apiKey,
                ]);

                if ($response->successful() && count($response->json('results', [])) > 0) {
                    $address = $response->json('results.0.formatted_address');
                } else {
                    Log::warning('Google Geocoding API call failed or returned no results.', [
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to connect to Google Geocoding API.', ['error' => $e->getMessage()]);
            }
        } else {
            Log::warning('Google Maps API key is not configured.');
        }

        // 2. Handle photo upload
        $photoPath = $request->file('photo')->store('trees', 'public');

        // 3. Create the tree
        $tree = Tree::create([
            'project_id' => $data['project_id'],
            'user_id'    => $request->user()->id,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'accuracy'   => $data['accuracy'] ?? null,
            'address'    => $address,
            // You can set default values for other fields if needed
            'common_name' => 'Unnamed Tree',
        ]);

        // 4. Attach the photo
        $tree->photos()->create(['path' => $photoPath]);

        return new TreeResource($tree->load('photos'));
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
