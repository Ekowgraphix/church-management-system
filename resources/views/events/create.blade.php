@extends('layouts.app')

@section('page-title', 'Create Event')
@section('page-subtitle', 'Add a new church event')

@section('content')
<div class="max-w-4xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Title *</label>
                    <input type="text" name="title" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Description</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Type *</label>
                    <select name="event_type" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Type</option>
                        <option value="service">Service</option>
                        <option value="meeting">Meeting</option>
                        <option value="conference">Conference</option>
                        <option value="social">Social</option>
                        <option value="outreach">Outreach</option>
                        <option value="training">Training</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Location</label>
                    <input type="text" name="location" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Start Date & Time *</label>
                    <input type="datetime-local" name="start_date" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">End Date & Time *</label>
                    <input type="datetime-local" name="end_date" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Capacity</label>
                    <input type="number" name="capacity" min="1" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Registration Fee (₵)</label>
                    <input type="number" name="registration_fee" min="0" step="0.01" value="0" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div class="md:col-span-2">
                    <label class="flex items-center space-x-3 text-white cursor-pointer">
                        <input type="checkbox" name="requires_registration" value="1" 
                               class="w-4 h-4 text-green-500 bg-white/10 border-white/20 rounded focus:ring-green-500">
                        <span>Requires Registration</span>
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-green-300 mb-2">Event Image</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Create Event
                </button>
                <a href="{{ route('events.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
