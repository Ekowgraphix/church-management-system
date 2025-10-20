@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass-card p-8 rounded-2xl">
        <h1 class="text-3xl font-black text-white mb-6">
            <i class="fas fa-edit mr-3 text-neon-green"></i>
            Edit Partnership
        </h1>

        <form method="POST" action="{{ route('partnerships.update', $partnership) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Organization Name *</label>
                    <input type="text" name="organization" required class="input-field" value="{{ old('organization', $partnership->organization) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Contact Person *</label>
                    <input type="text" name="contact_person" required class="input-field" value="{{ old('contact_person', $partnership->contact_person) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Phone *</label>
                    <input type="text" name="phone" required class="input-field" value="{{ old('phone', $partnership->phone) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" class="input-field" value="{{ old('email', $partnership->email) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Status *</label>
                    <select name="status" required class="input-field">
                        <option value="Active" {{ $partnership->status == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending" {{ $partnership->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Inactive" {{ $partnership->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Start Date *</label>
                    <input type="date" name="start_date" required class="input-field" value="{{ old('start_date', $partnership->start_date->format('Y-m-d')) }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">End Date</label>
                    <input type="date" name="end_date" class="input-field" value="{{ old('end_date', $partnership->end_date ? $partnership->end_date->format('Y-m-d') : '') }}">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Contribution Type</label>
                    <input type="text" name="contribution" class="input-field" value="{{ old('contribution', $partnership->contribution) }}">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Terms</label>
                    <textarea name="terms" rows="3" class="input-field">{{ old('terms', $partnership->terms) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="input-field">{{ old('notes', $partnership->notes) }}</textarea>
                </div>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i> Update Partnership
                </button>
                <a href="{{ route('partnerships.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
