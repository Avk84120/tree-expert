<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\Tree;
use App\Models\TreePhoto;
use App\Models\Tree;
use App\Models\AadhaarPhoto;
// use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\TreesExport;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    // ================= Upload Forms =================
    public function showUploadTreeForm()
    {
        return view('admin.media.upload-tree');
    }

    public function showUploadAadhaarForm()
    {
        return view('admin.media.upload-aadhaar');
    }

    // ================= Upload Handlers =================
    // public function uploadTreePhoto(Request $request)
    // {
    //     $request->validate([
    //         'tree_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $path = $request->file('tree_photo')->store('tree_photos', 'public');

    //     return back()->with('success', 'Tree photo uploaded successfully! Saved at: ' . $path);
    // }
    public function uploadTreePhoto(Request $request)
{
    $request->validate([
        'tree_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'tree_id'    => 'nullable|exists:trees,id',
        'latitude'   => 'nullable|numeric',
        'longitude'  => 'nullable|numeric',
        'accuracy'   => 'nullable|string|max:50',
    ]);

    // Store file in storage/app/public/trees
    $path = $request->file('tree_photo')->store('trees', 'public');

    // Save record in DB
    $photo = TreePhoto::create([
        'tree_id'   => $request->tree_id ?? null,
        'path'      => $path,
        'latitude'  => $request->latitude ?? null,
        'longitude' => $request->longitude ?? null,
        'accuracy'  => $request->accuracy ?? null,
    ]);

    return back()->with('success', '✅ Tree photo uploaded successfully! ID: '.$photo->id);
}

    public function uploadAadhaarPhoto(Request $request)
    {
        $request->validate([
            'aadhaar_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'user_id'       => 'nullable|exists:users,id', // if linked to users
        ]);

        // Save file in storage/app/public/aadhaar_photos
        $path = $request->file('aadhaar_photo')->store('aadhaar_photos', 'public');

        // Save in DB
        AadhaarPhoto::create([
            'user_id' => $request->user_id,
            'path'    => $path,
        ]);

        return back()->with('success', '✅ Aadhaar photo uploaded!');
    }

    //Tree Photos list
    public function listTreePhotos()
{
    $photos = TreePhoto::with('tree')->latest()->paginate(10);

    return view('admin.media.tree_photos.index', compact('photos'));
}

    // ================= Downloads =================
    public function downloadExcel($projectId = null)
{
    $filename = 'trees.xlsx';

    $trees = Tree::with('project')
        ->when($projectId, fn($q) => $q->where('project_id', $projectId))
        ->cursor(); // memory efficient

    // Transform data for Excel
    $exportData = function() use ($trees) {
        foreach ($trees as $t) {
            yield [
                'ID'                 => $t->id,
                'Project Name'       => $t->project->name ?? 'N/A',
                'Ward'               => $t->ward,
                'Tree No'            => $t->tree_no,
                'Tree Name'          => $t->tree_name,
                'Scientific Name'    => $t->scientific_name,
                'Family'             => $t->family,
                'Girth (cm)'         => $t->girth_cm,
                'Height (m)'         => $t->height_m,
                'Canopy (m)'         => $t->canopy_m,
                'Age'                => $t->age,
                'Condition'          => $t->condition,
                'Latitude'           => $t->latitude,
                'Longitude'          => $t->longitude,
                'Ownership'          => $t->ownership,
                'Concern Person'     => $t->concern_person_name,
                'Remark'             => $t->remark,
            ];
        }
    };

    return (new FastExcel($exportData()))->download($filename);
}

    public function downloadKML()
    {
        $kmlContent = '<?xml version="1.0" encoding="UTF-8"?>
        <kml xmlns="http://www.opengis.net/kml/2.2">
            <Document>
                <Placemark>
                    <name>Tree Location</name>
                    <Point>
                        <coordinates>72.8777,19.0760,0</coordinates>
                    </Point>
                </Placemark>
            </Document>
        </kml>';

        return response($kmlContent)
            ->header('Content-Type', 'application/vnd.google-earth.kml+xml')
            ->header('Content-Disposition', 'attachment; filename="trees.kml"');
    }

    public function generateReports()
    {
        return view('admin.media.reports');
    }
}
