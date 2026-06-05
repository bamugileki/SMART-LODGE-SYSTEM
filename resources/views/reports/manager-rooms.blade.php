<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Room Performance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #4338ca; border-bottom: 2px solid #4338ca; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Room Performance Report</h1>
    <p>Generated: {{ $generated_at }}</p>
    <table>
        <tr><th>Room</th><th>Bookings</th><th>Occupancy</th></tr>
        @foreach ($rooms as $room)
            <tr><td>{{ $room->name }}</td><td>{{ $room->bookings_count }}</td><td>{{ $room->status }}</td></tr>
        @endforeach
    </table>
    <p>Occupancy Rate: {{ $occupancyRate }}% ({{ $occupied }}/{{ $totalRooms }})</p>
</body>
</html>
