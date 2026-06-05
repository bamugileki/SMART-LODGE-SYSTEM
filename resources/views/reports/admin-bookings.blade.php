<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #4338ca; border-bottom: 2px solid #4338ca; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
        .summary { display: flex; justify-content: space-between; margin: 16px 0; }
        .card { background: #f9fafb; padding: 12px; border-radius: 8px; text-align: center; flex: 1; margin: 0 4px; }
        .card-value { font-size: 18px; font-weight: bold; color: #4338ca; }
    </style>
</head>
<body>
    <h1>Booking Report</h1>
    <p>Generated: {{ $generated_at }}</p>
    @if ($startDate) <p>From: {{ $startDate }} @endif @if ($endDate) To: {{ $endDate }}</p> @endif

    <div class="summary">
        <div class="card"><div>Total</div><div class="card-value">{{ $total }}</div></div>
        <div class="card"><div>Confirmed</div><div class="card-value" style="color:#16a34a">{{ $confirmed }}</div></div>
        <div class="card"><div>Cancelled</div><div class="card-value" style="color:#dc2626">{{ $cancelled }}</div></div>
        <div class="card"><div>Conversion</div><div class="card-value">{{ $conversion }}%</div></div>
    </div>

    <h2>Booking Trends</h2>
    <table>
        <tr><th>Month</th><th>Bookings</th></tr>
        @foreach ($monthlyTrend as $row)
            <tr><td>{{ $row->month }}</td><td>{{ $row->count }}</td></tr>
        @endforeach
    </table>

    <h2>Status Breakdown</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        @foreach ($byStatus as $status => $count)
            <tr><td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td><td>{{ $count }}</td></tr>
        @endforeach
    </table>
</body>
</html>
