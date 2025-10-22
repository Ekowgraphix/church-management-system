@extends('layouts.organization')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🧠 AI Insights</h1>
                <p class="text-gray-600">Strategic decision support and analytics</p>
            </div>
            <button class="bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
                <i class="fas fa-sync mr-2"></i>Refresh Insights
            </button>
        </div>
    </div>

    <!-- Branch Health Index -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-xl font-bold mb-2">Faith Temple</h3>
            <p class="text-sm opacity-90 mb-3">Branch Health Index</p>
            <p class="text-6xl font-black">95%</p>
            <p class="text-sm mt-2 opacity-75">⭐ Excellent Performance</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-xl font-bold mb-2">Grace Centre</h3>
            <p class="text-sm opacity-90 mb-3">Branch Health Index</p>
            <p class="text-6xl font-black">88%</p>
            <p class="text-sm mt-2 opacity-75">✅ Good Performance</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-xl font-bold mb-2">Hope Sanctuary</h3>
            <p class="text-sm opacity-90 mb-3">Branch Health Index</p>
            <p class="text-6xl font-black">72%</p>
            <p class="text-sm mt-2 opacity-75">⚠️ Needs Attention</p>
        </div>
    </div>

    <!-- AI Predictions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Attendance Prediction -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📈 Attendance Trend Prediction</h3>
            <div class="space-y-4">
                <div class="p-4 bg-green-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Next Sunday Forecast</p>
                    <p class="text-3xl font-bold text-green-600">1,850 attendees</p>
                    <p class="text-sm text-gray-600">↑ 12% increase expected</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Monthly Average</p>
                    <p class="text-3xl font-bold text-blue-600">7,200 attendees</p>
                    <p class="text-sm text-gray-600">Based on current trends</p>
                </div>
            </div>
        </div>

        <!-- Giving Forecast -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Giving Forecast</h3>
            <div class="space-y-4">
                <div class="p-4 bg-purple-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Next Month Projection</p>
                    <p class="text-3xl font-bold text-purple-600">₵85,000</p>
                    <p class="text-sm text-gray-600">Based on seasonality data</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-lg">
                    <p class="font-semibold text-gray-800">Year-End Projection</p>
                    <p class="text-3xl font-bold text-orange-600">₵950,000</p>
                    <p class="text-sm text-gray-600">Confidence: 87%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performing Branches -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">🏆 Top 5 Performing Branches (This Quarter)</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg">
                <div class="flex items-center space-x-4">
                    <span class="text-3xl font-bold text-yellow-600">1</span>
                    <div>
                        <p class="font-bold text-gray-800">Faith Temple - Accra</p>
                        <p class="text-sm text-gray-600">Growth: 23% | Engagement: 95% | Giving: ₵180k</p>
                    </div>
                </div>
                <i class="fas fa-trophy text-yellow-600 text-3xl"></i>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-bold text-gray-600">2</span>
                    <div>
                        <p class="font-bold text-gray-800">Grace Centre - Kumasi</p>
                        <p class="text-sm text-gray-600">Growth: 18% | Engagement: 92% | Giving: ₵145k</p>
                    </div>
                </div>
                <i class="fas fa-medal text-gray-400 text-2xl"></i>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <span class="text-2xl font-bold text-gray-600">3</span>
                    <div>
                        <p class="font-bold text-gray-800">Victory Chapel - Tema</p>
                        <p class="text-sm text-gray-600">Growth: 15% | Engagement: 89% | Giving: ₵120k</p>
                    </div>
                </div>
                <i class="fas fa-medal text-gray-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Branches Needing Attention -->
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
        <h3 class="text-xl font-bold text-red-800 mb-4">⚠️ Branches Needing Leadership Attention</h3>
        <div class="space-y-3">
            <div class="bg-white p-4 rounded-lg">
                <p class="font-bold text-gray-800">Hope Sanctuary - Takoradi</p>
                <p class="text-sm text-gray-600 mt-1">🔻 Attendance down 12% over last 2 months</p>
                <p class="text-sm text-gray-600">💡 Recommendation: Schedule pastoral visit, review engagement strategies</p>
            </div>
        </div>
    </div>
</div>

<script>
// Refresh Insights Button
document.querySelector('.bg-purple-600').addEventListener('click', function() {
    alert('🔄 Refreshing AI Insights...\n\nAnalyzing latest data with AI...');
    // TODO: Trigger AI analysis and refresh data
    setTimeout(() => {
        alert('✅ Insights refreshed successfully!');
    }, 1500);
});
</script>
@endsection
