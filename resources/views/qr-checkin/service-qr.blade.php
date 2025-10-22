<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service->name }} - QR Code Check-In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media print {
            body {
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-8 text-center text-white">
                <div class="inline-block p-4 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-church text-4xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-2">{{ $service->name }}</h1>
                <p class="text-green-100">
                    <i class="far fa-clock mr-2"></i>
                    {{ \Carbon\Carbon::parse($service->start_time)->format('h:i A') }} - 
                    {{ \Carbon\Carbon::parse($service->end_time)->format('h:i A') }}
                </p>
                @if($service->description)
                <p class="mt-2 text-green-100 text-sm">{{ $service->description }}</p>
                @endif
            </div>

            <!-- QR Code Section -->
            <div class="p-12 text-center bg-white">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Scan to Check In</h2>
                
                <!-- QR Code -->
                <div class="inline-block p-8 bg-white border-4 border-green-500 rounded-2xl shadow-lg mb-6">
                    <img src="{{ route('qr.service.generate', $service) }}" 
                         alt="QR Code" 
                         class="w-80 h-80"
                         id="qrCodeImage">
                </div>

                <!-- Instructions -->
                <div class="max-w-md mx-auto space-y-4 text-left">
                    <div class="flex items-start space-x-3 bg-gray-50 p-4 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                            1
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700">Open your phone camera or QR scanner app</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 bg-gray-50 p-4 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                            2
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700">Point your camera at the QR code above</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 bg-gray-50 p-4 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                            3
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-700">Tap the notification to complete check-in</p>
                        </div>
                    </div>
                </div>

                <!-- Manual Token (small text at bottom) -->
                <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 mb-1">Manual Check-In Token:</p>
                    <code class="text-sm font-mono bg-white px-3 py-1 rounded border border-gray-300 text-gray-700">
                        {{ $service->qr_code_token }}
                    </code>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <div>
                        <i class="fas fa-calendar text-green-500 mr-2"></i>
                        {{ ucfirst($service->day_of_week) }}s
                    </div>
                    <div>
                        <i class="fas fa-clock text-green-500 mr-2"></i>
                        {{ \Carbon\Carbon::parse($service->start_time)->format('h:i A') }}
                    </div>
                    <div>
                        <i class="fas fa-qrcode text-green-500 mr-2"></i>
                        QR Check-In Active
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons (No Print) -->
        <div class="mt-6 flex gap-3 no-print">
            <a href="{{ route('dashboard') }}" class="flex-1 px-6 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white text-center hover:bg-white/20 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
            
            <button onclick="window.print()" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all">
                <i class="fas fa-print mr-2"></i>
                Print QR Code
            </button>
            
            <button onclick="downloadQR()" class="flex-1 px-6 py-3 bg-blue-500 rounded-xl text-white font-semibold hover:bg-blue-600 transition-all">
                <i class="fas fa-download mr-2"></i>
                Download QR
            </button>
        </div>

        <!-- Live Stats (No Print) -->
        <div class="mt-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 no-print">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-chart-line text-green-400 mr-2"></i>
                Today's Check-Ins
            </h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-400" id="totalCheckIns">0</p>
                    <p class="text-sm text-gray-400">Total</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-400" id="lastHourCheckIns">0</p>
                    <p class="text-sm text-gray-400">Last Hour</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-400 pulse" id="recentCheckIns">0</p>
                    <p class="text-sm text-gray-400">Last 5 Min</p>
                </div>
            </div>
        </div>

        <!-- QR Code Link (No Print) -->
        <div class="mt-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 no-print">
            <p class="text-sm text-gray-400 mb-2">Direct Check-In Link:</p>
            <div class="flex gap-2">
                <input type="text" 
                       id="checkInLink" 
                       value="{{ route('qr.service.checkin', ['token' => $service->qr_code_token]) }}" 
                       readonly
                       class="flex-1 px-4 py-2 bg-white/5 border border-white/20 rounded-xl text-white text-sm">
                <button onclick="copyLink()" class="px-4 py-2 bg-green-500 rounded-xl text-white hover:bg-green-600 transition-all">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Download QR Code
        function downloadQR() {
            const link = document.createElement('a');
            link.href = '{{ route("qr.service.generate", $service) }}';
            link.download = '{{ $service->name }}-QR-Code.svg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Copy link
        function copyLink() {
            const linkInput = document.getElementById('checkInLink');
            linkInput.select();
            document.execCommand('copy');
            
            // Show feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.add('bg-green-600');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-600');
            }, 2000);
        }

        // Load stats (you'll need to create an API endpoint for this)
        async function loadStats() {
            // Placeholder - implement with actual API
            document.getElementById('totalCheckIns').textContent = '-';
            document.getElementById('lastHourCheckIns').textContent = '-';
            document.getElementById('recentCheckIns').textContent = '-';
        }

        // Reload stats every 30 seconds
        setInterval(loadStats, 30000);
        loadStats();
    </script>
</body>
</html>
