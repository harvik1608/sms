<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <h2>Invoice #{{ $invoice->id }}</h2>
    <p>Date: {{ format_date($invoice->created_at) }}</p>

    <h3>Customer Details</h3>
    <p>
        <strong>{{ $invoice->customer->name }}</strong><br>
        {{ $invoice->customer->email }}<br>
        {{ $invoice->customer->phone }}
    </p>

    <h3>Items</h3>
    <table>
        <thead>
            <tr>
                <th>Plan</th>
                <th>Duration</th>
                <th>Whatsapp Messages</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">{{ $invoice->plan?->name }}</td>
                <td align="center">{{ $invoice->duration }} month(s)</td>
                <td align="center">{{ $invoice->whatsapp }}</td>
                <td align="center">{{ currency().$invoice->amount }}</td>
            </tr>
        </tbody>
    </table>

    <h3 class="text-right">Grand Total: {{ currency() }}{{ number_format($invoice->amount, 2) }}</h3>

</body>
</html>
