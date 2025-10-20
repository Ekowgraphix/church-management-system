<?php

// Create missing view files
$views = [
    'resources/views/members/show.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Member Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route(\'members.edit\', $member) }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route(\'members.index\') }}" class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-600">Full Name</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->full_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Member ID</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->member_id ?? \'N/A\' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Phone</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->phone }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Email</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->email ?? \'N/A\' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Status</label>
                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($member->membership_status ?? \'active\') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Date of Birth</label>
                <p class="text-lg font-semibold text-gray-900">{{ $member->date_of_birth ? $member->date_of_birth->format(\'M d, Y\') : \'N/A\' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection',

    'resources/views/members/edit.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Edit Member</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'members.update\', $member) }}">
            @csrf
            @method(\'PUT\')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" value="{{ $member->first_name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" value="{{ $member->last_name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" value="{{ $member->phone }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ $member->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Update Member
                </button>
                <a href="{{ route(\'members.index\') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection',

    'resources/views/reports/membership.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Membership Report</h1>
        <p class="mt-2">Comprehensive membership statistics and analytics</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Membership Statistics</h2>
        <p class="text-gray-600">Report data will be displayed here</p>
    </div>
</div>
@endsection',

    'resources/views/reports/financial.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Financial Report</h1>
        <p class="mt-2">Income, expenses, and financial health analysis</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Financial Overview</h2>
        <p class="text-gray-600">Financial data will be displayed here</p>
    </div>
</div>
@endsection',

    'resources/views/reports/attendance.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Attendance Report</h1>
        <p class="mt-2">Service attendance trends and patterns</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Attendance Statistics</h2>
        <p class="text-gray-600">Attendance data will be displayed here</p>
    </div>
</div>
@endsection',

    'resources/views/equipment/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Equipment</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'equipment.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Equipment Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Equipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',

    'resources/views/visitors/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Visitor</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'visitors.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Visit Date *</label>
                    <input type="date" name="visit_date" required value="{{ date(\'Y-m-d\') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Visitor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',

    'resources/views/followups/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Follow-up</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'followups.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Follow-up
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',
];

echo "Creating missing view files...\n\n";

foreach ($views as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($fullPath, $content);
    echo "✓ Created: $path\n";
}

echo "\n✅ All missing view files created successfully!\n";
echo "\n📋 Summary:\n";
echo "- Members: show, edit\n";
echo "- Reports: membership, financial, attendance\n";
echo "- Equipment: create\n";
echo "- Visitors: create\n";
echo "- Follow-ups: create\n";
