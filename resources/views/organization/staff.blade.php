@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">👥 Staff & Role Management</h1>
                <p class="text-gray-600">Manage all staff across branches</p>
            </div>
            <button class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                <i class="fas fa-user-plus mr-2"></i>Add Staff Member
            </button>
        </div>
    </div>

    <!-- Role Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pastors</p>
                    <p class="text-3xl font-bold text-gray-800">12</p>
                </div>
                <i class="fas fa-user-tie text-blue-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Ministry Leaders</p>
                    <p class="text-3xl font-bold text-gray-800">28</p>
                </div>
                <i class="fas fa-users text-purple-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Volunteers</p>
                    <p class="text-3xl font-bold text-gray-800">150</p>
                </div>
                <i class="fas fa-hands-helping text-green-600 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Admins</p>
                    <p class="text-3xl font-bold text-gray-800">5</p>
                </div>
                <i class="fas fa-user-shield text-orange-600 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800">All Staff Members</h3>
                <div class="flex space-x-3">
                    <select class="border border-gray-300 rounded-lg px-4 py-2">
                        <option>All Roles</option>
                        <option>Pastor</option>
                        <option>Ministry Leader</option>
                        <option>Volunteer</option>
                        <option>Admin</option>
                    </select>
                    <input type="text" placeholder="Search staff..." class="border border-gray-300 rounded-lg px-4 py-2 w-64">
                </div>
            </div>
        </div>

        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Login</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="font-bold text-blue-600">PO</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Pastor Owusu</p>
                                <p class="text-sm text-gray-600">pastor.owusu@church.test</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">Pastor</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Faith Temple</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">2 hours ago</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800">
                            <i class="fas fa-user-slash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
