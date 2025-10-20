@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Attendance Management
                </h1>
                <p class="mt-2">Mark and track member attendance</p>
            </div>
            <button onclick="document.getElementById('markAttendanceModal').classList.remove('hidden')" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> Mark Attendance
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Attendance</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['total_today'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-users text-2xl text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Members Present</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['members_today'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Visitors</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['visitors_today'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-user-plus text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Attendance Rate</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['total_members'] > 0 ? round(($stats['members_today'] / $stats['total_members']) * 100) : 0 }}%</p>
                </div>
                <div class="bg-orange-100 rounded-full p-4">
                    <i class="fas fa-chart-line text-2xl text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance List -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">Today's Attendance ({{ $attendances->total() }})</h2>
            <div class="flex space-x-2">
                <input type="date" value="{{ request('date', date('Y-m-d')) }}" onchange="window.location.href='?date='+this.value" class="px-4 py-2 border border-gray-300 rounded-lg">
            </div>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($attendances as $index => $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $attendances->firstItem() + $index }}</td>
                                <td class="px-6 py-4">
                                    @if($attendance->member)
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                {{ strtoupper(substr($attendance->member->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $attendance->member->full_name }}</p>
                                                <p class="text-sm text-gray-500">Member</p>
                                            </div>
                                        </div>
                                    @elseif($attendance->visitor)
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                                V
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $attendance->visitor->first_name }} {{ $attendance->visitor->last_name }}</p>
                                                <p class="text-sm text-gray-500">Visitor</p>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $attendance->member ? $attendance->member->phone : ($attendance->visitor ? $attendance->visitor->phone : 'N/A') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $attendance->check_in_time ? $attendance->check_in_time->format('h:i A') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ ucfirst($attendance->check_in_method ?? 'manual') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->notes ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No attendance records for today</p>
                <p class="text-gray-400 text-sm">Click "Mark Attendance" to add records</p>
            </div>
        @endif
    </div>
</div>

<!-- Mark Attendance Modal -->
<div id="markAttendanceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Mark Attendance</h3>
            <button onclick="document.getElementById('markAttendanceModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('attendance.checkin') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Service *</label>
                    <select name="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Choose a service...</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} - {{ ucfirst($service->day_of_week) }} {{ \Carbon\Carbon::parse($service->start_time)->format('h:i A') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Member *</label>
                    <select name="member_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Choose a member...</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }} - {{ $member->phone }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="Any additional notes..."></textarea>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-check mr-2"></i> Mark Present
                    </button>
                    <button type="button" onclick="document.getElementById('markAttendanceModal').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection