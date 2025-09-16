<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Master Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1, h2 { text-align: center; }
    </style>
</head>
<body>

    <h1>Master Tree Report</h1>

    @if(isset($trees) && $trees->count() > 0)
        <h2>Tree Data</h2>
        <table>
            <thead>
                <tr>
                    <th>Tree No</th>
                    <th>Common Name</th>
                    <th>Girth (cm)</th>
                    <th>Height (m)</th>
                    <th>Ward</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trees as $tree)
                    <tr>
                        <td>{{ $tree->tree_no ?? 'N/A' }}</td>
                        <td>{{ $tree->tree_name ?? 'N/A' }}</td>
                        <td>{{ $tree->girth_cm ?? 'N/A' }}</td>
                        <td>{{ $tree->height_m ?? 'N/A' }}</td>
                        <td>{{ $tree->ward ?? 'N*A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No tree data available for this report.</p>
    @endif

</body>
</html>