@extends('layouts.app')

@section('page-title', 'Generate QR Codes')
@section('page-subtitle', 'Generate QR codes for all members')

@section('content')
<div>
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-4">Generate Member QR Codes</h2>
            <p class="text-gray-300 mb-6">Generate QR codes for all members so they can check in with their phones</p>
            
            <form method="POST" action="{{ route('qr.bulk.generate') }}" class="inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-qrcode"></i> Generate All QR Codes
                </button>
            </form>
        </div>
    </div>

    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-xl font-bold text-white mb-6">All Members with QR Codes</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $members = \App\Models\Member::whereNotNull('qr_code')->get();
            @endphp
            
            @forelse($members as $member)
                <div class="bg-white/5 p-6 rounded-2xl text-center">
                    <div class="w-24 h-24 gradient-green rounded-2xl flex items-center justify-center text-white font-black text-3xl mx-auto mb-4">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                    
                    <h4 class="text-white font-bold text-lg mb-2">{{ $member->full_name }}</h4>
                    <p class="text-gray-400 text-sm mb-4">{{ $member->phone }}</p>
                    
                    <div class="bg-white p-4 rounded-xl mb-4">
                        <img src="{{ route('qr.member.generate', $member) }}" alt="QR Code" class="w-full">
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('qr.member.show', $member) }}" target="_blank" class="btn btn-secondary btn-sm flex-1">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('qr.member.generate', $member) }}" download="qr-{{ $member->member_id }}.png" class="btn btn-primary btn-sm flex-1">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16">
                    <i class="fas fa-qrcode text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg mb-6">No QR codes generated yet</p>
                    <form method="POST" action="{{ route('qr.bulk.generate') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-qrcode"></i> Generate QR Codes Now
                        </button>
                    </form>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
