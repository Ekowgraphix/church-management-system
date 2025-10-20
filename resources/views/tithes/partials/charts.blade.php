<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Trend Chart -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-xl font-bold text-white mb-4">
            <i class="fas fa-chart-bar text-blue-400 mr-2"></i> Monthly Trend (Last 12 Months)
        </h3>
        <canvas id="monthlyTrendChart" height="300"></canvas>
    </div>

    <!-- Payment Method Breakdown Chart -->
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="text-xl font-bold text-white mb-4">
            <i class="fas fa-chart-pie text-green-400 mr-2"></i> Payment Methods (This Year)
        </h3>
        <canvas id="paymentChart" height="300"></canvas>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
