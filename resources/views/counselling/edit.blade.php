@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <h1 class="text-3xl font-black text-white mb-6">
            <i class="fas fa-edit mr-3 text-neon-green"></i>
            Edit Counselling Session
        </h1>

        <form method="POST" action="{{ route('counselling.update', $counselling) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Counsellor *</label>
                    <input type="text" name="counsellor" required class="input-field" value="{{ old('counsellor', $counselling->counsellor) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Session Date *</label>
                    <input type="date" name="session_date" required class="input-field" value="{{ old('session_date', $counselling->session_date->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Member (Optional)</label>
                    <select name="member_id" class="input-field">
                        <option value="">Select member...</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $counselling->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Visitor (Optional)</label>
                    <select name="visitor_id" class="input-field">
                        <option value="">Select visitor...</option>
                        @foreach($visitors as $visitor)
                            <option value="{{ $visitor->id }}" {{ $counselling->visitor_id == $visitor->id ? 'selected' : '' }}>
                                {{ $visitor->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Confidentiality *</label>
                    <select name="confidentiality" required class="input-field">
                        <option value="Normal" {{ $counselling->confidentiality == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Private" {{ $counselling->confidentiality == 'Private' ? 'selected' : '' }}>Private</option>
                        <option value="Strict" {{ $counselling->confidentiality == 'Strict' ? 'selected' : '' }}>Strict</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Follow-up Date</label>
                    <input type="date" name="follow_up_date" class="input-field" value="{{ old('follow_up_date', $counselling->follow_up_date ? $counselling->follow_up_date->format('Y-m-d') : '') }}">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Issues Discussed *</label>
                    <textarea name="issues" required rows="3" class="input-field">{{ old('issues', $counselling->issues) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Outcome/Advice</label>
                    <textarea name="outcome" rows="3" class="input-field">{{ old('outcome', $counselling->outcome) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="input-field">{{ old('notes', $counselling->notes) }}</textarea>
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i> Update Session
                </button>
                <a href="{{ route('counselling.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
