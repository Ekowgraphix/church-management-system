<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offerings Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #22c55e;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #22c55e;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }
        .total-box {
            background-color: #22c55e;
            color: white;
            padding: 15px;
            display: inline-block;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Church Offerings Report</h1>
        <p>Generated on {{ date('F d, Y \a\t h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Service</th>
                <th>Category</th>
                <th>Amount (GHS)</th>
                <th>Payment Method</th>
                <th>Collected By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offerings as $offering)
            <tr>
                <td>{{ $offering->date->format('M d, Y') }}</td>
                <td>{{ $offering->service_name ?: 'N/A' }}</td>
                <td>{{ $offering->category }}</td>
                <td>{{ number_format($offering->amount, 2) }}</td>
                <td>{{ $offering->payment_method }}</td>
                <td>{{ $offering->collected_by ?: 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <div class="total-box">
            Grand Total: GHS {{ number_format($total, 2) }}
        </div>
    </div>

    <div class="footer">
        <p>This report contains {{ $offerings->count() }} offering record(s)</p>
        <p>Church Management System - Financial Report</p>
    </div>
</body>
</html>
