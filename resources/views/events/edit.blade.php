@extends('layouts.app')

@section('page-title', 'Edit Event')
@section('page-subtitle', 'Update event information')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Title *</label>
                    <input type="text" name="title" value="{{ old('title', $event->title) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">{{ old('description', $event->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Type *</label>
                    <select name="event_type" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Type</option>
                        <option value="service" {{ $event->event_type == 'service' ? 'selected' : '' }}>Service</option>
                        <option value="meeting" {{ $event->event_type == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="conference" {{ $event->event_type == 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="social" {{ $event->event_type == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="outreach" {{ $event->event_type == 'outreach' ? 'selected' : '' }}>Outreach</option>
                        <option value="training" {{ $event->event_type == 'training' ? 'selected' : '' }}>Training</option>
                        <option value="other" {{ $event->event_type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Status *</label>
                    <select name="status" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="upcoming" {{ $event->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ $event->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ $event->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $event->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Start Date & Time *</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">End Date & Time *</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Capacity</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Registration Fee (₵)</label>
                    <input type="number" name="registration_fee" value="{{ old('registration_fee', $event->registration_fee) }}" min="0" step="0.01" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3 text-white cursor-pointer">
                        <input type="checkbox" name="requires_registration" value="1" {{ $event->requires_registration ? 'checked' : '' }}
                               class="w-4 h-4 text-green-500 bg-white/10 border-white/20 rounded focus:ring-green-500">
                        <span>Requires Registration</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Image</label>
                    @if($event->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-32 h-32 object-cover rounded-xl">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Update Event
                </button>
                <a href="{{ route('events.show', $event) }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
