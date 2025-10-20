@extends('layouts.app')

@section('content')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-sms mr-3"></i>
                    Compose SMS Campaign
                </h1>
                <p class="mt-2">Send SMS messages to your members</p>
            </div>
            <a href="{{ route('sms.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('sms.store') }}">
            @csrf
            <div class="space-y-6">
                <!-- Campaign Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Sunday Service Reminder">
                </div>

                <!-- Template Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Use Template (Optional)</label>
                    <select name="template_id" id="templateSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select a template --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" data-content="{{ $template->content }}">
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select a template to auto-fill the message</p>
                </div>

                <!-- Recipients -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients *</label>
                    <select name="recipient_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="all_members">All Active Members</option>
                        <option value="specific_members">Specific Members</option>
                        <option value="group">By Group/Cluster</option>
                        <option value="custom">Custom Phone Numbers</option>
                    </select>
                </div>

                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                    <textarea name="message" id="messageText" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Type your message here...">{{ old('message') }}</textarea>
                    <div class="flex justify-between mt-2">
                        <p class="text-xs text-gray-500">Character count: <span id="charCount">0</span>/500</p>
                        <p class="text-xs text-gray-500">SMS parts: <span id="smsCount">0</span></p>
                    </div>
                </div>

                <!-- Schedule -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <input type="checkbox" id="scheduleCheckbox" class="mr-2">
                        Schedule for later
                    </label>
                    <input type="datetime-local" name="scheduled_at" id="scheduledAt" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 hidden">
                </div>

                <!-- Actions -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-paper-plane mr-2"></i> Create Campaign
                    </button>
                    <a href="{{ route('sms.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-medium">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- SMS Preview -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Preview</h3>
        <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
            <div class="bg-blue-500 text-white rounded-lg p-3 max-w-xs">
                <p id="previewText" class="text-sm">Your message will appear here...</p>
            </div>
        </div>
    </div>
</div>

<script>
// Template selection
document.getElementById('templateSelect').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const content = selectedOption.getAttribute('data-content');
    if (content) {
        document.getElementById('messageText').value = content;
        updateCharCount();
    }
});

// Character count
const messageText = document.getElementById('messageText');
const charCount = document.getElementById('charCount');
const smsCount = document.getElementById('smsCount');
const previewText = document.getElementById('previewText');

function updateCharCount() {
    const length = messageText.value.length;
    charCount.textContent = length;
    smsCount.textContent = Math.ceil(length / 160) || 0;
    previewText.textContent = messageText.value || 'Your message will appear here...';
}

messageText.addEventListener('input', updateCharCount);

// Schedule checkbox
document.getElementById('scheduleCheckbox').addEventListener('change', function() {
    document.getElementById('scheduledAt').classList.toggle('hidden', !this.checked);
});
</script>
@endsection