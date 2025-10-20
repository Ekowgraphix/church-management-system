<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Trend Chart
    const monthlyData = @json($monthlyTrend);
    const monthlyLabels = monthlyData.map(item => {
        const date = new Date(item.year, item.month - 1);
        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
    });
    const monthlyAmounts = monthlyData.map(item => parseFloat(item.total));

    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Tithes Amount (GHS)',
                data: monthlyAmounts,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return 'GHS ' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.1)' },
                    ticks: { 
                        color: '#9ca3af',
                        callback: function(value) {
                            return 'GHS ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    });

    // Payment Method Breakdown Chart
    const paymentData = @json($paymentBreakdown);
    const paymentLabels = paymentData.map(item => item.payment_method);
    const paymentAmounts = paymentData.map(item => parseFloat(item.total));

    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: paymentLabels,
            datasets: [{
                data: paymentAmounts,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                    'rgba(234, 179, 8, 0.8)',
                    'rgba(236, 72, 153, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(168, 85, 247)',
                    'rgb(234, 179, 8)',
                    'rgb(236, 72, 153)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#9ca3af', padding: 15, font: { size: 12 } }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': GHS ' + context.parsed.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // AI Insights Modal Functions
    function openAIInsights() {
        document.getElementById('aiInsightsModal').classList.remove('hidden');
    }

    function closeAIInsights() {
        document.getElementById('aiInsightsModal').classList.add('hidden');
    }

    function loadAIInsights(period) {
        const content = document.getElementById('aiInsightsContent');
        content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-green-400"></i><p class="mt-4">Generating insights...</p></div>';

        fetch(`{{ route('tithes.ai-insights') }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 border border-purple-500/30 rounded-xl p-6">
                            <h4 class="text-lg font-bold text-white mb-4">
                                <i class="fas fa-chart-line text-purple-400 mr-2"></i> Financial Summary - ${data.period}
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-400">Total Amount</p>
                                    <p class="text-2xl font-bold text-green-400">GHS ${data.total_amount}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Total Tithes</p>
                                    <p class="text-2xl font-bold text-blue-400">${data.total_tithes}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Unique Givers</p>
                                    <p class="text-2xl font-bold text-purple-400">${data.unique_givers}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Average Amount</p>
                                    <p class="text-2xl font-bold text-yellow-400">GHS ${data.average_amount}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/50 border border-gray-700 rounded-xl p-6">
                            <h4 class="text-lg font-bold text-white mb-4">
                                <i class="fas fa-users text-yellow-400 mr-2"></i> Top 10 Givers
                            </h4>
                            <div class="space-y-3">
                                ${data.top_givers.map((giver, index) => `
                                    <div class="flex items-center justify-between bg-gray-900/50 p-3 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="text-lg font-bold text-green-400 w-8">#${index + 1}</span>
                                            <span class="text-white">${giver.member ? giver.member.first_name + ' ' + giver.member.last_name : 'Unknown'}</span>
                                        </div>
                                        <span class="text-green-400 font-bold">GHS ${parseFloat(giver.total).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>

                        <div class="bg-blue-600/20 border border-blue-500/30 rounded-xl p-6">
                            <h4 class="text-lg font-bold text-white mb-3">
                                <i class="fas fa-lightbulb text-yellow-400 mr-2"></i> AI Recommendations
                            </h4>
                            <p class="text-sm text-gray-300 mb-4">${data.prompt}</p>
                            <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-all">
                                Generate Full Report with AI <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                `;
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = '<div class="text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-3xl mb-4"></i><p>Error loading insights. Please try again.</p></div>';
                console.error('Error:', error);
            });
    }
</script>
