<!-- Search & Filters -->
<div class="glass-card p-6 rounded-2xl mb-6">
    <form method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class="fas fa-search mr-1"></i> Search Member
                </label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Member name..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class="fas fa-user mr-1"></i> Member
                </label>
                <select name="member_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                    <option value="">All Members</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                            {{ $member->first_name }} {{ $member->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class="fas fa-calendar mr-1"></i> Date From
                </label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class="fas fa-calendar mr-1"></i> Date To
                </label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    <i class="fas fa-credit-card mr-1"></i> Payment Method
                </label>
                <select name="payment_method" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-green-500 focus:ring-2 focus:ring-green-500/20">
                    <option value="">All Methods</option>
                    <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Mobile Money" {{ request('payment_method') == 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="Bank Transfer" {{ request('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cheque" {{ request('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="Card" {{ request('payment_method') == 'Card' ? 'selected' : '' }}>Card</option>
                </select>
            </div>
        </div>
        <div class="flex space-x-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-all">
                <i class="fas fa-filter mr-2"></i> Apply Filters
            </button>
            <a href="{{ route('tithes.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all">
                <i class="fas fa-redo mr-2"></i> Clear
            </a>
        </div>
    </form>
</div>
