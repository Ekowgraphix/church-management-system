<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offering Receipt #{{ $offering->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #22c55e;
            padding: 30px;
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #22c55e;
            margin: 0;
            font-size: 32px;
        }
        .header .receipt-number {
            color: #666;
            font-size: 14px;
            margin-top: 10px;
        }
        .details {
            margin: 30px 0;
        }
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #333;
        }
        .detail-value {
            flex: 1;
            color: #666;
        }
        .amount-box {
            background-color: #22c55e;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            margin: 30px 0;
        }
        .amount-box .label {
            font-size: 14px;
            opacity: 0.9;
        }
        .amount-box .amount {
            font-size: 36px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #22c55e;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .thank-you {
            text-align: center;
            color: #22c55e;
            font-size: 18px;
            font-weight: bold;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>💰 OFFERING RECEIPT</h1>
            <div class="receipt-number">Receipt #{{ str_pad($offering->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="details">
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value">{{ $offering->date->format('l, F d, Y') }}</div>
            </div>

            @if($offering->service_name)
            <div class="detail-row">
                <div class="detail-label">Service:</div>
                <div class="detail-value">{{ $offering->service_name }}</div>
            </div>
            @endif

            <div class="detail-row">
                <div class="detail-label">Category:</div>
                <div class="detail-value">{{ $offering->category }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Payment Method:</div>
                <div class="detail-value">{{ $offering->payment_method }}</div>
            </div>

            @if($offering->collected_by)
            <div class="detail-row">
                <div class="detail-label">Collected By:</div>
                <div class="detail-value">{{ $offering->collected_by }}</div>
            </div>
            @endif

            @if($offering->remarks)
            <div class="detail-row">
                <div class="detail-label">Remarks:</div>
                <div class="detail-value">{{ $offering->remarks }}</div>
            </div>
            @endif
        </div>

        <div class="amount-box">
            <div class="label">AMOUNT RECEIVED</div>
            <div class="amount">GHS {{ number_format($offering->amount, 2) }}</div>
        </div>

        <div class="thank-you">
            Thank you for your generous offering!
        </div>

        <div class="footer">
            <p>Issued on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            <p>Church Management System - Financial Receipt</p>
            <p>This is a computer-generated receipt and is valid without signature</p>
        </div>
    </div>
</body>
</html>
