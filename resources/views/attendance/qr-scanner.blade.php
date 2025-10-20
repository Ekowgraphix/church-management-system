@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">
                    <i class="fas fa-qrcode mr-3 text-neon-green"></i>
                    QR Code Scanner
                </h1>
                <p class="text-gray-300">Scan member QR codes for quick check-in</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Scanner Section -->
        <div class="lg:col-span-2">
            <div class="glass-card p-6 rounded-2xl">
                <!-- Service Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Select Service *</label>
                    <select id="service_id" class="input-field" required>
                        <option value="">Choose a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->service_name }} - {{ $service->service_time }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- QR Scanner -->
                <div id="qr-reader" class="rounded-xl overflow-hidden mb-4"></div>
                
                <div class="text-center">
                    <button id="start-scan" onclick="startScanner()" class="btn btn-primary mr-2">
                        <i class="fas fa-play mr-2"></i> Start Scanning
                    </button>
                    <button id="stop-scan" onclick="stopScanner()" class="btn btn-secondary hidden">
                        <i class="fas fa-stop mr-2"></i> Stop Scanning
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Check-ins -->
        <div>
            <div class="glass-card p-6 rounded-2xl">
                <h2 class="text-xl font-bold text-white mb-4">Recent Check-ins</h2>
                <div id="recent-checkins" class="space-y-3">
                    <p class="text-gray-400 text-center py-8">No check-ins yet</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="glass-card p-6 rounded-2xl mt-6">
                <h2 class="text-xl font-bold text-white mb-4">Today's Stats</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Total Check-ins</span>
                        <span id="total-count" class="text-2xl font-bold text-white">0</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">This Service</span>
                        <span id="service-count" class="text-2xl font-bold text-neon-green">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-lg hidden transform transition-all">
    <div class="flex items-center space-x-3">
        <i class="fas fa-check-circle text-2xl"></i>
        <div>
            <p class="font-bold">Check-in Successful!</p>
            <p id="success-name" class="text-sm"></p>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div id="error-toast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-xl shadow-lg hidden transform transition-all">
    <div class="flex items-center space-x-3">
        <i class="fas fa-exclamation-circle text-2xl"></i>
        <div>
            <p class="font-bold">Check-in Failed</p>
            <p id="error-text" class="text-sm"></p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner;
let recentCheckins = [];

function startScanner() {
    const serviceId = document.getElementById('service_id').value;
    if (!serviceId) {
        showError('Please select a service first');
        return;
    }

    html5QrcodeScanner = new Html5Qrcode("qr-reader");
    
    html5QrcodeScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        onScanError
    );

    document.getElementById('start-scan').classList.add('hidden');
    document.getElementById('stop-scan').classList.remove('hidden');
}

function stopScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.stop();
        document.getElementById('start-scan').classList.remove('hidden');
        document.getElementById('stop-scan').classList.add('hidden');
    }
}

function onScanSuccess(decodedText) {
    const serviceId = document.getElementById('service_id').value;

    fetch('{{ route("attendance.checkin.qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            qr_code: decodedText,
            service_id: serviceId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            showError(data.error);
        } else {
            showSuccess(data.member?.full_name || 'Member');
            addRecentCheckin(data.member);
            updateStats();
        }
    })
    .catch(error => {
        showError('Check-in failed. Please try again.');
    });
}

function onScanError(error) {
    // Silent error handling
}

function showSuccess(name) {
    document.getElementById('success-name').textContent = name;
    const toast = document.getElementById('success-toast');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function showError(message) {
    document.getElementById('error-text').textContent = message;
    const toast = document.getElementById('error-toast');
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function addRecentCheckin(member) {
    recentCheckins.unshift({
        name: member.full_name,
        time: new Date().toLocaleTimeString()
    });
    
    if (recentCheckins.length > 10) recentCheckins.pop();
    
    updateRecentCheckins();
}

function updateRecentCheckins() {
    const container = document.getElementById('recent-checkins');
    if (recentCheckins.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-center py-8">No check-ins yet</p>';
        return;
    }

    container.innerHTML = recentCheckins.map(checkin => `
        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-white font-semibold">${checkin.name}</span>
            </div>
            <span class="text-gray-400 text-sm">${checkin.time}</span>
        </div>
    `).join('');
}

function updateStats() {
    // Update statistics (you can fetch from API)
    const totalEl = document.getElementById('total-count');
    const serviceEl = document.getElementById('service-count');
    
    totalEl.textContent = parseInt(totalEl.textContent) + 1;
    serviceEl.textContent = parseInt(serviceEl.textContent) + 1;
}
</script>
@endsection
