@extends('layouts.app')

@section('page-title', 'Create Volunteer Role')
@section('page-subtitle', 'Add a new volunteer role')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card p-8 rounded-2xl">
        <form method="POST" action="{{ route('volunteers.store') }}">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Role Name *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-green-500"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Department *</label>
                    <select name="department" required 
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                        <option value="">Select Department</option>
                        <option value="worship">Worship</option>
                        <option value="children">Children</option>
                        <option value="hospitality">Hospitality</option>
                        <option value="media">Media</option>
                        <option value="security">Security</option>
                        <option value="ushering">Ushering</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-green-300 mb-2">Required Volunteers *</label>
                    <input type="number" name="required_volunteers" min="1" value="1" required 
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:outline-none focus:border-green-500">
                </div>
            </div>

            <div class="mt-8 flex space-x-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Create Role
                </button>
                <a href="{{ route('volunteers.index') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
