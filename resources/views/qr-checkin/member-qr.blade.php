@extends('layouts.app')

@section('page-title', 'Member QR Code')
@section('page-subtitle', $member->full_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-card p-8 rounded-2xl text-center">
        <!-- Member Info -->
        <div class="mb-8">
            <div class="w-24 h-24 gradient-green rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-white font-black text-4xl">{{ strtoupper(substr($member->first_name, 0, 1)) }}</span>
            </div>
            <h2 class="text-3xl font-black text-white mb-2">{{ $member->full_name }}</h2>
            <p class="text-gray-400">{{ $member->phone }}</p>
        </div>

        <!-- QR Code -->
        <div class="bg-white p-8 rounded-2xl inline-block mb-6">
            <img src="{{ route('qr.member.generate', $member) }}" alt="QR Code" class="w-64 h-64">
        </div>

        <!-- Instructions -->
        <div class="bg-white/5 p-6 rounded-xl mb-6">
            <h3 class="text-lg font-bold text-white mb-3">How to Use</h3>
            <div class="text-left space-y-2 text-gray-300">
                <p><i class="fas fa-check text-green-400 mr-2"></i> Show this QR code at check-in</p>
                <p><i class="fas fa-check text-green-400 mr-2"></i> Scanner will automatically mark your attendance</p>
                <p><i class="fas fa-check text-green-400 mr-2"></i> Save this page for quick access</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex space-x-3 justify-center">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i> Print QR Code
            </button>
            <button onclick="downloadQR()" class="btn btn-primary">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    </div>
</div>

<script>
function downloadQR() {
    const link = document.createElement('a');
    link.href = '{{ route("qr.member.generate", $member) }}';
    link.download = '{{ $member->full_name }}-QR-Code.svg';
    link.click();
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .glass-card, .glass-card * {
        visibility: visible;
    }
    .glass-card {
        position: absolute;
        left: 0;
        top: 0;
    }
    .btn {
        display: none;
    }
}
</style>
@endsection
