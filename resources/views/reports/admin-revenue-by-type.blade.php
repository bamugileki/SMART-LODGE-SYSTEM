<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue by Room Type</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #4338ca; border-bottom: 2px solid #4338ca; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Revenue by Room Type</h1>
    <p>Generated: {{ $generated_at }}</p>
    @if ($startDate) <p>From: {{ $startDate }} @endif @if ($endDate) To: {{ $endDate }}</p> @endif

    <table>
        <tr><th>Room Type</th><th>Revenue</th><th>Bookings</th></tr>
        @foreach ($items as $row)
            <tr><td>{{ $row->name }}</td><td>TSh{{ number_format($row->total, 2) }}</td><td>{{ $row->count }}</td></tr>
        @endforeach
    </table>
</body>
</html>
