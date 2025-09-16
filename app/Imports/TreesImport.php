<?php

namespace App\Imports;

use App\Models\Tree;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TreesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Tree([
            'project_id'          => $row['project_id'],
            'user_id'             => $row['user_id'] ?? null,
            'tree_name_id'        => $row['tree_name_id'] ?? null,
            'ward'                => $row['ward'] ?? null,
            'tree_no'             => $row['tree_no'] ?? null,
            'tree_name'           => $row['tree_name'],
            'scientific_name'     => $row['scientific_name'] ?? null,
            'family'              => $row['family'] ?? null,
            'girth_cm'            => $row['girth_cm'] ?? null,
            'height_m'            => $row['height_m'] ?? null,
            'canopy_m'            => $row['canopy_m'] ?? null,
            'age'                 => $row['age'] ?? null,
            'condition'           => $row['condition'] ?? null,
            'address'             => $row['address'] ?? null,
            'landmark'            => $row['landmark'] ?? null,
            'ownership'           => $row['ownership'] ?? null,
            'concern_person_name' => $row['concern_person_name'] ?? null,
            'remark'              => $row['remark'] ?? null,
            'latitude'            => $row['latitude'] ?? null,
            'longitude'           => $row['longitude'] ?? null,
            'accuracy'            => $row['accuracy'] ?? null,
        ]);
    }
}