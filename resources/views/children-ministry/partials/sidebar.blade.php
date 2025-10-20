<!-- Sidebar -->
<div class="space-y-6">
    <!-- Today's Attendance -->
    <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-green-500/10 to-emerald-500/10">
        <h3 class="text-xl font-black text-white mb-4">📊 Today's Attendance</h3>
        <div class="text-center mb-4">
            <div class="text-5xl font-black text-white mb-2">{{ $stats['present_today'] ?? 0 }}</div>
            <p class="text-white/80">out of {{ $stats['total'] ?? 0 }} children</p>
        </div>
        <div class="w-full bg-white/10 rounded-full h-3 mb-4">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all" 
                style="width: {{ $stats['attendance_rate'] ?? 0 }}%"></div>
        </div>
        <button onclick="quickAttendance()" class="w-full glass-card px-4 py-3 rounded-xl text-white font-semibold hover:bg-white/10 transition">
            <i class="fas fa-clipboard-check mr-2"></i>Quick Check-in
        </button>
    </div>

    <!-- Upcoming Birthdays -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-black text-white mb-4">🎂 Upcoming Birthdays</h3>
        <div class="space-y-3">
            @for($i = 0; $i < 3; $i++)
            <div class="flex items-center space-x-3 glass-card p-3 rounded-xl">
                <div class="w-10 h-10 gradient-pink rounded-lg flex items-center justify-center">
                    <i class="fas fa-birthday-cake text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">{{ ['Emma Johnson', 'Noah Williams', 'Olivia Brown'][$i] }}</p>
                    <p class="text-white/60 text-xs">{{ ['Oct 20', 'Oct 22', 'Oct 25'][$i] }} • Turning {{ [7, 5, 9][$i] }}</p>
                </div>
                <button onclick="sendBirthdayWish({{ $i }})" class="px-3 py-1 bg-pink-500/20 text-pink-400 rounded-lg hover:bg-pink-500/30 transition text-xs">
                    <i class="fas fa-gift"></i>
                </button>
            </div>
            @endfor
        </div>
        <button onclick="viewAllBirthdays()" class="w-full mt-4 glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition text-sm">
            View All Birthdays
        </button>
    </div>

    <!-- Class Analytics -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-black text-white mb-4">📈 Class Progress</h3>
        <div class="space-y-4">
            @foreach(['Nursery' => 90, 'Toddlers' => 85, 'Pre-School' => 78, 'Primary' => 92] as $class => $rate)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-white font-semibold text-sm">{{ $class }}</span>
                    <span class="text-white font-bold text-sm">{{ $rate }}%</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-2">
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full transition-all" 
                        style="width: {{ $rate }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- AI Lesson Planner -->
    <div class="glass-card rounded-2xl p-6 bg-gradient-to-br from-purple-500/10 to-pink-500/10">
        <h3 class="text-xl font-black text-white mb-4">🧠 AI Lesson Planner</h3>
        <p class="text-white/80 text-sm mb-4">Generate age-appropriate Bible lessons instantly!</p>
        <div class="space-y-2 mb-4">
            <select id="ageGroupSelect" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white text-sm focus:outline-none focus:border-purple-400 transition">
                <option value="">Select Age Group</option>
                <option value="nursery">Nursery (0-2)</option>
                <option value="toddlers">Toddlers (3-4)</option>
                <option value="preschool">Pre-School (5-6)</option>
                <option value="primary">Primary (7-9)</option>
                <option value="juniors">Juniors (10-12)</option>
            </select>
            <input type="text" id="lessonTopic" placeholder="Bible story or theme..." 
                class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 text-sm focus:outline-none focus:border-purple-400 transition">
        </div>
        <button onclick="generateLesson()" class="w-full glass-card px-4 py-3 rounded-xl text-white font-semibold hover:scale-105 transition-all bg-gradient-to-r from-purple-500/20 to-pink-500/20">
            <i class="fas fa-magic mr-2"></i>Generate Lesson Plan
        </button>
    </div>

    <!-- Points Leaderboard -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-black text-white mb-4">🏆 Points Leaderboard</h3>
        <p class="text-white/70 text-xs mb-4">Gamified rewards for attendance & memory verses</p>
        <div class="space-y-3">
            @foreach([
                ['name' => 'Emma J.', 'points' => 250, 'rank' => 1, 'achievement' => '5-Week Streak'],
                ['name' => 'Noah W.', 'points' => 230, 'rank' => 2, 'achievement' => 'Memory Master'],
                ['name' => 'Olivia B.', 'points' => 220, 'rank' => 3, 'achievement' => 'Helpful Helper']
            ] as $leader)
            <div class="flex items-center space-x-3 glass-card p-3 rounded-xl">
                <div class="w-10 h-10 {{ $leader['rank'] === 1 ? 'gradient-yellow' : ($leader['rank'] === 2 ? 'bg-gradient-to-br from-gray-400 to-gray-500' : 'gradient-orange') }} rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ $leader['rank'] }}
                </div>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">{{ $leader['name'] }}</p>
                    <p class="text-white/60 text-xs">
                        <i class="fas fa-star text-yellow-400 mr-1"></i>{{ $leader['points'] }} points
                        <span class="mx-1">•</span>
                        <span class="text-purple-400">{{ $leader['achievement'] }}</span>
                    </p>
                </div>
                @if($leader['rank'] === 1)
                    <i class="fas fa-crown text-yellow-400 text-xl"></i>
                @endif
            </div>
            @endforeach
        </div>
        <button onclick="viewFullLeaderboard()" class="w-full mt-4 glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition text-sm">
            View Full Leaderboard
        </button>
    </div>

    <!-- Milestones Tracker -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-black text-white mb-4">🎯 Recent Milestones</h3>
        <div class="space-y-3">
            @foreach([
                ['child' => 'Emma J.', 'milestone' => 'First Bible Verse Memorized', 'icon' => 'book', 'color' => 'purple'],
                ['child' => 'Noah W.', 'milestone' => '10 Weeks Perfect Attendance', 'icon' => 'check-circle', 'color' => 'green'],
                ['child' => 'Olivia B.', 'milestone' => 'Helped in Service', 'icon' => 'hands-helping', 'color' => 'blue']
            ] as $milestone)
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 gradient-{{ $milestone['color'] }} rounded-lg flex items-center justify-center">
                    <i class="fas fa-{{ $milestone['icon'] }} text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">{{ $milestone['child'] }}</p>
                    <p class="text-white/60 text-xs">{{ $milestone['milestone'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <button onclick="manageMilestones()" class="w-full mt-4 glass-card px-4 py-2 rounded-xl text-white font-semibold hover:bg-white/10 transition text-sm">
            <i class="fas fa-trophy mr-2"></i>Track Milestones
        </button>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card rounded-2xl p-6">
        <h3 class="text-xl font-black text-white mb-4">⚡ Quick Actions</h3>
        <div class="space-y-2">
            <button onclick="bulkCheckIn()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition group">
                <i class="fas fa-users-cog mr-2 text-green-400"></i>Bulk Check-in
            </button>
            <button onclick="sendParentNotification()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition group">
                <i class="fas fa-bell mr-2 text-blue-400"></i>Parent Notification
            </button>
            <button onclick="printNameTags()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition group">
                <i class="fas fa-print mr-2 text-purple-400"></i>Print Name Tags
            </button>
            <button onclick="showTeacherAssignment()" class="w-full glass-card px-4 py-3 rounded-xl text-white text-left hover:bg-white/10 transition group">
                <i class="fas fa-chalkboard-teacher mr-2 text-orange-400"></i>Teacher Assignment
            </button>
        </div>
    </div>
</div>
