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

    <h2>Summary</h2>
    <table>
        <tr><td>Total Rooms</td><td>{{ $totalRooms }}</td></tr>
        <tr><td>Occupied</td><td>{{ $occupied }}</td></tr>
        <tr><td>Available</td><td>{{ $available }}</td></tr>
        <tr><td>Occupancy Rate</td><td>{{ $occupancyRate }}%</td></tr>
    </table>

    <h2>Room Details</h2>
    <table>
        <tr><th>Room</th><th>Bookings</th><th>Revenue</th></tr>
        @foreach ($rooms as $room)
            <tr><td>{{ $room->name }}</td><td>{{ $room->bookings_count }}</td><td>TSh{{ number_format($room->revenue ?? 0, 2) }}</td></tr>
        @endforeach
    </table>
</body>
</html>
