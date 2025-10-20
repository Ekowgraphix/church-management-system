@extends('layouts.pastor')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🙏 Prayer Requests</h1>
                <p class="text-gray-600">Review and respond to prayer needs</p>
            </div>
            <div class="flex space-x-2">
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>Mark All as Prayed
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-lg p-4 mb-6">
        <div class="flex space-x-4">
            <button class="px-6 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-semibold">Pending</button>
            <button class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-semibold">Prayed Over</button>
            <button class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-semibold">All</button>
        </div>
    </div>

    <!-- Prayer Requests List -->
    <div class="space-y-4">
        @forelse($prayers as $prayer)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $prayer->member->full_name ?? 'Anonymous' }}</h3>
                            <p class="text-sm text-gray-500">{{ $prayer->created_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold {{ $prayer->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($prayer->status) }}
                    </span>
                </div>

                <div class="mb-4">
                    <p class="text-gray-700">{{ $prayer->request }}</p>
                </div>

                @if($prayer->category)
                    <div class="mb-4">
                        <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">{{ $prayer->category }}</span>
                    </div>
                @endif

                <div class="flex space-x-3">
                    <button class="flex-1 bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Mark as Prayed Over
                    </button>
                    <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700">
                        <i class="fas fa-reply mr-2"></i>Send Encouragement
                    </button>
                    <button class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700">
                        <i class="fas fa-magic"></i> AI Prayer
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-praying-hands text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">No prayer requests at this time</p>
            </div>
        @endforelse
    </div>

    @if($prayers->hasPages())
        <div class="mt-6">
            {{ $prayers->links() }}
        </div>
    @endif
</div>

<script>
// Filter tabs functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.bg-white.rounded-2xl.shadow-lg.p-4 button');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-yellow-100', 'text-yellow-700');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });
            
            // Add active class to clicked button
            this.classList.remove('text-gray-600', 'hover:bg-gray-100');
            this.classList.add('bg-yellow-100', 'text-yellow-700');
            
            // Show notification
            showNotification(`Filtering: ${this.textContent}`, 'info');
        });
    });
});

// Mark as Prayed Over
function markAsPrayed(element) {
    const card = element.closest('.bg-white.rounded-2xl');
    const statusBadge = card.querySelector('span.rounded-full');
    
    // Update status badge
    statusBadge.classList.remove('bg-yellow-100', 'text-yellow-700');
    statusBadge.classList.add('bg-green-100', 'text-green-700');
    statusBadge.textContent = 'Prayed Over';
    
    // Update button
    element.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Prayed Over';
    element.classList.remove('bg-green-600', 'hover:bg-green-700');
    element.classList.add('bg-gray-400');
    element.disabled = true;
    
    showNotification('✓ Marked as prayed over. God bless!', 'success');
}

// Send Encouragement
function sendEncouragement(memberName) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-heart text-red-500 mr-2"></i>Send Encouragement
                </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <p class="text-gray-600 mb-4">To: <strong>${memberName}</strong></p>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Your Message</label>
                <textarea id="encouragementMessage" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Write your encouraging message..."></textarea>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Quick Templates:</p>
                <div class="flex flex-wrap gap-2">
                    <button onclick="applyEncouragement('praying')" class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full hover:bg-blue-100">We're praying for you</button>
                    <button onclick="applyEncouragement('strength')" class="text-xs bg-purple-50 text-purple-700 px-3 py-1 rounded-full hover:bg-purple-100">God will give you strength</button>
                    <button onclick="applyEncouragement('faith')" class="text-xs bg-green-50 text-green-700 px-3 py-1 rounded-full hover:bg-green-100">Keep the faith</button>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="sendEncouragementMessage()" class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                    <i class="fas fa-paper-plane mr-2"></i>Send Message
                </button>
                <button onclick="closeModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Apply encouragement template
function applyEncouragement(type) {
    const templates = {
        praying: "Dear brother/sister,\n\nWe want you to know that our church family is praying for you during this time. May God's peace and comfort surround you.\n\nIn Christ's love,\nPastor",
        strength: "Hello,\n\nRemember that God promises: 'I can do all things through Christ who strengthens me' (Philippians 4:13). You are not alone in this journey.\n\nStay strong in faith,\nPastor",
        faith: "Greetings in Christ,\n\n'Now faith is confidence in what we hope for and assurance about what we do not see' (Hebrews 11:1). Keep trusting in God's perfect plan.\n\nBlessings,\nPastor"
    };
    
    document.getElementById('encouragementMessage').value = templates[type];
}

// Send encouragement message
function sendEncouragementMessage() {
    const message = document.getElementById('encouragementMessage').value;
    if (message.trim() === '') {
        alert('⚠️ Please write a message');
        return;
    }
    
    closeModal();
    showNotification('✓ Encouragement sent successfully!', 'success');
}

// AI Prayer Generator
function generateAIPrayer(requestText) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-magic text-purple-600 mr-2"></i>AI Prayer Generator
                </h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="prayerContent" class="mb-6">
                <div class="flex items-center justify-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-600"></i>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="copyPrayer()" class="flex-1 bg-purple-600 text-white py-3 rounded-lg font-semibold hover:bg-purple-700">
                    <i class="fas fa-copy mr-2"></i>Copy Prayer
                </button>
                <button onclick="closeModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    // Simulate AI generation
    setTimeout(() => {
        const prayer = generatePrayerText(requestText);
        document.getElementById('prayerContent').innerHTML = `
            <div class="bg-purple-50 rounded-lg p-6">
                <p class="text-gray-800 whitespace-pre-line" id="generatedPrayer">${prayer}</p>
            </div>
        `;
    }, 1500);
}

// Generate prayer text
function generatePrayerText(request) {
    return `Heavenly Father,

We come before You today lifting up this prayer request in faith. You know the deepest needs and concerns of Your child.

Lord, we ask that You would:
• Provide comfort and peace in this situation
• Grant wisdom and guidance
• Strengthen faith and trust in Your perfect plan
• Surround with Your loving presence

We trust in Your promises and believe that You hear our prayers. Thank You for Your faithfulness and unfailing love.

In Jesus' name we pray,
Amen.`;
}

// Copy prayer to clipboard
function copyPrayer() {
    const prayer = document.getElementById('generatedPrayer').textContent;
    navigator.clipboard.writeText(prayer).then(() => {
        showNotification('✓ Prayer copied to clipboard!', 'success');
    });
}

// Mark all as prayed
function markAllAsPrayed() {
    if (confirm('Mark all pending prayer requests as prayed over?')) {
        const cards = document.querySelectorAll('.bg-white.rounded-2xl.shadow-lg.p-6');
        cards.forEach(card => {
            const statusBadge = card.querySelector('span.rounded-full');
            if (statusBadge && statusBadge.textContent.includes('Pending')) {
                statusBadge.classList.remove('bg-yellow-100', 'text-yellow-700');
                statusBadge.classList.add('bg-green-100', 'text-green-700');
                statusBadge.textContent = 'Prayed Over';
            }
        });
        showNotification('✓ All prayers marked as prayed over!', 'success');
    }
}

// Close modal
function closeModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) modal.remove();
}

// Show notification
function showNotification(message, type) {
    const colors = {
        success: 'bg-green-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Add event listeners to buttons
document.addEventListener('DOMContentLoaded', function() {
    // Mark All as Prayed button
    const markAllBtn = document.querySelector('.bg-green-600.text-white');
    if (markAllBtn) {
        markAllBtn.addEventListener('click', markAllAsPrayed);
    }
    
    // Individual prayer buttons
    document.querySelectorAll('.bg-white.rounded-2xl.shadow-lg.p-6').forEach((card, index) => {
        const buttons = card.querySelectorAll('button');
        if (buttons.length >= 3) {
            // Mark as Prayed button
            buttons[0].addEventListener('click', function() {
                markAsPrayed(this);
            });
            
            // Send Encouragement button
            buttons[1].addEventListener('click', function() {
                const memberName = card.querySelector('h3').textContent;
                sendEncouragement(memberName);
            });
            
            // AI Prayer button
            buttons[2].addEventListener('click', function() {
                const requestText = card.querySelector('.text-gray-700').textContent;
                generateAIPrayer(requestText);
            });
        }
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection
