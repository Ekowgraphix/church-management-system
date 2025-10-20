<!-- AI Insights Modal -->
<div id="aiInsightsModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-700 flex justify-between items-center sticky top-0 bg-gray-900/95 backdrop-blur-lg z-10">
            <h3 class="text-2xl font-bold text-white">
                <i class="fas fa-robot text-purple-400 mr-2"></i> AI Insights & Analytics
            </h3>
            <button onclick="closeAIInsights()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-3">Select Period</label>
                <div class="flex space-x-3">
                    <button onclick="loadAIInsights('month')" class="px-6 py-2 bg-gray-700 hover:bg-green-600 text-white rounded-lg transition-all">
                        Last Month
                    </button>
                    <button onclick="loadAIInsights('quarter')" class="px-6 py-2 bg-gray-700 hover:bg-green-600 text-white rounded-lg transition-all">
                        Last Quarter
                    </button>
                    <button onclick="loadAIInsights('year')" class="px-6 py-2 bg-gray-700 hover:bg-green-600 text-white rounded-lg transition-all">
                        Last Year
                    </button>
                </div>
            </div>
            <div id="aiInsightsContent" class="text-gray-300">
                <p class="text-center py-8">Select a period to generate insights...</p>
            </div>
        </div>
    </div>
</div>
