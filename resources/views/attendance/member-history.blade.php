@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">{{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-white">{{ $member->full_name }}</h1>
                    <p class="text-gray-300">Attendance History</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('attendance.qrcode', $member) }}" class="btn btn-primary">
                    <i class="fas fa-qrcode mr-2"></i> View QR Code
                </a>
                <a href="{{ route('members.show', $member) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Total Attendances</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['total_attendances'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">This Month</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['this_month'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-month text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">This Year</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['this_year'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-year text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Attendance Rate</p>
                    <p class="text-3xl font-bold text-white">{{ $stats['attendance_rate'] }}%</p>
                    <p class="text-xs text-gray-400">Last 3 months</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Last Attendance Info -->
    @if($stats['last_attendance'])
    <div class="glass-card p-6 rounded-2xl mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-green-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Last Attendance</p>
                <p class="text-white text-xl font-bold">{{ $stats['last_attendance']->attendance_date->format('l, M d, Y') }}</p>
                <p class="text-gray-300">{{ $stats['last_attendance']->service->service_name ?? 'Unknown Service' }} • {{ $stats['last_attendance']->check_in_time->format('g:i A') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendance Records -->
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">Attendance Records</h2>
        
        @if($attendanceRecords->count() > 0)
            <div class="space-y-3">
                @foreach($attendanceRecords as $record)
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl hover:bg-white/10 transition">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $record->service->service_name ?? 'Unknown Service' }}</p>
                            <p class="text-gray-400 text-sm">{{ $record->attendance_date->format('l, M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-semibold">{{ $record->check_in_time->format('g:i A') }}</p>
                        <p class="text-gray-400 text-xs">
                            @if($record->check_in_method === 'qr')
                                <i class="fas fa-qrcode mr-1"></i> QR Code
                            @elseif($record->check_in_method === 'manual')
                                <i class="fas fa-hand-point-up mr-1"></i> Manual
                            @else
                                <i class="fas fa-mobile mr-1"></i> Mobile
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $attendanceRecords->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg">No attendance records found</p>
            </div>
        @endif
    </div>
</div>
@endsection
