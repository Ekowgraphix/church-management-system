@extends('layouts.app')

@section('page-title', 'QR Check-In Scanner')
@section('page-subtitle', 'Scan member QR codes for instant check-in')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Scanner Card -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="text-center mb-8">
            <div class="w-24 h-24 gradient-green rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                <i class="fas fa-qrcode text-white text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white mb-2">QR Code Scanner</h2>
            <p class="text-gray-400">Point camera at member's QR code</p>
        </div>

        <!-- Camera Preview -->
        <div class="relative mb-6">
            <div id="reader" class="rounded-2xl overflow-hidden border-4 border-green-500/50"></div>
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 border-4 border-green-500 rounded-2xl animate-pulse"></div>
            </div>
        </div>

        <!-- Manual Entry -->
        <div class="text-center">
            <button onclick="toggleManualEntry()" class="btn btn-outline btn-sm">
                <i class="fas fa-keyboard"></i> Enter Code Manually
            </button>
        </div>

        <!-- Manual Entry Form -->
        <div id="manualEntry" class="hidden mt-6">
            <form onsubmit="manualCheckIn(event)" class="flex space-x-3">
                <input type="text" id="manualQRCode" placeholder="Enter QR code..." 
                       class="flex-1 px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Check In
                </button>
            </form>
        </div>
    </div>

    <!-- Recent Check-ins -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-xl font-bold text-white mb-4">Recent Check-ins</h3>
        <div id="recentCheckIns" class="space-y-3">
            <p class="text-gray-400 text-center py-4">No check-ins yet</p>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="glass-card max-w-md w-full mx-4 p-8 rounded-2xl text-center animate-fade-in">
        <div class="w-20 h-20 gradient-green rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-white text-4xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Check-in Successful!</h3>
        <p class="text-green-300 text-lg mb-1" id="memberName"></p>
        <p class="text-gray-400 text-sm mb-6" id="checkInTime"></p>
        <button onclick="closeSuccessModal()" class="btn btn-primary">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="glass-card max-w-md w-full mx-4 p-8 rounded-2xl text-center animate-fade-in">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-times text-red-400 text-4xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Check-in Failed</h3>
        <p class="text-red-300 mb-6" id="errorMessage"></p>
        <button onclick="closeErrorModal()" class="btn btn-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let recentCheckIns = [];

// Initialize QR Scanner
function initScanner() {
    html5QrCode = new Html5Qrcode("reader");
    
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Unable to start scanner", err);
    });
}

function onScanSuccess(decodedText, decodedResult) {
    // Stop scanning temporarily
    html5QrCode.pause();
    
    // Process check-in
    processCheckIn(decodedText);
}

function onScanFailure(error) {
    // Ignore scan failures
}

function processCheckIn(qrCode) {
    fetch('{{ route("qr.checkin.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ qr_code: qrCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.member);
            addToRecentCheckIns(data.member);
            
            // Resume scanning after 2 seconds
            setTimeout(() => {
                if (html5QrCode) {
                    html5QrCode.resume();
                }
            }, 2000);
        } else {
            showError(data.message);
            setTimeout(() => {
                if (html5QrCode) {
                    html5QrCode.resume();
                }
            }, 2000);
        }
    })
    .catch(error => {
        showError('Network error. Please try again.');
        setTimeout(() => {
            if (html5QrCode) {
                html5QrCode.resume();
            }
        }, 2000);
    });
}

function showSuccess(member) {
    document.getElementById('memberName').textContent = member.name;
    document.getElementById('checkInTime').textContent = 'Checked in at ' + member.time;
    document.getElementById('successModal').classList.remove('hidden');
}

function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    document.getElementById('errorModal').classList.remove('hidden');
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}

function closeErrorModal() {
    document.getElementById('errorModal').classList.add('hidden');
}

function addToRecentCheckIns(member) {
    recentCheckIns.unshift(member);
    if (recentCheckIns.length > 5) recentCheckIns.pop();
    
    const container = document.getElementById('recentCheckIns');
    container.innerHTML = recentCheckIns.map(m => `
        <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 gradient-green rounded-xl flex items-center justify-center text-white font-bold">
                    ${m.name.charAt(0)}
                </div>
                <div>
                    <p class="text-white font-semibold">${m.name}</p>
                    <p class="text-gray-400 text-sm">${m.phone}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-green-300 text-sm font-semibold">${m.time}</p>
            </div>
        </div>
    `).join('');
}

function toggleManualEntry() {
    document.getElementById('manualEntry').classList.toggle('hidden');
}

function manualCheckIn(event) {
    event.preventDefault();
    const qrCode = document.getElementById('manualQRCode').value;
    if (qrCode) {
        processCheckIn(qrCode);
        document.getElementById('manualQRCode').value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initScanner);
</script>
@endsection
