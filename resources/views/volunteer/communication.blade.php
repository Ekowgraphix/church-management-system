@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🗣️ Communication</h1>
        <p class="text-gray-600">Chat with team members and view announcements</p>
    </div>

    <!-- Pinned Announcements -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white mb-6">
        <div class="flex items-center space-x-3 mb-4">
            <i class="fas fa-thumbtack text-2xl"></i>
            <h3 class="text-xl font-bold">Pinned Announcement</h3>
        </div>
        <p class="text-lg mb-2">Volunteer Appreciation Event - Saturday 4 PM</p>
        <p class="text-sm opacity-90">Join us for a special appreciation event to honor all our faithful volunteers. Refreshments will be served!</p>
    </div>

    <!-- Chat Interface -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Contacts Sidebar -->
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg p-4">
            <h3 class="font-bold text-gray-800 mb-4">Chats</h3>
            <div class="space-y-2">
                <div class="p-3 bg-orange-50 rounded-lg cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-orange-600">UT</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">Ushering Team</p>
                            <p class="text-xs text-gray-600 truncate">Team Leader: Great work today!</p>
                        </div>
                    </div>
                </div>
                <div class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-blue-600">JM</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate">John Mensah</p>
                            <p class="text-xs text-gray-600 truncate">See you Sunday!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-lg flex flex-col" style="height: 500px;">
            <!-- Chat Header -->
            <div class="border-b p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="font-bold text-orange-600">UT</span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">Ushering Team</p>
                        <p class="text-sm text-gray-600">8 members</p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold text-blue-600">JM</span>
                    </div>
                    <div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none px-4 py-2">
                            <p class="text-sm text-gray-800">Great work everyone today! The service ran smoothly.</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">10:30 AM</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 justify-end">
                    <div>
                        <div class="bg-orange-600 text-white rounded-2xl rounded-tr-none px-4 py-2">
                            <p class="text-sm">Thank you! It was a blessing to serve.</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-right">10:32 AM</p>
                    </div>
                    <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-orange-700 text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="border-t p-4">
                <div class="flex space-x-3">
                    <button class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-paperclip text-xl"></i>
                    </button>
                    <input type="text" placeholder="Type a message..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2">
                    <button class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
