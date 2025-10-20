@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-purple-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">📱 Bulk QR Code Generator</h1>
                    <p class="text-purple-100 text-lg">Generate printable QR codes for all equipment</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="window.print()" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-semibold hover:bg-purple-50 transition-all shadow-lg">
                        <i class="fas fa-print mr-2"></i>Print All
                    </button>
                    <a href="{{ route('equipment.index') }}" class="bg-purple-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-purple-400 transition-all shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-6 rounded-lg mb-8 no-print">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-2xl mr-4 mt-1"></i>
                <div>
                    <h3 class="font-bold text-lg mb-2">How to Use:</h3>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Click "Print All" to print labels</li>
                        <li>Cut out individual QR codes</li>
                        <li>Attach labels to equipment using clear tape or label stickers</li>
                        <li>Scan with any QR code reader to view equipment details</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- QR Codes Grid -->
        @if($equipment->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($equipment as $item)
                    <div class="bg-white rounded-xl shadow-lg p-6 print:break-inside-avoid print:border print:border-gray-300">
                        <div class="text-center">
                            <h3 class="font-bold text-gray-800 mb-1 text-lg truncate">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-600 mb-1">{{ $item->equipment_code }}</p>
                            <p class="text-xs text-gray-500 mb-4">{{ $item->category->name ?? 'N/A' }}</p>
                            
                            <!-- QR Code -->
                            <div class="qrcode-container inline-block mb-4" data-qr="{{ $item->getQrCodeData() }}"></div>
                            
                            <!-- Equipment Info -->
                            <div class="text-xs text-gray-500 space-y-1">
                                @if($item->location)
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt w-4"></i>
                                        <span>{{ $item->location }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-center">
                                    @php
                                        $statusColors = [
                                            'available' => 'text-green-600',
                                            'in_use' => 'text-yellow-600',
                                            'maintenance' => 'text-orange-600',
                                            'retired' => 'text-gray-600',
                                        ];
                                    @endphp
                                    <i class="fas fa-circle {{ $statusColors[$item->status] ?? 'text-gray-600' }} w-4 text-xs"></i>
                                    <span class="capitalize">{{ str_replace('_', ' ', $item->status) }}</span>
                                </div>
                            </div>
                            
                            <!-- Actions (no print) -->
                            <div class="mt-4 no-print">
                                <a href="{{ route('equipment.show', $item) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-boxes text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">No Equipment Found</h3>
                <p class="text-gray-600 mb-6">Add equipment to generate QR codes</p>
                <a href="{{ route('equipment.create') }}" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-all">
                    <i class="fas fa-plus mr-2"></i>Add Equipment
                </a>
            </div>
        @endif>

    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    // Generate all QR codes
    document.addEventListener('DOMContentLoaded', function() {
        const qrContainers = document.querySelectorAll('.qrcode-container');
        
        qrContainers.forEach(container => {
            const qrData = container.dataset.qr;
            new QRCode(container, {
                text: qrData,
                width: 180,
                height: 180,
                colorDark: "#1e40af",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    });
</script>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .grid {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 1.5rem !important;
        }
        
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>
@endsection
