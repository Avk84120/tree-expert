<!DOCTYPE html>
<html>
<head>
    <title>Project Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Project Report: {{ $project->name }}</h2>

    <p><b>Client:</b> {{ $project->client }}</p>
    <p><b>Company:</b> {{ $project->company }}</p>
    <p><b>Field Officer:</b> {{ $project->field_officer }}</p>
    <p><b>Start Date:</b> {{ $project->start_date }}</p>
    <p><b>End Date:</b> {{ $project->end_date }}</p>
    <p><b>Total Trees:</b> {{ $trees->count() }}</p>

    <h3>Trees Data</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Scientific</th>
            <th>Ward</th>
            <th>Height (m)</th>
            <th>Officer</th>
        </tr>
        @foreach($trees as $tree)
        <tr>
            <td>{{ $tree->id }}</td>
            <td>{{ $tree->tree_name }}</td>
            <td>{{ $tree->scientific_name }}</td>
            <td>{{ $tree->ward }}</td>
            <td>{{ $tree->height_m }}</td>
            <td>{{ $tree->concern_person_name }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
