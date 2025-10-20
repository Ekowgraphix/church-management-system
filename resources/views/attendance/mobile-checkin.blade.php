<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mobile Check-In - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                <i class="fas fa-church text-purple-600 text-3xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">Mobile Check-In</h1>
            <p class="text-purple-200">Scan your QR code or enter your details</p>
        </div>

        <!-- Tab Selection -->
        <div class="flex bg-white/10 backdrop-blur-lg rounded-2xl p-2 mb-6">
            <button onclick="showTab('qr')" id="tab-qr-btn" class="tab-btn flex-1 py-3 rounded-xl text-white font-semibold transition bg-white/20">
                <i class="fas fa-qrcode mr-2"></i> QR Code
            </button>
            <button onclick="showTab('manual')" id="tab-manual-btn" class="tab-btn flex-1 py-3 rounded-xl text-white/70 font-semibold transition">
                <i class="fas fa-keyboard mr-2"></i> Manual
            </button>
        </div>

        <!-- QR Code Scanner Tab -->
        <div id="qr-tab" class="tab-content">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 mb-6">
                <div class="text-center">
                    <div id="qr-reader" class="mb-6 rounded-xl overflow-hidden"></div>
                    <p class="text-white/70 text-sm">Position your QR code in front of the camera</p>
                </div>
            </div>
        </div>

        <!-- Manual Check-In Tab -->
        <div id="manual-tab" class="tab-content hidden">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
                <form id="manual-checkin-form" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-white font-semibold mb-2">Member ID or Phone</label>
                        <input type="text" id="member_identifier" name="member_identifier" required 
                               class="w-full px-4 py-3 bg-white/20 border-2 border-white/30 rounded-xl text-white placeholder-white/50 focus:outline-none focus:border-purple-400"
                               placeholder="Enter Member ID or Phone Number">
                    </div>

                    <div>
                        <label class="block text-white font-semibold mb-2">Select Service</label>
                        <select id="service_id" name="service_id" required 
                                class="w-full px-4 py-3 bg-white/20 border-2 border-white/30 rounded-xl text-white focus:outline-none focus:border-purple-400">
                            <option value="">Choose a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->service_name }} - {{ $service->service_time }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold py-4 rounded-xl hover:from-purple-600 hover:to-pink-600 transition transform hover:scale-105">
                        <i class="fas fa-check-circle mr-2"></i> Check In
                    </button>
                </form>
            </div>
        </div>

        <!-- Success Message -->
        <div id="success-message" class="hidden bg-green-500/20 border-2 border-green-500 rounded-2xl p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-check text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold text-xl">Check-In Successful!</h3>
                    <p id="success-name" class="text-green-200"></p>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="hidden bg-red-500/20 border-2 border-red-500 rounded-2xl p-6 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-times text-white text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-white font-bold text-xl">Check-In Failed</h3>
                    <p id="error-text" class="text-red-200"></p>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6">
            <h3 class="text-white font-bold mb-4">Today's Attendance</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <p class="text-4xl font-bold text-white" id="today-count">-</p>
                    <p class="text-purple-200 text-sm">Total Check-Ins</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-white" id="service-count">-</p>
                    <p class="text-purple-200 text-sm">This Service</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <a href="{{ route('attendance.index') }}" class="text-purple-200 hover:text-white">
                <i class="fas fa-arrow-left mr-2"></i> Back to Attendance Dashboard
            </a>
        </div>
    </div>

    <!-- QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <script>
        let html5QrcodeScanner;

        function showTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-white/20');
                el.classList.add('text-white/70');
            });

            // Show selected tab
            if (tab === 'qr') {
                document.getElementById('qr-tab').classList.remove('hidden');
                document.getElementById('tab-qr-btn').classList.add('bg-white/20');
                document.getElementById('tab-qr-btn').classList.remove('text-white/70');
                startQRScanner();
            } else {
                document.getElementById('manual-tab').classList.remove('hidden');
                document.getElementById('tab-manual-btn').classList.add('bg-white/20');
                document.getElementById('tab-manual-btn').classList.remove('text-white/70');
                stopQRScanner();
            }
        }

        function startQRScanner() {
            if (html5QrcodeScanner) return;

            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanError);
        }

        function stopQRScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Get selected service
            const serviceSelect = document.getElementById('service_id');
            if (!serviceSelect.value) {
                showError('Please select a service first');
                return;
            }

            // Send QR code to server
            fetch('{{ route("attendance.checkin.qr") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    qr_code: decodedText,
                    service_id: serviceSelect.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showError(data.error);
                } else {
                    showSuccess(data.member_name || 'Member');
                    updateStats();
                }
            })
            .catch(error => {
                showError('Check-in failed. Please try again.');
            });

            stopQRScanner();
        }

        function onScanError(error) {
            // Handle scan error silently
        }

        // Manual check-in form submission
        document.getElementById('manual-checkin-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("attendance.checkin") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.member_name || 'Member');
                    this.reset();
                    updateStats();
                } else {
                    showError(data.error || 'Check-in failed');
                }
            })
            .catch(error => {
                showError('Check-in failed. Please try again.');
            });
        });

        function showSuccess(name) {
            document.getElementById('success-name').textContent = name + ' checked in successfully!';
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('error-message').classList.add('hidden');
            
            setTimeout(() => {
                document.getElementById('success-message').classList.add('hidden');
                if (html5QrcodeScanner) {
                    startQRScanner();
                }
            }, 3000);
        }

        function showError(message) {
            document.getElementById('error-text').textContent = message;
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('success-message').classList.add('hidden');
            
            setTimeout(() => {
                document.getElementById('error-message').classList.add('hidden');
                if (html5QrcodeScanner) {
                    startQRScanner();
                }
            }, 3000);
        }

        function updateStats() {
            // Fetch and update today's stats
            fetch('/api/attendance/today-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('today-count').textContent = data.total || '0';
                    document.getElementById('service-count').textContent = data.service || '0';
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        // Initialize
        startQRScanner();
        updateStats();
    </script>
</body>
</html>
