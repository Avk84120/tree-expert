<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tree;
use App\Models\Project;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Support\Facades\Response;
// use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Imports\TreesImport;
// use PDF;

        // $headers = [
        //     'Content-Type' => 'text/csv',
        //     'Content-Disposition' => 'attachment; filename="'. $filename.'"',
        // ];
class ReportsController extends Controller
{
    public function masterReport(){
        $trees = Tree::with('project')->get();
        return response()->json($trees);
    }

   public function exportExcel()
{
    $filename = "master_report.xlsx";

    // Generator function
    $treeGenerator = function () {
        foreach (Tree::with('project', 'photos')->cursor() as $t) {
            yield [
                'Project Name' => $t->project->name ?? 'N/A',
                'Tree ID'      => $t->id,
                'Tree No'      => $t->tree_no,
                'Common Name'  => $t->common_name,
                'Girth (cm)'   => $t->girth_cm,
                'Height (m)'   => $t->height_m,
                'Canopy (m)'   => $t->canopy_m,
                'Latitude'     => $t->latitude,
                'Longitude'    => $t->longitude,
                'Ward'         => $t->ward,
                'Condition'    => $t->condition,
                'Photo Link'   => $t->photos->first() 
                                    ? asset('storage/' . $t->photos->first()->path) 
                                    : null,
            ];
        }
    };

    return (new FastExcel($treeGenerator()))->download($filename);
}


    public function exportPdf()
{
    $trees = Tree::with('project')->get();

    // Load blade view into PDF
    $pdf = Pdf::loadView('reports.master_pdf', compact('trees'))
              ->setPaper('a4', 'landscape'); // landscape for wide tables

    $filename = "master_tree_report.pdf";

    return $pdf->download($filename);
}

       public function exportKml()
    {
        $trees = Tree::whereNotNull('latitude')->whereNotNull('longitude')->with('photos')->get();

        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2"><Document>';
        $kml .= "<name>Master Tree Report</name>\n";

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
            'Content-Disposition' => 'attachment; filename="master_tree_report.kml"'
        ]);
    }

    public function generateReport($projectId)
    {
        $project = Project::with(['trees', 'users'])->findOrFail($projectId);

        $data = [
            'project' => $project,
            'trees'   => $project->trees,
            'officers'=> $project->users,
        ];

        $pdf = PDF::loadView('reports.project', $data);

        $fileName = 'project_report_' . $project->id . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}
