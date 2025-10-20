<!-- Top Givers Section -->
@if($topGivers->count() > 0)
<div class="glass-card p-6 rounded-2xl mb-8">
    <h3 class="text-xl font-bold text-white mb-4">
        <i class="fas fa-star text-yellow-400 mr-2"></i> Top Givers This Year - Loyalty Recognition
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($topGivers->take(10) as $index => $giver)
        <div class="bg-gradient-to-br from-gray-800/80 to-gray-900/80 p-4 rounded-xl border border-gray-700 hover:border-green-500/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-2xl font-bold text-green-400">#{{ $index + 1 }}</span>
                @if($index == 0)
                    <span class="text-2xl">🥇</span>
                @elseif($index == 1)
                    <span class="text-2xl">🥈</span>
                @elseif($index == 2)
                    <span class="text-2xl">🥉</span>
                @else
                    <span class="text-2xl">⭐</span>
                @endif
            </div>
            <h4 class="text-white font-semibold group-hover:text-green-400 transition-colors">
                {{ $giver->member->first_name ?? 'Unknown' }} {{ $giver->member->last_name ?? '' }}
            </h4>
            <p class="text-2xl font-bold text-green-400 mt-2">GHS {{ number_format($giver->total, 2) }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ $giver->count }} tithe{{ $giver->count != 1 ? 's' : '' }}</p>
            <a href="{{ route('tithes.member-history', $giver->member_id) }}" class="text-xs text-blue-400 hover:text-blue-300 mt-2 inline-block">
                View History <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif
