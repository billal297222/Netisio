<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF Summary - Page {{ $pageNumber }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        h2 { text-align: center; margin-bottom: 20px; }
        .download-date { text-align: right; font-size: 12px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="download-date">
        Downloaded on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <h2>PDF Summary </h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Short Description</th>
                <th>Date of Pdf</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pdfs as $index => $pdf)
                <tr>
                    <td>{{ ($index + 1) + (($pageNumber - 1) * 10) }}</td>
                    <td>{{ $pdf['title'] }}</td>
                    <td>{{ $pdf['short_desc'] }}</td>
                    <td>{{ $pdf->date->date_value ?? '' }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
