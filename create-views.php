<?php

$views = [
    // Members
    'resources/views/members/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-4xl">
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add New Member</h1>
        <p class="mt-2">Register a new church member</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'members.store\') }}">
            @csrf
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" name="first_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" name="last_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                    <input type="tel" name="phone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex space-x-4">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-save mr-2"></i> Save Member
                </button>
                <a href="{{ route(\'members.index\') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection',

    // Attendance
    'resources/views/attendance/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Attendance Dashboard
                </h1>
                <p class="mt-2">View and manage church attendance records</p>
            </div>
            <button class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                <i class="fas fa-check-circle mr-2"></i> Mark Attendance
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Recent Services</h2>
        <p class="text-gray-600">Attendance tracking will be displayed here</p>
    </div>
</div>
@endsection',

    // Donations
    'resources/views/donations/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Financial Dashboard</h1>
                <p class="mt-2">Track and manage church finances</p>
            </div>
            <a href="{{ route(\'donations.create\') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                <i class="fas fa-plus mr-2"></i> Add Transaction
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Total Income</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Total Expenses</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Current Balance</p>
            <p class="text-3xl font-bold mt-2">GH₵ 0.00</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
        <p class="text-gray-600">No transactions found</p>
    </div>
</div>
@endsection',

    'resources/views/donations/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Donation</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'donations.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="donation_date" required value="{{ date(\'Y-m-d\') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Donation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',

    // SMS
    'resources/views/sms/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">SMS Broadcast</h1>
                <p class="mt-2">Send messages to members</p>
            </div>
            <a href="{{ route(\'sms.create\') }}" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100">
                <i class="fas fa-paper-plane mr-2"></i> Compose SMS
            </a>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-blue-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Total SMS</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
        </div>
        <div class="bg-green-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Sent</p>
            <p class="text-3xl font-bold text-green-600 mt-2">0</p>
        </div>
        <div class="bg-yellow-50 rounded-xl p-6">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">0</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">SMS Campaigns</h2>
        <p class="text-gray-600">No campaigns found</p>
    </div>
</div>
@endsection',

    'resources/views/sms/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Compose SMS</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'sms.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recipients *</label>
                    <select name="recipients" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="all">All Members</option>
                        <option value="active">Active Members Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                    <textarea name="message" required rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium">
                    Send SMS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',

    // Equipment
    'resources/views/equipment/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Equipment Management</h1>
        <p class="mt-2">Track church equipment and assignments</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Equipment List</h2>
        <p class="text-gray-600">No equipment found</p>
    </div>
</div>
@endsection',

    // Visitors
    'resources/views/visitors/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Visitors</h1>
        <p class="mt-2">Manage church visitors</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Visitor List</h2>
        <p class="text-gray-600">No visitors found</p>
    </div>
</div>
@endsection',

    // Reports
    'resources/views/reports/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Reports</h1>
        <p class="mt-2">Generate and view church reports</p>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <a href="{{ route(\'reports.membership\') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-users text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-bold">Membership Report</h3>
        </a>
        <a href="{{ route(\'reports.financial\') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-chart-line text-4xl text-green-600 mb-4"></i>
            <h3 class="text-xl font-bold">Financial Report</h3>
        </a>
        <a href="{{ route(\'reports.attendance\') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <i class="fas fa-calendar-check text-4xl text-purple-600 mb-4"></i>
            <h3 class="text-xl font-bold">Attendance Report</h3>
        </a>
    </div>
</div>
@endsection',

    // Followups
    'resources/views/followups/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Follow-ups</h1>
        <p class="mt-2">Manage member follow-ups</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Follow-up List</h2>
        <p class="text-gray-600">No follow-ups found</p>
    </div>
</div>
@endsection',

    // Expenses
    'resources/views/expenses/index.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div>
    <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl shadow-lg p-8 mb-6 text-white">
        <h1 class="text-3xl font-bold">Expenses</h1>
        <p class="mt-2">Track church expenses</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Expense List</h2>
        <p class="text-gray-600">No expenses found</p>
    </div>
</div>
@endsection',

    'resources/views/expenses/create.blade.php' => '@extends(\'layouts.app\')

@section(\'content\')
<div class="max-w-2xl">
    <div class="bg-gradient-to-r from-red-600 to-pink-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <h1 class="text-3xl font-bold">Add Expense</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route(\'expenses.store\') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                    <input type="number" step="0.01" name="amount" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="expense_date" required value="{{ date(\'Y-m-d\') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium">
                    Save Expense
                </button>
            </div>
        </form>
    </div>
</div>
@endsection',
];

echo "Creating view files...\n\n";

foreach ($views as $path => $content) {
    $fullPath = __DIR__ . '/' . $path;
    $dir = dirname($fullPath);
    
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents($fullPath, $content);
    echo "✓ Created: $path\n";
}

echo "\n✅ All view files created successfully!\n";
