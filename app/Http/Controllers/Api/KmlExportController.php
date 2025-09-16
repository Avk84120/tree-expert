<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tree;

class KmlExportController extends Controller
{
    /**
     * Export Trees as KML with photo links
     */
    public function downloadKml(Request $request, $projectId = null)
    {
        $trees = Tree::query()
            ->when($projectId, fn($q) => $q->where('project_id', $projectId))
            ->get();

        $kml = '<?xml version="1.0" encoding="UTF-8"?>';
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
        $kml .= '<name>Tree Survey Data</name>';

        foreach ($trees as $tree) {
            $photoUrl = $tree->photo ? asset('storage/' . $tree->photo) : 'No Photo';

            $kml .= '<Placemark>';
            $kml .= '<name>' . htmlspecialchars($tree->tree_name) . '</name>';
            $kml .= '<description><![CDATA[';
            $kml .= '<b>Scientific Name:</b> ' . $tree->scientific_name . '<br/>';
            $kml .= '<b>Family:</b> ' . $tree->family . '<br/>';
            $kml .= '<b>Ward:</b> ' . $tree->ward . '<br/>';
            $kml .= '<b>Officer:</b> ' . $tree->concern_person_name . '<br/>';
            $kml .= '<b>Photo:</b> <a href="' . $photoUrl . '" target="_blank">View</a>';
            $kml .= ']]></description>';
            $kml .= '<Point><coordinates>' . $tree->longitude . ',' . $tree->latitude . ',0</coordinates></Point>';
            $kml .= '</Placemark>';
        }

        $kml .= '</Document></kml>';

        $fileName = 'trees_' . now()->format('Ymd_His') . '.kml';

        return response($kml, 200, [
            'Content-Type' => 'application/vnd.google-earth.kml+xml',
            'Content-Disposition' => "attachment; filename={$fileName}"
        ]);
    }
}
