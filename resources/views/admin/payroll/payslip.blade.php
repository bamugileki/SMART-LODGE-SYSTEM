<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->user->name }} - {{ $payroll->month }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; }
        .total-row td { font-weight: bold; font-size: 16px; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .label { color: #666; }
        .footer { text-align: center; margin-top: 40px; color: #888; font-size: 12px; }
        .details { margin-top: 30px; }
        .details p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BungeStay Hotel</h1>
        <p>Payslip for {{ $payroll->month }}</p>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="details">
        <p><strong>Employee:</strong> {{ $payroll->user->name ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $payroll->user->email ?? 'N/A' }}</p>
        <p><strong>Role:</strong> {{ ucfirst($payroll->user->role->slug ?? 'N/A') }}</p>
    </div>

    <table>
        <tr><th>Description</th><th class="text-right">Amount (TSh)</th></tr>
        <tr><td>Base Salary</td><td class="text-right">{{ number_format($payroll->base_salary, 2) }}</td></tr>
        <tr><td>Bonus</td><td class="text-right text-green-600">+{{ number_format($payroll->bonus, 2) }}</td></tr>
        <tr><td>Deductions</td><td class="text-right text-red-600">-{{ number_format($payroll->deductions, 2) }}</td></tr>
        <tr class="total-row"><td>Total Salary</td><td class="text-right">{{ number_format($payroll->total_salary, 2) }}</td></tr>
    </table>

    @if ($payroll->notes)
        <p style="margin-top: 20px;"><strong>Notes:</strong> {{ $payroll->notes }}</p>
    @endif

    @if ($payroll->paid_at)
        <p style="margin-top: 10px;"><strong>Paid on:</strong> {{ $payroll->paid_at->format('F d, Y h:i A') }}</p>
    @endif

    @if ($payroll->processor)
        <p style="margin-top: 10px;"><strong>Processed by:</strong> {{ $payroll->processor->name }}</p>
    @endif

    <div class="footer">
        <p>BungeStay Hotel Management System</p>
        <p>This is a computer-generated document.</p>
    </div>
</body>
</html>
