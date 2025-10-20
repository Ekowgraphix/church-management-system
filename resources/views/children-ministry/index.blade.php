@extends('layouts.app')

@section('content')
<div>
    <!-- Header -->
    <div class="glass-card p-8 rounded-2xl mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-2 flex items-center">
                    <i class="fas fa-child mr-3 text-neon-green"></i>
                    Children Ministry
                </h1>
                <p class="text-gray-300">Manage children records and Sunday school</p>
            </div>
            <a href="{{ route('children-ministry.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Register Child
            </a>
        </div>
    </div>

    <!-- Children List -->
    <div class="glass-card p-6 rounded-2xl">
        <h2 class="text-2xl font-bold text-white mb-6">Registered Children</h2>

        @if($children->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-gray-400 border-b border-white/10">
                            <th class="pb-4">Child Name</th>
                            <th class="pb-4">Age</th>
                            <th class="pb-4">Gender</th>
                            <th class="pb-4">Parent</th>
                            <th class="pb-4">Contact</th>
                            <th class="pb-4">Class</th>
                            <th class="pb-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($children as $child)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="py-4">
                                <div class="font-semibold">{{ $child->child_name }}</div>
                                <div class="text-sm text-gray-400">DOB: {{ $child->dob->format('M d, Y') }}</div>
                            </td>
                            <td>{{ $child->dob->age }} years</td>
                            <td>{{ $child->gender }}</td>
                            <td>{{ $child->parent_name }}</td>
                            <td>{{ $child->contact }}</td>
                            <td>
                                @if($child->class_group)
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                        {{ $child->class_group }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="{{ route('children-ministry.edit', $child) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('children-ministry.destroy', $child) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-red-500/20 text-red-400 hover:bg-red-500/30">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $children->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-child text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg mb-4">No children registered yet</p>
                <a href="{{ route('children-ministry.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i> Register First Child
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
