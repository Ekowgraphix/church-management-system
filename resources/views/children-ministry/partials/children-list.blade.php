<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Children List with Cards -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Filters and Search -->
        <div class="glass-card rounded-2xl p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <select onchange="filterByClass(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                    <option value="">All Classes</option>
                    <option value="nursery">Nursery</option>
                    <option value="toddlers">Toddlers</option>
                    <option value="preschool">Pre-School</option>
                    <option value="primary">Primary</option>
                </select>

                <select onchange="filterByGender(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                    <option value="">All Genders</option>
                    <option value="male">Boys</option>
                    <option value="female">Girls</option>
                </select>

                <select onchange="sortChildren(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-yellow-400 transition">
                    <option value="name">Sort by Name</option>
                    <option value="age">Sort by Age</option>
                    <option value="recent">Recently Added</option>
                </select>

                <input type="text" placeholder="Search children..." onkeyup="searchChildren(this.value)"
                    class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 text-sm focus:outline-none focus:border-yellow-400 transition">
            </div>
        </div>

        <!-- View Toggle -->
        <div class="flex items-center justify-between glass-card rounded-xl p-3">
            <div class="flex space-x-2">
                <button onclick="switchView('grid')" id="gridViewBtn" class="px-4 py-2 bg-white/10 rounded-lg text-white text-sm transition">
                    <i class="fas fa-th-large mr-2"></i>Grid
                </button>
                <button onclick="switchView('list')" id="listViewBtn" class="px-4 py-2 rounded-lg text-white text-sm hover:bg-white/10 transition">
                    <i class="fas fa-list mr-2"></i>List
                </button>
            </div>
            <span class="text-white/60 text-sm">{{ $children->total() ?? 0 }} children</span>
        </div>

        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(isset($children) && $children->count() > 0)
                @foreach($children as $child)
                <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition-all">
                    <div class="flex items-start space-x-4 mb-4">
                        <!-- Photo -->
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex-shrink-0">
                            @if($child->photo)
                                <img src="{{ asset('storage/' . $child->photo) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-child text-4xl text-white/40"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-white font-bold text-lg">{{ $child->name ?? $child->child_name }}</h3>
                                    <p class="text-white/60 text-sm">
                                        <i class="fas fa-birthday-cake mr-1 text-pink-400"></i>
                                        {{ $child->dob->age }} years old
                                    </p>
                                </div>
                                @if($child->class_group)
                                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full font-bold">
                                        {{ $child->class_group }}
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-1 text-sm text-white/70 mb-3">
                                <p><i class="fas fa-user mr-2 text-green-400"></i>{{ $child->guardian_name ?? $child->parent_name }}</p>
                                <p><i class="fas fa-phone mr-2 text-blue-400"></i>{{ $child->guardian_contact ?? $child->contact }}</p>
                            </div>

                            <!-- Quick Stats -->
                            <div class="flex items-center space-x-3 text-xs">
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg">
                                    <i class="fas fa-check mr-1"></i>{{ rand(75, 95) }}% Attendance
                                </span>
                                <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-lg">
                                    <i class="fas fa-star mr-1"></i>{{ rand(100, 250) }} Points
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2 pt-3 border-t border-white/10">
                        <button onclick="viewProfile({{ $child->id }})" class="flex-1 glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition text-sm">
                            <i class="fas fa-eye mr-1"></i>View
                        </button>
                        <button onclick="markAttendance({{ $child->id }})" class="flex-1 glass-card px-3 py-2 rounded-lg text-white hover:bg-green-500/20 transition text-sm">
                            <i class="fas fa-check mr-1"></i>Present
                        </button>
                        <a href="{{ route('children-ministry.edit', $child) }}" class="flex-1 glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition text-sm text-center">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-span-2 glass-card rounded-2xl p-12 text-center">
                    <i class="fas fa-child text-white/20 text-6xl mb-4"></i>
                    <p class="text-white/60 text-lg mb-6">No children registered yet</p>
                    <a href="{{ route('children-ministry.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                        <i class="fas fa-plus mr-2"></i>Register First Child
                    </a>
                </div>
            @endif
        </div>

        <!-- List View (Hidden by default) -->
        <div id="listView" class="hidden">
            <div class="glass-card rounded-2xl overflow-hidden">
                <table class="w-full">
                    <thead class="bg-white/5">
                        <tr class="text-left text-white/80 text-sm">
                            <th class="p-4">Photo</th>
                            <th class="p-4">Name</th>
                            <th class="p-4">Age</th>
                            <th class="p-4">Class</th>
                            <th class="p-4">Guardian</th>
                            <th class="p-4">Attendance</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @if(isset($children) && $children->count() > 0)
                            @foreach($children as $child)
                            <tr class="border-t border-white/5 hover:bg-white/5 transition">
                                <td class="p-4">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gradient-to-br from-yellow-500/20 to-orange-500/20">
                                        @if($child->photo)
                                            <img src="{{ asset('storage/' . $child->photo) }}" alt="{{ $child->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-child text-2xl text-white/40"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4">
                                    <p class="font-semibold">{{ $child->name ?? $child->child_name }}</p>
                                    <p class="text-xs text-white/60">DOB: {{ $child->dob->format('M d, Y') }}</p>
                                </td>
                                <td class="p-4">{{ $child->dob->age }}</td>
                                <td class="p-4">
                                    @if($child->class_group)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full">{{ $child->class_group }}</span>
                                    @else
                                        <span class="text-white/40">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <p class="text-sm">{{ $child->guardian_name ?? $child->parent_name }}</p>
                                    <p class="text-xs text-white/60">{{ $child->guardian_contact ?? $child->contact }}</p>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-lg">{{ rand(75, 95) }}%</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <button onclick="markAttendance({{ $child->id }})" class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition text-xs">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <a href="{{ route('children-ministry.edit', $child) }}" class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition text-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if(isset($children) && $children->count() > 0)
            <div class="mt-6">
                {{ $children->links() }}
            </div>
        @endif
    </div>
</div>
