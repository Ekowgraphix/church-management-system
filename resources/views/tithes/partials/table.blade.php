<!-- Tithe Records Table -->
<div class="glass-card rounded-2xl overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h3 class="text-xl font-bold text-white">
            <i class="fas fa-table text-green-400 mr-2"></i> Tithe Records
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-800/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Payment Method</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Received By</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Remarks</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($tithes as $tithe)
                <tr class="hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-300">
                        <i class="fas fa-calendar-day text-blue-400 mr-2"></i>
                        {{ $tithe->date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gradient-to-br from-green-500 to-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($tithe->member->first_name ?? 'U', 0, 1) }}{{ substr($tithe->member->last_name ?? 'N', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">
                                    {{ $tithe->member->first_name ?? 'Unknown' }} {{ $tithe->member->last_name ?? '' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $tithe->member->phone ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-bold text-green-400">
                        GHS {{ number_format($tithe->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $tithe->payment_method == 'Cash' ? 'bg-green-500/20 text-green-400' : '' }}
                            {{ $tithe->payment_method == 'Mobile Money' ? 'bg-blue-500/20 text-blue-400' : '' }}
                            {{ $tithe->payment_method == 'Bank Transfer' ? 'bg-purple-500/20 text-purple-400' : '' }}
                            {{ $tithe->payment_method == 'Cheque' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ $tithe->payment_method == 'Card' ? 'bg-pink-500/20 text-pink-400' : '' }}">
                            {{ $tithe->payment_method }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-300">
                        {{ $tithe->received_by ?: 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">
                        {{ Str::limit($tithe->remarks, 30) ?: '-' }}
                    </td>
                    <td class="px-6 py-4 text-right text-sm space-x-2">
                        <a href="{{ route('tithes.receipt', $tithe->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all text-xs" title="Download Receipt">
                            <i class="fas fa-receipt"></i>
                        </a>
                        <a href="{{ route('tithes.edit', $tithe->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-all text-xs" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('tithes.destroy', $tithe->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this tithe record?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all text-xs" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                            <p class="text-lg">No tithe records found</p>
                            <p class="text-sm mt-2">Start by adding your first tithe record</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tithes->hasPages())
    <div class="px-6 py-4 border-t border-gray-700">
        {{ $tithes->links() }}
    </div>
    @endif
</div>
