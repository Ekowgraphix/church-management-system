@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <h1 class="text-3xl font-black text-white mb-6">
            <i class="fas fa-video mr-3 text-neon-green"></i>
            Add Media Team Member
        </h1>

        <form method="POST" action="{{ route('media-teams.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Member *</label>
                    <select name="member_id" required class="input-field">
                        <option value="">Select member...</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Role *</label>
                    <select name="role" required class="input-field">
                        <option value="">Select role...</option>
                        <option value="Camera">Camera</option>
                        <option value="Sound">Sound</option>
                        <option value="Livestream">Livestream</option>
                        <option value="Editing">Editing</option>
                        <option value="Graphics">Graphics</option>
                        <option value="Lighting">Lighting</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Availability</label>
                    <input type="text" name="availability" class="input-field" value="{{ old('availability') }}" placeholder="e.g., Sundays only">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Assigned Program</label>
                    <input type="text" name="assigned_program" class="input-field" value="{{ old('assigned_program') }}">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="input-field">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i> Add Member
                </button>
                <a href="{{ route('media-teams.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
