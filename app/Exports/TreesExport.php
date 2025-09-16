<?php

namespace App\Exports;

use App\Models\Tree;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TreesExport implements FromCollection, WithHeadings
{
    protected $projectId;

    public function __construct($projectId = null)
    {
        $this->projectId = $projectId;
    }

    public function collection()
    {
        $query = Tree::query()->with('project');

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query->get([
            'id',
            'project_id',
            'ward',
            'tree_no',
            'tree_name',
            'scientific_name',
            'family',
            'girth_cm',
            'height_m',
            'canopy_m',
            'age',
            'condition',
            'latitude',
            'longitude',
            'ownership',
            'concern_person_name',
            'remark'
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Project ID',
            'Ward',
            'Tree No',
            'Tree Name',
            'Scientific Name',
            'Family',
            'Girth (cm)',
            'Height (m)',
            'Canopy (m)',
            'Age',
            'Condition',
            'Latitude',
            'Longitude',
            'Ownership',
            'Concern Person',
            'Remark',
        ];
    }
}
