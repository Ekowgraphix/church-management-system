@extends('layouts.member-portal')

@section('content')
<div>
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('events.index') }}" class="text-green-400 hover:text-green-300 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Events</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Event Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Event Image -->
                <div class="h-96 bg-gradient-to-br from-purple-500 to-pink-600 relative">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-white text-9xl opacity-30"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        <span class="px-4 py-2 rounded-full text-sm font-bold
                            {{ $event->status == 'upcoming' ? 'bg-blue-500' : '' }}
                            {{ $event->status == 'ongoing' ? 'bg-green-500' : '' }}
                            {{ $event->status == 'completed' ? 'bg-gray-500' : '' }}
                            text-white">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                </div>

                <!-- Event Info -->
                <div class="p-8">
                    <div class="flex items-center mb-4">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                            {{ $event->event_type == 'service' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $event->event_type == 'meeting' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $event->event_type == 'conference' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $event->event_type == 'social' ? 'bg-pink-100 text-pink-700' : '' }}
                            {{ $event->event_type == 'outreach' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $event->event_type == 'training' ? 'bg-cyan-100 text-cyan-700' : '' }}">
                            {{ ucfirst($event->event_type) }}
                        </span>
                    </div>

                    <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $event->title }}</h1>
                    
                    <!-- Date, Time, Location -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-calendar-alt text-indigo-500 text-xl w-8"></i>
                            <div>
                                <p class="font-semibold">Start Date</p>
                                <p>{{ $event->start_date->format('l, F j, Y - h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-calendar-check text-indigo-500 text-xl w-8"></i>
                            <div>
                                <p class="font-semibold">End Date</p>
                                <p>{{ $event->end_date->format('l, F j, Y - h:i A') }}</p>
                            </div>
                        </div>
                        @if($event->location)
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-map-marker-alt text-indigo-500 text-xl w-8"></i>
                                <div>
                                    <p class="font-semibold">Location</p>
                                    <p>{{ $event->location }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">About This Event</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                    </div>

                    <!-- Registration Fee -->
                    @if($event->registration_fee > 0)
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-600">Registration Fee</p>
                                    <p class="text-2xl font-bold text-green-600">₵{{ number_format($event->registration_fee) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Registration -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Register for Event</h3>
                
                @if($event->capacity)
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Capacity</span>
                            <span class="font-bold text-gray-800">{{ $event->registrations->count() }} / {{ $event->capacity }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ ($event->registrations->count() / $event->capacity) * 100 }}%"></div>
                        </div>
                    </div>
                @endif

                @if($event->requires_registration)
                    <form action="{{ route('events.register', $event) }}" method="POST">
                        @csrf
                        
                        @if($event->registration_fee > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    You'll be redirected to payment after registration.
                                </p>
                            </div>
                        @endif

                        <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i>
                            Register Now
                        </button>
                    </form>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <i class="fas fa-info-circle text-blue-600 text-2xl mb-2"></i>
                        <p class="text-blue-800 font-semibold">No registration required</p>
                        <p class="text-blue-600 text-sm mt-1">Just show up!</p>
                    </div>
                @endif

                <!-- Share Event -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">Share this event</p>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event)) }}" 
                           target="_blank"
                           class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('events.show', $event)) }}&text={{ urlencode($event->title) }}" 
                           target="_blank"
                           class="flex-1 bg-sky-500 text-white py-2 rounded-lg text-center hover:bg-sky-600 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . route('events.show', $event)) }}" 
                           target="_blank"
                           class="flex-1 bg-green-600 text-white py-2 rounded-lg text-center hover:bg-green-700 transition">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
