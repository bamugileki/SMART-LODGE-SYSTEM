<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { color: #4338ca; border-bottom: 2px solid #4338ca; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
        .total { font-weight: bold; font-size: 14px; }
        .summary { display: flex; justify-content: space-between; margin: 16px 0; }
        .card { background: #f9fafb; padding: 12px; border-radius: 8px; text-align: center; flex: 1; margin: 0 4px; }
        .card-value { font-size: 18px; font-weight: bold; color: #4338ca; }
    </style>
</head>
<body>
    <h1>Financial Report</h1>
    <p>Generated: {{ $generated_at }}</p>
    @if ($startDate) <p>From: {{ $startDate }} @endif @if ($endDate) To: {{ $endDate }}</p> @endif

    <div class="summary">
        <div class="card"><div>Total Revenue</div><div class="card-value">TSh{{ number_format($totalRevenue, 2) }}</div></div>
        <div class="card"><div>Transactions</div><div class="card-value">{{ $totalPayments }}</div></div>
    </div>

    <h2>Daily Revenue</h2>
    <table>
        <tr><th>Date</th><th>Revenue</th><th>Transactions</th></tr>
        @foreach ($daily as $row)
            <tr><td>{{ $row->date }}</td><td>TSh{{ number_format($row->total, 2) }}</td><td>{{ $row->count }}</td></tr>
        @endforeach
    </table>

    <h2>Payment Methods</h2>
    <table>
        <tr><th>Method</th><th>Transactions</th><th>Total</th></tr>
        @foreach ($byMethod as $m)
            <tr><td>{{ ucfirst(str_replace('_', ' ', $m->method)) }}</td><td>{{ $m->count }}</td><td>TSh{{ number_format($m->total, 2) }}</td></tr>
        @endforeach
    </table>
</body>
</html>
