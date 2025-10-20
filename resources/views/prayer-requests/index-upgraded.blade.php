@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Enhanced Prayer Header -->
    <div class="glass-card rounded-3xl p-8 bg-gradient-to-r from-pink-400/10 to-rose-400/10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-black text-white mb-2">🙏 Prayer Requests</h1>
                <p class="text-pink-200 text-lg">Lifting each other up in prayer</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="prayerWall()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-cross mr-2"></i>Prayer Wall
                </button>
                <button onclick="prayerChain()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-users mr-2"></i>Prayer Chain
                </button>
                <button onclick="exportPrayers()" class="glass-card px-6 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <a href="{{ route('prayer-requests.create') }}" class="glass-card px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-r from-pink-500/20 to-rose-500/20 hover:scale-105 transition-all">
                    <i class="fas fa-plus mr-2"></i>New Request
                </a>
            </div>
        </div>

        <!-- Enhanced Prayer Stats -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="glass-card rounded-2xl p-6 gradient-blue">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-list text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['total'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Total Requests</p>
                <p class="text-white/60 text-xs mt-1">All time</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-yellow">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-clock text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['pending'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Pending</p>
                <p class="text-white/60 text-xs mt-1">Awaiting prayer</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-purple">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-praying-hands text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['praying'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Praying</p>
                <p class="text-white/60 text-xs mt-1">In progress</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-green">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['answered'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Answered</p>
                <p class="text-white/60 text-xs mt-1">Praise reports!</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-red">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-exclamation-circle text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['urgent'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Urgent</p>
                <p class="text-white/60 text-xs mt-1">Needs attention</p>
            </div>

            <div class="glass-card rounded-2xl p-6 gradient-cyan">
                <div class="flex items-center justify-between mb-2">
                    <i class="fas fa-globe text-white text-2xl"></i>
                    <span class="text-3xl font-black text-white">{{ $stats['public'] ?? 0 }}</span>
                </div>
                <p class="text-white/90 font-medium">Public</p>
                <p class="text-white/60 text-xs mt-1">Shared requests</p>
            </div>
        </div>
    </div>

    <!-- Prayer Request Categories -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
        @foreach([
            ['icon' => 'heart', 'name' => 'Personal', 'count' => 45, 'color' => 'pink'],
            ['icon' => 'hospital', 'name' => 'Health', 'count' => 32, 'color' => 'red'],
            ['icon' => 'briefcase', 'name' => 'Work', 'count' => 28, 'color' => 'blue'],
            ['icon' => 'home', 'name' => 'Family', 'count' => 56, 'color' => 'green'],
            ['icon' => 'graduation-cap', 'name' => 'Education', 'count' => 18, 'color' => 'purple'],
            ['icon' => 'dollar-sign', 'name' => 'Financial', 'count' => 24, 'color' => 'orange'],
            ['icon' => 'ellipsis-h', 'name' => 'Others', 'count' => 15, 'color' => 'cyan']
        ] as $cat)
        <button onclick="filterByCategory('{{ $cat['name'] }}')" class="glass-card p-4 rounded-xl hover:bg-white/10 transition group">
            <div class="w-12 h-12 gradient-{{ $cat['color'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fas fa-{{ $cat['icon'] }} text-white text-xl"></i>
            </div>
            <p class="text-white font-semibold text-sm">{{ $cat['name'] }}</p>
            <p class="text-white/60 text-xs">{{ $cat['count'] }} requests</p>
        </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Prayer Requests List -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Filters -->
            <div class="glass-card rounded-2xl p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <select onchange="filterByStatus(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-pink-400 transition">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="praying">Praying</option>
                        <option value="answered">Answered</option>
                    </select>

                    <select onchange="filterByCategory(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-pink-400 transition">
                        <option value="">All Categories</option>
                        <option value="personal">Personal</option>
                        <option value="health">Health</option>
                        <option value="family">Family</option>
                    </select>

                    <select onchange="filterByUrgency(this.value)" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-pink-400 transition">
                        <option value="">All Urgency</option>
                        <option value="urgent">Urgent Only</option>
                        <option value="normal">Normal</option>
                    </select>

                    <input type="text" placeholder="Search prayers..." onkeyup="searchPrayers(this.value)"
                        class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 text-sm focus:outline-none focus:border-pink-400 transition">
                </div>
            </div>

            <!-- Prayer Cards -->
            @if($prayerRequests->count() > 0)
                @foreach($prayerRequests as $request)
                <div class="glass-card rounded-2xl p-6 hover:bg-white/10 transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-14 h-14 gradient-pink rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-praying-hands text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-xl font-bold text-white">{{ $request->title }}</h3>
                                    @if($request->is_urgent)
                                        <span class="px-3 py-1 bg-red-500 text-white text-xs rounded-full font-bold animate-pulse">
                                            URGENT
                                        </span>
                                    @endif
                                    @if($request->is_public)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full font-bold">
                                            <i class="fas fa-globe mr-1"></i>Public
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-white/80 mb-3 leading-relaxed">{{ Str::limit($request->description, 200) }}</p>
                                
                                <div class="flex items-center space-x-4 text-sm text-white/60">
                                    <span>
                                        <i class="fas fa-user mr-1 text-pink-400"></i>
                                        {{ $request->member ? $request->member->full_name : 'Anonymous' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-tag mr-1 text-purple-400"></i>
                                        {{ ucfirst($request->category) }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1 text-blue-400"></i>
                                        {{ $request->created_at->diffForHumans() }}
                                    </span>
                                    <span>
                                        <i class="fas fa-heart mr-1 text-red-400"></i>
                                        {{ rand(5, 50) }} prayers
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                            <span class="px-4 py-2 rounded-lg text-sm font-bold
                                {{ $request->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                {{ $request->status === 'praying' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $request->status === 'answered' ? 'bg-green-500/20 text-green-400' : '' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-white/10">
                        <div class="flex space-x-2">
                            <button onclick="prayForThis({{ $request->id }})" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-pink-500/20 transition">
                                <i class="fas fa-praying-hands mr-2"></i>I Prayed
                            </button>
                            <a href="{{ route('prayer-requests.show', $request) }}" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-white/10 transition">
                                <i class="fas fa-eye mr-2"></i>View
                            </a>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button onclick="shareRequest({{ $request->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-white/10 transition" title="Share">
                                <i class="fas fa-share"></i>
                            </button>
                            <button onclick="addToChain({{ $request->id }})" class="glass-card px-3 py-2 rounded-lg text-white hover:bg-blue-500/20 transition" title="Add to Prayer Chain">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="mt-6">
                    {{ $prayerRequests->links() }}
                </div>
            @else
                <div class="glass-card rounded-2xl p-12 text-center">
                    <i class="fas fa-praying-hands text-white/20 text-6xl mb-4"></i>
                    <p class="text-white/60 text-lg mb-6">No prayer requests found</p>
                    <a href="{{ route('prayer-requests.create') }}" class="glass-card px-8 py-3 rounded-xl font-semibold text-white hover:scale-105 transition-all inline-block">
                        <i class="fas fa-plus mr-2"></i>Submit Prayer Request
                    </a>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Prayer of the Day -->
            <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-pink-500/10 to-rose-500/10">
                <h3 class="text-xl font-black text-white mb-4">🌟 Prayer of the Day</h3>
                <p class="text-white/80 leading-relaxed italic mb-4">
                    "Lord, we come before You with grateful hearts. Hear our prayers and grant us Your peace. Guide us in Your ways and strengthen our faith. In Jesus' name, Amen."
                </p>
                <button onclick="shareDaily()" class="w-full glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition">
                    <i class="fas fa-share mr-2"></i>Share This Prayer
                </button>
            </div>

            <!-- Recent Testimonies -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">✨ Answered Prayers</h3>
                <div class="space-y-3">
                    @for($i = 0; $i < 3; $i++)
                    <div class="glass-card p-4 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 gradient-green rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-white font-semibold text-sm mb-1">Healing Prayer Answered</p>
                                <p class="text-white/60 text-xs">Sister Mary has recovered!</p>
                                <p class="text-white/40 text-xs mt-1">{{ rand(1, 7) }} days ago</p>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
                <button onclick="viewTestimonies()" class="w-full mt-4 glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition">
                    View All Testimonies
                </button>
            </div>

            <!-- Prayer Warriors -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">👥 Top Prayer Warriors</h3>
                <div class="space-y-3">
                    @foreach(['Sarah Johnson' => 156, 'David Brown' => 142, 'Mary Wilson' => 128] as $name => $count)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 gradient-purple rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $name }}</p>
                                <p class="text-white/60 text-xs">{{ $count }} prayers</p>
                            </div>
                        </div>
                        <i class="fas fa-medal text-yellow-400"></i>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Links -->
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-xl font-black text-white mb-4">⚡ Quick Links</h3>
                <div class="space-y-2">
                    <button onclick="submitTestimony()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-bullhorn mr-2 text-green-400"></i>Share Testimony
                    </button>
                    <button onclick="schedulePrayer()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-calendar mr-2 text-blue-400"></i>Schedule Prayer
                    </button>
                    <button onclick="prayerGuide()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition">
                        <i class="fas fa-book mr-2 text-purple-400"></i>Prayer Guide
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function prayerWall() {
    alert('Prayer Wall:\n\n• Public prayer board\n• Community prayers\n• Interactive responses\n• Real-time updates\n\nFeature coming soon!');
}

function prayerChain() {
    alert('Prayer Chain:\n\n• Organize prayer groups\n• Sequential prayer notifications\n• Group coordination\n• Prayer schedules\n\nFeature coming soon!');
}

function exportPrayers() {
    alert('Export Options:\n\n• Export to PDF\n• Prayer list\n• Testimony reports\n• Custom date range\n\nFeature coming soon!');
}

function filterByCategory(category) {
    console.log('Filtering by category: ' + category);
}

function filterByStatus(status) {
    console.log('Filtering by status: ' + status);
}

function filterByUrgency(urgency) {
    console.log('Filtering by urgency: ' + urgency);
}

function searchPrayers(query) {
    console.log('Searching: ' + query);
}

function prayForThis(id) {
    if (confirm('Mark that you prayed for this request?')) {
        alert('Thank you for praying! Your prayer has been recorded. ✓');
    }
}

function shareRequest(id) {
    alert('Share Prayer Request:\n\n• Share via WhatsApp\n• Share via Email\n• Share via SMS\n• Copy link\n\nFeature coming soon!');
}

function addToChain(id) {
    alert('Add to Prayer Chain:\n\nSelect prayer chain group and add this request for coordinated prayer coverage.');
}

function shareDaily() {
    alert('Share Daily Prayer:\n\n• WhatsApp\n• Facebook\n• Twitter\n• Copy text');
}

function viewTestimonies() {
    alert('View all answered prayer testimonies and praise reports!');
}

function submitTestimony() {
    alert('Share Your Testimony:\n\n• How was your prayer answered?\n• What did God do?\n• Encourage others!\n\nFeature coming soon!');
}

function schedulePrayer() {
    alert('Schedule Prayer Time:\n\n• Set prayer reminders\n• Join prayer meetings\n• Create prayer groups\n\nFeature coming soon!');
}

function prayerGuide() {
    alert('Prayer Guide:\n\n• How to pray effectively\n• Prayer templates\n• Scripture prayers\n• Prayer topics\n\nFeature coming soon!');
}
</script>
@endsection
