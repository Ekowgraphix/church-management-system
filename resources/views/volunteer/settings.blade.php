@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">⚙️ Settings</h1>
        <p class="text-gray-600">Manage your volunteer profile and preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Settings -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Profile Information</h3>
                <form>
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-3xl">
                            {{ strtoupper(substr($volunteer->name, 0, 1)) }}
                        </div>
                        <div>
                            <button type="button" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700 mb-2">
                                Upload Photo
                            </button>
                            <p class="text-sm text-gray-600">JPG, PNG or GIF (max 2MB)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                            <input type="text" value="{{ $volunteer->name }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" value="{{ $volunteer->email }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                            <input type="tel" value="{{ $volunteer->phone }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Ministry</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                <option>Ushering Team</option>
                                <option>Media Team</option>
                                <option>Outreach Team</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Available Days -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Available Days for Serving</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Monday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Tuesday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Wednesday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Thursday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Friday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Saturday</span>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" class="mr-3" checked>
                        <span class="text-gray-700">Sunday</span>
                    </label>
                </div>
                <button class="mt-4 bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700">
                    Update Availability
                </button>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Change Password</h3>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Current Password</label>
                        <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">New Password</label>
                        <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">Confirm New Password</label>
                        <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="space-y-6">
            <!-- Notification Preferences -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Notifications</h3>
                <div class="space-y-4">
                    <label class="flex items-center justify-between">
                        <span class="text-gray-700">Email Notifications</span>
                        <input type="checkbox" class="toggle" checked>
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-gray-700">SMS Reminders</span>
                        <input type="checkbox" class="toggle" checked>
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-gray-700">Task Alerts</span>
                        <input type="checkbox" class="toggle" checked>
                    </label>
                    <label class="flex items-center justify-between">
                        <span class="text-gray-700">Event Reminders</span>
                        <input type="checkbox" class="toggle" checked>
                    </label>
                </div>
            </div>

            <!-- Volunteer Status -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Volunteer Status</h3>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" class="mr-3">
                        <span class="text-gray-700">Temporarily pause volunteer duties</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2 ml-6">You won't receive new assignments while paused</p>
                </div>
            </div>

            <!-- Volunteering History -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Volunteering Stats</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Total Hours:</span>
                        <span class="font-bold">45 hrs</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Events Served:</span>
                        <span class="font-bold">12</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Member Since:</span>
                        <span class="font-bold">{{ $volunteer->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Badges Earned:</span>
                        <span class="font-bold">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
