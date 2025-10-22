@extends('layouts.member-portal')

@section('page-title', 'My Attendance')
@section('page-subtitle', 'QR Code Check-in History')

@section('content')
<div class="space-y-6">
    <!-- Quick Action Banner -->
    <div class="bg-gradient-to-r from-green-500/20 to-blue-500/20 backdrop-blur-xl border border-green-500/30 rounded-2xl p-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-qrcode text-3xl text-white"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">QR Code Check-In</h3>
                    <p class="text-gray-300 text-sm">Scan QR code at church to mark your attendance</p>
                </div>
            </div>
            <a href="{{ route('qr.service.scanner') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg hover:shadow-green-500/50">
                <i class="fas fa-camera mr-2"></i>
                Open Scanner
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-6 rounded-2xl border border-white/10 hover:border-green-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-400 text-sm">Total Check-Ins</p>
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-double text-green-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-white">{{ $stats['total'] }}</p>
            <p class="text-gray-400 text-xs mt-1">All time</p>
        </div>
        
        <div class="glass-card p-6 rounded-2xl border border-white/10 hover:border-blue-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-400 text-sm">This Year</p>
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-blue-300">{{ $stats['this_year'] }}</p>
            <p class="text-gray-400 text-xs mt-1">{{ now()->year }}</p>
        </div>
        
        <div class="glass-card p-6 rounded-2xl border border-white/10 hover:border-purple-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-400 text-sm">This Month</p>
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-purple-300">{{ $stats['this_month'] }}</p>
            <p class="text-gray-400 text-xs mt-1">{{ now()->format('F') }}</p>
        </div>
        
        <div class="glass-card p-6 rounded-2xl border border-white/10 hover:border-orange-500/50 transition-all">
            <div class="flex items-center justify-between mb-2">
                <p class="text-gray-400 text-sm">QR Check-Ins</p>
                <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-qrcode text-orange-400"></i>
                </div>
            </div>
            <p class="text-4xl font-black text-orange-300">{{ $stats['qr_check_ins'] }}</p>
            <p class="text-gray-400 text-xs mt-1">Via QR code</p>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="glass-card p-6 rounded-2xl border border-white/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-history text-green-400 mr-3"></i>
                    Check-In History
                </h3>
                <p class="text-gray-400 text-sm mt-1">Your QR code attendance records</p>
            </div>
            @if($attendance->count() > 0)
            <div class="text-right">
                <p class="text-sm text-gray-400">Latest check-in:</p>
                <p class="text-green-400 font-semibold">{{ $attendance->first()->attendance_date->diffForHumans() }}</p>
            </div>
            @endif
        </div>
        
        <div class="space-y-3">
            @forelse($attendance as $record)
                <div class="bg-white/5 hover:bg-white/10 p-5 rounded-xl border border-white/5 hover:border-green-500/30 transition-all group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Service Icon -->
                            <div class="flex-shrink-0">
                                @if($record->service)
                                    @php
                                        $dayColors = [
                                            'sunday' => 'from-purple-500 to-purple-600',
                                            'monday' => 'from-blue-500 to-blue-600',
                                            'tuesday' => 'from-green-500 to-green-600',
                                            'wednesday' => 'from-yellow-500 to-yellow-600',
                                            'thursday' => 'from-orange-500 to-orange-600',
                                            'friday' => 'from-red-500 to-red-600',
                                            'saturday' => 'from-pink-500 to-pink-600',
                                        ];
                                        $color = $dayColors[$record->service->day_of_week] ?? 'from-gray-500 to-gray-600';
                                    @endphp
                                    <div class="w-14 h-14 bg-gradient-to-br {{ $color }} rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-church text-white text-xl"></i>
                                    </div>
                                @else
                                    <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-calendar text-white text-xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Check-in Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="text-white font-bold text-lg truncate">
                                        @if($record->service)
                                            {{ $record->service->name }}
                                        @else
                                            Church Service
                                        @endif
                                    </h4>
                                    @if($record->check_in_method === 'qr_code')
                                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold flex items-center">
                                            <i class="fas fa-qrcode mr-1"></i> QR
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                                    <span class="text-gray-300 flex items-center">
                                        <i class="far fa-calendar text-green-400 mr-2"></i>
                                        <strong>{{ $record->attendance_date->format('l, M d, Y') }}</strong>
                                    </span>
                                    
                                    <span class="text-gray-300 flex items-center">
                                        <i class="far fa-clock text-blue-400 mr-2"></i>
                                        <strong>{{ \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') }}</strong>
                                    </span>
                                    
                                    @if($record->service)
                                    <span class="text-gray-400 flex items-center">
                                        <i class="fas fa-calendar-week text-purple-400 mr-2"></i>
                                        {{ ucfirst($record->service->day_of_week) }}
                                    </span>
                                    @endif
                                </div>
                                
                                @if($record->service && $record->service->description)
                                <p class="text-gray-500 text-xs mt-2">
                                    {{ Str::limit($record->service->description, 60) }}
                                </p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="flex-shrink-0 ml-4">
                            <div class="flex flex-col items-end space-y-2">
                                <span class="px-4 py-2 rounded-full text-xs font-bold bg-green-500/20 text-green-300 border border-green-500/30">
                                    <i class="fas fa-check-circle mr-1"></i> Checked In
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $record->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-white/5 rounded-full flex items-center justify-center">
                        <i class="fas fa-qrcode text-5xl text-gray-500"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-2">No Check-Ins Yet</h4>
                    <p class="text-gray-400 mb-6">Start tracking your attendance by scanning QR codes at church services</p>
                    <a href="{{ route('qr.service.scanner') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-lg">
                        <i class="fas fa-camera"></i>
                        <span>Open QR Scanner</span>
                    </a>
                </div>
            @endforelse
        </div>

        @if($attendance->hasPages())
        <div class="mt-6 border-t border-white/10 pt-6">
            {{ $attendance->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
