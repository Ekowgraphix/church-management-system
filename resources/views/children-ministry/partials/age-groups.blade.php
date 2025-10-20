<!-- Age Group Filters -->
<div class="grid grid-cols-2 md:grid-cols-6 gap-4">
    @foreach([
        ['icon' => 'baby', 'name' => 'Nursery', 'age' => '0-2', 'count' => 8, 'color' => 'pink'],
        ['icon' => 'child', 'name' => 'Toddlers', 'age' => '3-4', 'count' => 12, 'color' => 'purple'],
        ['icon' => 'user-graduate', 'name' => 'Pre-School', 'age' => '5-6', 'count' => 15, 'color' => 'blue'],
        ['icon' => 'book-reader', 'name' => 'Primary', 'age' => '7-9', 'count' => 22, 'color' => 'green'],
        ['icon' => 'user-friends', 'name' => 'Juniors', 'age' => '10-12', 'count' => 18, 'color' => 'orange'],
        ['icon' => 'users', 'name' => 'Teens', 'age' => '13-17', 'count' => 10, 'color' => 'cyan']
    ] as $group)
    <button onclick="filterByAge('{{ $group['name'] }}')" class="glass-card p-4 rounded-xl hover:bg-white/10 transition group">
        <div class="w-12 h-12 gradient-{{ $group['color'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-{{ $group['icon'] }} text-white text-xl"></i>
        </div>
        <p class="text-white font-semibold text-sm">{{ $group['name'] }}</p>
        <p class="text-white/60 text-xs">{{ $group['age'] }} years</p>
        <p class="text-white/80 text-sm font-bold mt-1">{{ $group['count'] }} kids</p>
    </button>
    @endforeach
</div>
