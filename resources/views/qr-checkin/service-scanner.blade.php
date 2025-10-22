@extends('layouts.member-portal')

@section('page-title', 'QR Check-In Scanner')
@section('page-subtitle', 'Scan QR code to mark your attendance')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }
    
    #qr-reader {
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }
    
    #qr-reader__scan_region {
        border-radius: 12px !important;
    }
    
    #qr-reader video {
        border-radius: 12px;
    }
    
    .service-card {
        transition: all 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3);
    }
    
    .scanner-container {
        position: relative;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    
    .day-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>

<div class="max-w-4xl mx-auto p-4 space-y-4">
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
            <i class="fas fa-qrcode text-3xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">QR Check-In</h1>
        <p class="text-gray-400">Scan the service QR code to mark your attendance</p>
    </div>

    <!-- Scanner Container -->
    <div class="scanner-container p-6 mb-4">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <i class="fas fa-camera text-green-400 mr-2"></i>
            QR Code Scanner
        </h3>
        <div id="qr-reader" class="mb-4"></div>
        
        <div id="scanner-status" class="text-center text-gray-300 mb-4 p-3 bg-white/5 rounded-lg">
            <i class="fas fa-camera text-green-400 mr-2"></i>
            <span>Position the QR code within the frame</span>
        </div>

        <!-- Manual Token Input -->
        <div class="border-t border-white/10 pt-4">
            <p class="text-sm text-gray-400 mb-3">Manual Token Entry:</p>
            <form id="manualCheckInForm" class="flex flex-col sm:flex-row gap-2">
                @csrf
                <input type="text" 
                       id="manualToken" 
                       placeholder="Enter service token (e.g., SVC-ABC123...)" 
                       class="flex-1 px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all whitespace-nowrap">
                    <i class="fas fa-check mr-2"></i>
                    Check In
                </button>
            </form>
        </div>
    </div>

    <!-- All Services by Day -->
    @if($services->count() > 0)
    <div class="scanner-container p-6 mb-4">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <i class="fas fa-calendar-week text-green-400 mr-2"></i>
            Weekly Services Schedule
        </h3>
        
        <div class="grid gap-3">
            @php
                $groupedServices = $services->groupBy('day_of_week');
                $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                $dayColors = [
                    'sunday' => 'from-purple-500 to-purple-600',
                    'monday' => 'from-blue-500 to-blue-600',
                    'tuesday' => 'from-green-500 to-green-600',
                    'wednesday' => 'from-yellow-500 to-yellow-600',
                    'thursday' => 'from-orange-500 to-orange-600',
                    'friday' => 'from-red-500 to-red-600',
                    'saturday' => 'from-pink-500 to-pink-600',
                ];
            @endphp
            
            @foreach($days as $day)
                @if(isset($groupedServices[$day]))
                <div class="bg-white/5 border {{ $day == $today ? 'border-green-400 shadow-lg shadow-green-500/20' : 'border-white/10' }} rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $dayColors[$day] }} flex items-center justify-center">
                                <i class="fas fa-calendar-day text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-lg">{{ ucfirst($day) }}</h4>
                                @if($day == $today)
                                    <span class="text-xs text-green-400 font-semibold flex items-center">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1 animate-pulse"></span>
                                        Today
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="day-badge bg-white/10 text-white">
                            {{ $groupedServices[$day]->count() }} {{ Str::plural('service', $groupedServices[$day]->count()) }}
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        @foreach($groupedServices[$day] as $service)
                        <div class="service-card bg-white/5 border border-white/5 rounded-lg p-3 hover:bg-white/10 transition-all">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h5 class="font-semibold text-white text-sm">{{ $service->name }}</h5>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="text-xs text-gray-400">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ \Carbon\Carbon::parse($service->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($service->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                    @if($service->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($service->description, 50) }}</p>
                                    @endif
                                </div>
                                <div class="text-green-400 ml-2">
                                    <i class="fas fa-qrcode text-xl"></i>
                                </div>
                            </div>
                            
                            <!-- Service Token -->
                            <div class="mt-2 pt-2 border-t border-white/5">
                                <div class="flex items-center justify-between bg-black/20 rounded px-2 py-1.5">
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-400 mb-0.5">Service Token:</p>
                                        <code class="text-xs font-mono text-green-400 select-all">{{ $service->qr_code_token ?? 'No token' }}</code>
                                    </div>
                                    <button onclick="copyToken('{{ $service->qr_code_token }}')" class="ml-2 px-2 py-1 bg-green-500/20 hover:bg-green-500/30 rounded text-green-400 text-xs transition-all">
                                        <i class="fas fa-copy mr-1"></i>
                                        Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @else
    <div class="scanner-container p-6 text-center">
        <i class="fas fa-calendar-times text-4xl text-gray-500 mb-3"></i>
        <p class="text-gray-400">No services scheduled</p>
    </div>
    @endif

    <!-- Recent Check-Ins -->
    <div class="scanner-container p-6">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <i class="fas fa-history text-green-400 mr-2"></i>
            Your Recent Check-Ins
        </h3>
        <div id="recentCheckIns" class="space-y-2">
            <div class="text-center text-gray-400 py-8 bg-white/5 rounded-lg">
                <i class="fas fa-info-circle text-3xl mb-2"></i>
                <p>Check your full attendance history in the Attendance page</p>
                <a href="{{ route('portal.attendance') }}" class="inline-block mt-3 px-4 py-2 bg-green-500 rounded-lg text-white hover:bg-green-600 transition-all">
                    <i class="fas fa-chart-line mr-2"></i>
                    View Attendance History
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-slate-900 rounded-2xl p-6 max-w-md w-full transform scale-95 transition-all">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-green-500/20 flex items-center justify-center">
                <i class="fas fa-check-circle text-4xl text-green-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Check-In Successful!</h3>
            <p class="text-gray-400 mb-4" id="successMessage"></p>
            <div class="bg-white/5 rounded-xl p-4 mb-4 text-left">
                <p class="text-sm text-gray-400">Service:</p>
                <p class="text-white font-semibold" id="successService"></p>
                <p class="text-sm text-gray-400 mt-2">Time:</p>
                <p class="text-white font-semibold" id="successTime"></p>
            </div>
            <button onclick="closeSuccessModal()" class="w-full px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all">
                <i class="fas fa-check mr-2"></i>
                Done
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-slate-900 rounded-2xl p-6 max-w-md w-full transform scale-95 transition-all">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-red-500/20 flex items-center justify-center">
                <i class="fas fa-times-circle text-4xl text-red-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">Check-In Failed</h3>
            <p class="text-gray-400 mb-4" id="errorMessage"></p>
            <button onclick="closeErrorModal()" class="w-full px-6 py-3 bg-white/10 rounded-xl text-white font-semibold hover:bg-white/20 transition-all">
                <i class="fas fa-times mr-2"></i>
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner;
    let isProcessing = false;

    // Copy token to clipboard
    function copyToken(token) {
        if (!token) {
            alert('No token to copy');
            return;
        }
        
        // Use modern clipboard API if available
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(token).then(() => {
                // Show success feedback
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                btn.classList.add('bg-green-500', 'text-white');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-green-500', 'text-white');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy:', err);
                fallbackCopy(token);
            });
        } else {
            fallbackCopy(token);
        }
    }
    
    // Fallback copy method for older browsers
    function fallbackCopy(token) {
        const textarea = document.createElement('textarea');
        textarea.value = token;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            alert('Token copied: ' + token);
        } catch (err) {
            alert('Failed to copy. Please copy manually: ' + token);
        }
        document.body.removeChild(textarea);
    }

    // Initialize QR Scanner
    function initScanner() {
        // Check if page is loaded over HTTPS or localhost
        const isSecure = window.location.protocol === 'https:' || 
                        window.location.hostname === 'localhost' || 
                        window.location.hostname === '127.0.0.1' ||
                        window.location.hostname.startsWith('192.168.');
        
        if (!isSecure && navigator.mediaDevices) {
            document.getElementById('scanner-status').innerHTML = `
                <div class="text-yellow-400">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>Camera requires HTTPS. Please use manual token entry below.</span>
                </div>
            `;
        }
        
        try {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                    rememberLastUsedCamera: true,
                    showTorchButtonIfSupported: true
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            
            document.getElementById('scanner-status').innerHTML = `
                <i class="fas fa-camera text-green-400 mr-2"></i>
                <span>Scanner ready! Point camera at QR code</span>
            `;
        } catch (error) {
            console.error('Scanner initialization error:', error);
            document.getElementById('scanner-status').innerHTML = `
                <div class="text-yellow-400">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>Camera not available. Please use manual token entry below.</span>
                </div>
            `;
            document.getElementById('qr-reader').innerHTML = `
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-6 text-center">
                    <i class="fas fa-camera-slash text-4xl text-yellow-400 mb-3"></i>
                    <p class="text-yellow-400 font-semibold mb-2">Camera Access Not Available</p>
                    <p class="text-gray-400 text-sm">Please use the manual token entry below to check in.</p>
                </div>
            `;
        }
    }

    // On successful scan
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        
        console.log(`Code scanned: ${decodedText}`);
        
        // Extract token from URL if it's a full URL
        let token = decodedText;
        if (decodedText.includes('/checkin/')) {
            token = decodedText.split('/checkin/')[1];
        }
        
        processCheckIn(token);
    }

    function onScanFailure(error) {
        // Handle scan failure, usually because nothing was scanned
    }

    // Process check-in
    async function processCheckIn(token) {
        if (isProcessing) return;
        isProcessing = true;

        document.getElementById('scanner-status').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i><span>Processing...</span>';

        try {
            // Get user location (optional)
            let latitude = null;
            let longitude = null;
            
            if (navigator.geolocation) {
                try {
                    const position = await new Promise((resolve, reject) => {
                        navigator.geolocation.getCurrentPosition(resolve, reject);
                    });
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                } catch (err) {
                    console.log('Location access denied');
                }
            }

            const response = await fetch('{{ route("qr.service.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    token: token,
                    latitude: latitude,
                    longitude: longitude
                })
            });

            const data = await response.json();

            if (data.success) {
                showSuccess(data.message, data.data);
                loadRecentCheckIns();
                
                // Stop scanner temporarily
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear();
                }
                
                // Restart scanner after 3 seconds
                setTimeout(() => {
                    initScanner();
                    isProcessing = false;
                }, 3000);
            } else {
                showError(data.message);
                isProcessing = false;
            }

            document.getElementById('scanner-status').innerHTML = '<i class="fas fa-camera mr-2"></i><span>Position the QR code within the frame</span>';
        } catch (error) {
            console.error('Check-in error:', error);
            showError('An error occurred. Please try again.');
            document.getElementById('scanner-status').innerHTML = '<i class="fas fa-camera mr-2"></i><span>Position the QR code within the frame</span>';
            isProcessing = false;
        }
    }

    // Show success modal
    function showSuccess(message, data) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('successService').textContent = data.service;
        document.getElementById('successTime').textContent = data.time + ' - ' + data.date;
        document.getElementById('successModal').classList.remove('hidden');
        document.getElementById('successModal').classList.add('flex');
        
        // Play success sound (optional)
        // new Audio('/sounds/success.mp3').play();
    }

    // Show error modal
    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').classList.remove('hidden');
        document.getElementById('errorModal').classList.add('flex');
    }

    // Close modals
    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
        document.getElementById('successModal').classList.remove('flex');
    }

    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
        document.getElementById('errorModal').classList.remove('flex');
    }

    // Manual check-in
    document.getElementById('manualCheckInForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const token = document.getElementById('manualToken').value.trim();
        if (token) {
            processCheckIn(token);
            document.getElementById('manualToken').value = '';
        }
    });

    // Load recent check-ins
    async function loadRecentCheckIns() {
        try {
            const response = await fetch('{{ route("portal.attendance") }}');
            // This would need an API endpoint to return recent check-ins
            // For now, we'll show a placeholder
            document.getElementById('recentCheckIns').innerHTML = `
                <div class="text-center text-gray-400 py-4">
                    <i class="fas fa-info-circle mr-2"></i>
                    Check your attendance history in the Attendance page
                </div>
            `;
        } catch (error) {
            console.error('Error loading recent check-ins:', error);
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initScanner();
        loadRecentCheckIns();
    });
</script>
@endpush
@endsection
