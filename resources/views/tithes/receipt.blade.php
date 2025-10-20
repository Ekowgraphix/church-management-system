<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tithe Receipt - {{ $tithe->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            padding: 40px;
            background: #f8f9fa;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #22c55e;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #22c55e;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #22c55e, #3b82f6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
        }
        
        .church-name {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        
        .receipt-title {
            font-size: 20px;
            color: #22c55e;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-section {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #22c55e;
        }
        
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 500;
        }
        
        .amount-section {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin: 30px 0;
        }
        
        .amount-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 10px;
        }
        
        .amount-value {
            font-size: 48px;
            font-weight: bold;
        }
        
        .amount-words {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 10px;
            font-style: italic;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        .details-table th {
            background: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #1f2937;
        }
        
        .thank-you {
            background: #fef3c7;
            border: 2px dashed #f59e0b;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 30px 0;
        }
        
        .thank-you h3 {
            color: #b45309;
            margin-bottom: 10px;
        }
        
        .thank-you p {
            color: #92400e;
            line-height: 1.6;
        }
        
        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px dashed #e5e7eb;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-top: 2px solid #1f2937;
            margin-bottom: 10px;
            padding-top: 10px;
        }
        
        .signature-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
        }
        
        .receipt-number {
            background: #fee2e2;
            color: #991b1b;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(34, 197, 94, 0.05);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        
        @media print {
            body {
                padding: 0;
                background: white;
            }
            
            .receipt-container {
                border: none;
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">PAID</div>
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">⛪</div>
            <div class="church-name">{{ config('app.name', 'Church Management System') }}</div>
            <div style="color: #6b7280; margin-top: 5px;">Official Tithe Receipt</div>
            <div class="receipt-title">💎 TITHE RECEIPT</div>
            <div class="receipt-number">Receipt #{{ str_pad($tithe->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Receipt Info Grid -->
        <div class="receipt-info">
            <div class="info-section">
                <div class="info-label">Date</div>
                <div class="info-value">{{ $tithe->date->format('F d, Y') }}</div>
            </div>
            
            <div class="info-section">
                <div class="info-label">Receipt Date</div>
                <div class="info-value">{{ now()->format('F d, Y') }}</div>
            </div>
            
            <div class="info-section">
                <div class="info-label">Member Name</div>
                <div class="info-value">{{ $tithe->member->first_name ?? 'Unknown' }} {{ $tithe->member->last_name ?? '' }}</div>
            </div>
            
            <div class="info-section">
                <div class="info-label">Contact</div>
                <div class="info-value">{{ $tithe->member->phone ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">AMOUNT RECEIVED</div>
            <div class="amount-value">GHS {{ number_format($tithe->amount, 2) }}</div>
            <div class="amount-words">
                ({{ ucwords(numberToWords($tithe->amount)) }} Ghana Cedis Only)
            </div>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Payment Method</strong></td>
                    <td>{{ $tithe->payment_method }}</td>
                </tr>
                <tr>
                    <td><strong>Received By</strong></td>
                    <td>{{ $tithe->received_by ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Purpose</strong></td>
                    <td>Tithe Offering</td>
                </tr>
                @if($tithe->remarks)
                <tr>
                    <td><strong>Remarks</strong></td>
                    <td>{{ $tithe->remarks }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>Receipt Generated</strong></td>
                    <td>{{ now()->format('F d, Y - h:i A') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Thank You Message -->
        <div class="thank-you">
            <h3>🙏 Thank You for Your Faithfulness!</h3>
            <p>
                "Bring the whole tithe into the storehouse, that there may be food in my house. 
                Test me in this," says the LORD Almighty, "and see if I will not throw open the 
                floodgates of heaven and pour out so much blessing that there will not be room enough 
                to store it." - Malachi 3:10
            </p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    <div style="height: 40px;"></div>
                </div>
                <div class="signature-label">Finance Officer Signature</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-line">
                    <div style="height: 40px;"></div>
                </div>
                <div class="signature-label">Church Stamp</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>This is an official receipt from {{ config('app.name', 'Church Management System') }}</strong></p>
            <p style="margin-top: 5px;">For inquiries, please contact the church office.</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            <p style="margin-top: 10px; color: #22c55e; font-weight: bold;">God Bless You!</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

@php
function numberToWords($number) {
    $ones = array(
        0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 
        6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 
        11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 
        15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 
        19 => 'nineteen'
    );
    
    $tens = array(
        0 => '', 2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
        6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
    );
    
    $hundreds = array('', 'thousand', 'million', 'billion');
    
    $number = number_format($number, 2, '.', '');
    list($integer, $decimal) = explode('.', $number);
    
    $words = '';
    
    if ($integer == 0) {
        $words = 'zero';
    } else {
        $integer = str_pad($integer, (ceil(strlen($integer) / 3) * 3), 0, STR_PAD_LEFT);
        $groups = str_split($integer, 3);
        
        foreach ($groups as $key => $group) {
            if ($group == '000') continue;
            
            $word = '';
            $digit1 = $group[0];
            $digit2 = $group[1];
            $digit3 = $group[2];
            
            if ($digit1 > 0) {
                $word .= $ones[$digit1] . ' hundred ';
            }
            
            $last_two = $digit2 . $digit3;
            if ($last_two < 20) {
                $word .= $ones[$last_two];
            } else {
                $word .= $tens[$digit2] . ' ' . $ones[$digit3];
            }
            
            $word = trim($word);
            $word .= ' ' . $hundreds[count($groups) - $key - 1];
            $words .= $word . ' ';
        }
    }
    
    $words = trim($words);
    
    if ($decimal > 0) {
        $words .= ' and ' . $decimal . '/100';
    }
    
    return $words;
}
@endphp
