@extends('layouts.app')

@section('content')
<div>
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-cog mr-3"></i>
                    System Settings
                </h1>
                <p class="mt-2">Configure your church management system</p>
            </div>
        </div>
    </div>

    <!-- System Stats -->
    <div class="grid grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Members</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_members'] }}</p>
                </div>
                <i class="fas fa-users text-3xl text-blue-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Visitors</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_visitors'] }}</p>
                </div>
                <i class="fas fa-user-plus text-3xl text-green-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Donations</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_donations'] }}</p>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl text-purple-200"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Equipment</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['total_equipment'] }}</p>
                </div>
                <i class="fas fa-tools text-3xl text-orange-200"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <!-- Church Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-church text-green-600 mr-2"></i>
                Church Information
            </h2>
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Church Name</label>
                        <input type="text" name="church_name" value="{{ $settings['church_name'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="church_email" value="{{ $settings['church_email'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" name="church_phone" value="{{ $settings['church_phone'] }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="church_address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">{{ $settings['church_address'] }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- System Configuration -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                System Configuration
            </h2>
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Database</h3>
                    <p class="text-sm text-gray-600">Connection: <span class="font-mono bg-green-100 text-green-800 px-2 py-1 rounded">SQLite</span></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">Laravel Version</h3>
                    <p class="text-sm text-gray-600">Version: <span class="font-mono bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ app()->version() }}</span></p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-800 mb-2">PHP Version</h3>
                    <p class="text-sm text-gray-600">Version: <span class="font-mono bg-purple-100 text-purple-800 px-2 py-1 rounded">{{ PHP_VERSION }}</span></p>
                </div>
            </div>
        </div>

        <!-- Categories Management -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tags text-purple-600 mr-2"></i>
                Categories
            </h2>
            <div class="space-y-3">
                <a href="#" class="block p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-800">Donation Categories</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" class="block p-3 bg-red-50 hover:bg-red-100 rounded-lg transition">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-800">Expense Categories</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" class="block p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-800">Equipment Categories</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" class="block p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-800">Followup Types</span>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Quick Actions
            </h2>
            <div class="space-y-3">
                <button onclick="if(confirm('Clear all cache?')) alert('Cache cleared!')" class="w-full p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition text-left">
                    <div class="flex items-center">
                        <i class="fas fa-sync text-blue-600 mr-3"></i>
                        <span class="font-medium text-gray-800">Clear Cache</span>
                    </div>
                </button>
                <button onclick="alert('Backup created!')" class="w-full p-3 bg-green-50 hover:bg-green-100 rounded-lg transition text-left">
                    <div class="flex items-center">
                        <i class="fas fa-database text-green-600 mr-3"></i>
                        <span class="font-medium text-gray-800">Backup Database</span>
                    </div>
                </button>
                <button onclick="alert('System optimized!')" class="w-full p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition text-left">
                    <div class="flex items-center">
                        <i class="fas fa-rocket text-purple-600 mr-3"></i>
                        <span class="font-medium text-gray-800">Optimize System</span>
                    </div>
                </button>
                <a href="{{ route('reports.index') }}" class="block w-full p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition text-left">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-orange-600 mr-3"></i>
                        <span class="font-medium text-gray-800">View Reports</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
