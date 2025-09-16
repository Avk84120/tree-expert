<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel; // optional if you use maatwebsite/excel

class ProjectDetailController extends Controller
{
    /**
     * Show project detail on screen
     */
    public function show($id)
    {
        $project = Project::with(['trees.photos','users'])->findOrFail($id);

        // Determine project status sample logic (you can use DB field instead)
        $status = now()->between($project->start_date ?? now(), $project->end_date ?? now()) ? 'During work' : 'Start Work';

        // Total count of trees
        $totalTrees = $project->trees()->count();

        // Ward / Plot numbers (collect distinct ward or ward_no)
        $wards = $project->trees()->select('ward')->distinct()->pluck('ward')->filter()->values();

        // Officer - if project has a field_officer column string:
        $officerName = $project->field_officer ?? ($project->users()->wherePivot('role_id',2)->first()->name ?? null);

        // Sample aggregated stats
        $conditionCounts = $project->trees()->selectRaw('`condition`, count(*) as total')
                            ->groupBy('condition')->pluck('total','condition');

        return response()->json([
            'id' => $project->id,
            'name' => $project->name,
            'status' => $status,
            'total_count' => $project->total_count,
            'start_date' => optional($project->start_date)->toDateString(),
            'end_date' => optional($project->end_date)->toDateString(),
            'ward' => $wards,
            'officer_name' => $officerName,
            // 'condition_counts' => $conditionCounts,
            'download_options' => [
                'kml' => url("/api/projects/{$project->id}/kml"),
                'excel' => url("/api/projects/{$project->id}/export/excel"),
                'photos_zip' => url("/api/projects/{$project->id}/photos/download"),
                'report' => url("/api/projects/{$project->id}/report"),
            ],
            // 'trees_preview' => $project->trees()->limit(20)->get()->map(function($t){
            //     return [
            //         'id' => $t->id,
            //         'tree_no' => $t->tree_no,
            //         'common_name' => $t->common_name,
            //         'girth_cm' => $t->girth_cm,
            //         'height_m' => $t->height_m,
            //         'latitude' => $t->latitude,
            //         'longitude' => $t->longitude,
            //         'ward' => $t->ward,
            //         'photo' => $t->photos->first() ? asset('storage/'.$t->photos->first()->path) : null
            //     ];
            // })
        ]);
    }

    /**
     * Export KML with photo links for all trees in the project.
     */
    public function exportKml($id)
    {
        $project = Project::with('trees.photos')->findOrFail($id);
        $trees = $project->trees()->whereNotNull('latitude')->get();

        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
        $kml .= "<name>" . htmlspecialchars($project->name) . "</name>\n";

        foreach ($trees as $t) {
            $photo = $t->photos->first();
            $photoUrl = $photo ? asset('storage/' . $photo->path) : '';

            $desc = '<![CDATA[';
            $desc .= '<strong>' . htmlspecialchars($t->common_name ?? 'Unknown') . '</strong><br/>';
            $desc .= 'Girth: ' . ($t->girth_cm ? $t->girth_cm . ' cm' : 'N/A') . '<br/>';
            $desc .= 'Height: ' . ($t->height_m ? $t->height_m . ' m' : 'N/A') . '<br/>';
            if ($photoUrl) $desc .= "<img src=\"{$photoUrl}\" width=\"200\" />";
            $desc .= ']]>';

            $kml .= "<Placemark>\n";
            $kml .= "<name>" . htmlspecialchars($t->tree_no ?? $t->common_name ?? 'Tree') . "</name>\n";
            $kml .= "<description>$desc</description>\n";
            $kml .= "<Point><coordinates>{$t->longitude},{$t->latitude},0</coordinates></Point>\n";
            $kml .= "</Placemark>\n";
        }

        $kml .= '</Document></kml>';

        return response($kml, 200, [
            'Content-Type' => 'application/vnd.google-earth.kml+xml',
            'Content-Disposition' => "attachment; filename=\"project_{$project->id}.kml\""
        ]);
    }

    /**
     * Export Excel with unit conversion.
     * Query param: ?unit=cm|m|feet  (default: cm for girth; for height use m internally)
     */
    public function exportExcel(Request $request, $id)
    {
        $unit = $request->get('unit', 'cm'); // cm | m | feet
        $project = Project::with('trees.photos')->findOrFail($id);
        $trees = $project->trees()->get();

        // Map conversion
        $rows = $trees->map(function($t) use ($unit) {
            // assume girth in cm, height in meters
            $girth_cm = $t->girth_cm;
            $height_m = $t->height_m;

            switch ($unit) {
                case 'm':
                    $girth = $girth_cm ? round($girth_cm / 100, 3) : null; // cm -> m
                    $height = $height_m;
                    $girth_unit = 'm';
                    $height_unit = 'm';
                    break;
                case 'feet':
                    $girth = $girth_cm ? round($girth_cm / 30.48, 3) : null; // cm->feet
                    $height = $height_m ? round($height_m * 3.28084, 3) : null; // m->feet
                    $girth_unit = 'ft';
                    $height_unit = 'ft';
                    break;
                default: // cm
                    $girth = $girth_cm;
                    $height = $height_m;
                    $girth_unit = 'cm';
                    $height_unit = 'm';
                    break;
            }

            return [
                'Tree ID' => $t->id,
                'Tree No' => $t->tree_no,
                'Common Name' => $t->common_name,
                'Girth (' . $girth_unit . ')' => $girth,
                'Height (' . $height_unit . ')' => $height,
                'Canopy (m)' => $t->canopy_m,
                'Latitude' => $t->latitude,
                'Longitude' => $t->longitude,
                'Ward' => $t->ward,
                'Photo Link' => $t->photos->first() ? asset('storage/'.$t->photos->first()->path) : null,
            ];
        })->toArray();

        // If you have maatwebsite/excel installed you can return a real Excel file.
        // Simple CSV response fallback:
        $filename = "project_{$project->id}_trees_{$unit}.csv";
        $handle = fopen('php://temp','r+');
        // header row
        if (!empty($rows)) {
            fputcsv($handle, array_keys($rows[0]));
            foreach ($rows as $row) fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    /**
     * Download all photos for a project as a ZIP archive (compressed).
     */
    public function downloadPhotos($id)
    {
        $project = Project::with('trees.photos')->findOrFail($id);

        $zipFileName = storage_path("app/public/project_{$project->id}_photos.zip");

        // create zip
        $zip = new ZipArchive;
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return response()->json(['message' => 'Could not create zip file'], 500);
        }

        foreach ($project->trees as $t) {
            foreach ($t->photos as $photo) {
                $filePath = storage_path('app/public/'.$photo->path);
                if (file_exists($filePath)) {
                    // put files under folder tree_{id}/filename.jpg
                    $localName = "tree_{$t->id}/" . basename($photo->path);
                    $zip->addFile($filePath, $localName);
                }
            }
        }

        $zip->close();

        // Stream the zip file to user
        return response()->download($zipFileName, "project_{$project->id}_photos.zip")->deleteFileAfterSend(true);
    }

    /**
     * Return project report (aggregated JSON). You can replace with PDF generator (dompdf) if required.
     */
    public function report($id)
    {
        $project = Project::with('trees.photos')->findOrFail($id);

        $total = $project->trees()->count();
        $byCondition = $project->trees()->selectRaw('`condition`, count(*) as cnt')
    ->groupBy('condition')->pluck('cnt','condition');
        $bySpecies = $project->trees()->selectRaw('common_name, count(*) as cnt')->groupBy('common_name')->orderByDesc('cnt')->limit(10)->pluck('cnt','common_name');

        $report = [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'start_date' => optional($project->start_date)->toDateString(),
                'total_trees' => $total
            ],
            'by_condition' => $byCondition,
            'top_species' => $bySpecies,
            'download_kml' => url("/api/projects/{$project->id}/kml"),
            'download_excel' => url("/api/projects/{$project->id}/export/excel")
        ];

        // If you want PDF, use a library (dompdf / snappy) to render a blade view and return PDF download.
        return response()->json($report);
    }
}
