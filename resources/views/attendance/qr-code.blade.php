@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 flex items-center justify-center p-6">
    <div class="max-w-2xl w-full">
        
        <!-- QR Code Card -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-center">
                <h1 class="text-3xl font-black text-white mb-2">
                    📱 Member QR Code
                </h1>
                <p class="text-blue-100">Scan for Quick Check-In</p>
            </div>

            <!-- Member Info -->
            <div class="p-8 text-center">
                <div class="mb-6">
                    @if($member->profile_photo)
                        <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                             alt="{{ $member->first_name }}" 
                             class="w-24 h-24 rounded-full mx-auto border-4 border-blue-500 shadow-lg object-cover">
                    @else
                        <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center border-4 border-blue-500 shadow-lg">
                            <span class="text-3xl font-bold text-white">
                                {{ substr($member->first_name, 0, 1) }}{{ substr($member->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <h2 class="text-2xl font-bold text-white mb-1">
                    {{ $member->first_name }} {{ $member->last_name }}
                </h2>
                <p class="text-blue-300 mb-2">{{ $member->email }}</p>
                <p class="text-gray-400 text-sm">Member ID: {{ $member->id }}</p>
            </div>

            <!-- QR Code -->
            <div class="p-8 bg-white/5">
                <div class="bg-white rounded-2xl p-8 flex items-center justify-center shadow-xl mx-auto max-w-sm">
                    {!! $qrImage !!}
                </div>
                
                <div class="mt-6 text-center">
                    <p class="text-white font-semibold mb-2">QR Code: {{ $qrCode->qr_code }}</p>
                    <div class="flex items-center justify-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $qrCode->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $qrCode->is_active ? '✓ Active' : '✗ Inactive' }}
                        </span>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-xs font-semibold">
                            Created: {{ $qrCode->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="p-6 bg-gradient-to-r from-purple-500/10 to-blue-500/10 border-t border-white/10">
                <h3 class="text-white font-bold mb-3 flex items-center justify-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    How to Use
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-lg">1</span>
                        </div>
                        <p class="text-blue-300 font-semibold">Save QR Code</p>
                        <p class="text-gray-400 text-xs">Download or screenshot</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-lg">2</span>
                        </div>
                        <p class="text-green-300 font-semibold">Show at Check-In</p>
                        <p class="text-gray-400 text-xs">Present to scanner</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-lg">3</span>
                        </div>
                        <p class="text-purple-300 font-semibold">Instant Check-In</p>
                        <p class="text-gray-400 text-xs">Attendance recorded</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-white/5 border-t border-white/10">
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="downloadQR()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </button>
                    <button onclick="printQR()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-print mr-2"></i>
                        Print
                    </button>
                    <button onclick="shareQR()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl text-white font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-share-alt mr-2"></i>
                        Share
                    </button>
                    <a href="{{ route('attendance.index') }}" class="px-6 py-3 bg-white/10 rounded-xl text-white font-semibold hover:bg-white/20 transition-all text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>

        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-gray-400 text-sm">
                <i class="fas fa-shield-alt mr-1"></i>
                This QR code is unique to {{ $member->first_name }} and should not be shared
            </p>
        </div>

    </div>
</div>

<script>
function downloadQR() {
    // Get the QR code SVG
    const svg = document.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(svg);
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    const img = new Image();
    
    canvas.width = 300;
    canvas.height = 300;
    
    img.onload = function() {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
        
        const a = document.createElement('a');
        a.download = 'qr-code-{{ $member->first_name }}-{{ $member->last_name }}.png';
        a.href = canvas.toDataURL('image/png');
        a.click();
    };
    
    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
}

function printQR() {
    window.print();
}

function shareQR() {
    if (navigator.share) {
        navigator.share({
            title: 'My Church QR Code',
            text: 'Check-in QR Code for {{ $member->first_name }} {{ $member->last_name }}',
            url: window.location.href
        }).then(() => {
            console.log('Shared successfully');
        }).catch((error) => {
            copyLink();
        });
    } else {
        copyLink();
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    alert('✅ Link copied to clipboard!');
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white {
        visibility: visible !important;
    }
    .bg-white * {
        visibility: visible !important;
    }
    .bg-white {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
}
</style>
@endsection
