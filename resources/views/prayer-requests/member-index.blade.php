@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2 flex items-center">
                    <i class="fas fa-praying-hands mr-3 text-green-400"></i>
                    Prayer Requests
                </h1>
                <p class="text-gray-300">Submit and view prayer needs</p>
            </div>
            <a href="{{ route('prayer-requests.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg">
                <i class="fas fa-plus mr-2"></i> Submit Prayer Request
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-list text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Answered</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['answered'] }}</p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Public Requests</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['public'] }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-globe text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prayer Requests List -->
    @if($prayerRequests->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($prayerRequests as $request)
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $request->category == 'health' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $request->category == 'family' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $request->category == 'financial' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $request->category == 'spiritual' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $request->category == 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ ucfirst($request->category) }}
                                </span>
                                @if($request->is_urgent)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Urgent
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $request->title }}</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $request->status == 'praying' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $request->status == 'answered' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $request->description }}</p>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2"></i>
                            {{ $request->created_at->diffForHumans() }}
                        </div>
                        <a href="{{ route('prayer-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-sm">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $prayerRequests->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <i class="fas fa-praying-hands text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No Prayer Requests Yet</h3>
            <p class="text-gray-600 mb-6">Submit your first prayer request and let the church pray with you</p>
            <a href="{{ route('prayer-requests.create') }}" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i> Submit Prayer Request
            </a>
        </div>
    @endif
</div>
@endsection
