@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50 py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">📱 QR Code</h1>
                    <p class="text-purple-100 text-lg">{{ $equipment->name }}</p>
                </div>
                <a href="{{ route('equipment.show', $equipment) }}" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        <!-- QR Code Display -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Equipment Info -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $equipment->name }}</h2>
                <p class="text-gray-600 mb-1">{{ $equipment->equipment_code }}</p>
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $equipment->category->name ?? 'N/A' }}
                </span>
            </div>

            <!-- QR Code -->
            <div class="flex flex-col items-center justify-center">
                <div id="qrcode" class="bg-white p-8 rounded-xl shadow-lg"></div>
                
                <div class="mt-6 space-y-3 w-full max-w-md">
                    <button onclick="downloadQR()" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-download mr-2"></i>Download QR Code
                    </button>
                    
                    <button onclick="printQR()" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all">
                        <i class="fas fa-print mr-2"></i>Print Label
                    </button>
                    
                    <a href="{{ route('equipment.qr.bulk') }}" class="block w-full text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                        <i class="fas fa-qrcode mr-2"></i>Generate Bulk QR Codes
                    </a>
                </div>
            </div>

            <!-- Equipment Details -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Equipment Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Code</p>
                        <p class="font-semibold text-gray-800">{{ $equipment->equipment_code }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Category</p>
                        <p class="font-semibold text-gray-800">{{ $equipment->category->name ?? 'N/A' }}</p>
                    </div>
                    @if($equipment->brand)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Brand</p>
                            <p class="font-semibold text-gray-800">{{ $equipment->brand }} {{ $equipment->model }}</p>
                        </div>
                    @endif
                    @if($equipment->location)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Location</p>
                            <p class="font-semibold text-gray-800">{{ $equipment->location }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Print Area (hidden on screen) -->
        <div id="printArea" class="hidden print:block">
            <div class="text-center p-8">
                <h1 class="text-2xl font-bold mb-2">{{ $equipment->name }}</h1>
                <p class="text-lg text-gray-600 mb-1">{{ $equipment->equipment_code }}</p>
                <p class="text-sm text-gray-500 mb-6">{{ $equipment->category->name ?? '' }}</p>
                <div id="qrcodePrint" class="inline-block"></div>
                <p class="text-xs text-gray-500 mt-6">Scan to view details</p>
            </div>
        </div>

    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // QR Code Data
    const qrData = @json($equipment->getQrCodeData());
    
    // Generate QR Codes
    new QRCode(document.getElementById("qrcode"), {
        text: qrData,
        width: 256,
        height: 256,
        colorDark: "#1e40af",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    new QRCode(document.getElementById("qrcodePrint"), {
        text: qrData,
        width: 256,
        height: 256,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // Download QR Code
    function downloadQR() {
        const canvas = document.querySelector('#qrcode canvas');
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = '{{ Str::slug($equipment->name) }}-qr-code.png';
        link.href = url;
        link.click();
    }

    // Print QR Code
    function printQR() {
        window.print();
    }
</script>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printArea, #printArea * {
            visibility: visible;
        }
        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>
@endsection
