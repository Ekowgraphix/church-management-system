@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🕊️ Prayer Requests</h1>
                <p class="text-gray-600">Submit and pray for others</p>
            </div>
            <button onclick="togglePrayerForm()" class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 transition-all">
                <i class="fas fa-plus mr-2"></i>Submit Prayer Request
            </button>
        </div>
    </div>

    <!-- Submit Prayer Form -->
    <div id="prayerForm" class="bg-white rounded-2xl shadow-lg p-6 mb-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-800">Submit Your Prayer Request</h3>
            <button onclick="togglePrayerForm()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="submitPrayerForm" onsubmit="submitPrayer(event)">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Prayer Request *</label>
                <textarea id="prayerRequest" name="prayer_request" rows="4" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all" placeholder="Share your prayer need..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Be specific so others can pray effectively</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Category</label>
                <select id="prayerCategory" name="category" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all">
                    <option value="General">General</option>
                    <option value="Health">Health</option>
                    <option value="Family">Family</option>
                    <option value="Finances">Finances</option>
                    <option value="Ministry">Ministry</option>
                    <option value="Thanksgiving">Thanksgiving</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" id="confidential" name="confidential" class="mr-2 w-4 h-4">
                    <span class="text-gray-700">🔒 Keep this request confidential (Pastoral team only)</span>
                </label>
            </div>
            <div class="flex space-x-3">
                <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-700 transition-all">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Prayer
                </button>
                <button type="button" onclick="togglePrayerForm()" class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-400 transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Prayer Wall -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">💫 Prayer Wall</h3>
        <div class="space-y-4">
            @forelse($prayers as $prayer)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $prayer->member->full_name ?? 'Anonymous' }}</p>
                            <p class="text-sm text-gray-500">{{ $prayer->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-bold">
                            {{ $prayer->category ?? 'General' }}
                        </span>
                    </div>
                    <p class="text-gray-700 mb-3">{{ $prayer->request }}</p>
                    <div class="flex items-center space-x-3">
                        <button onclick="prayedFor({{ $prayer->id }}, this)" class="prayer-btn-{{ $prayer->id }} bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700 transition-all">
                            <i class="fas fa-praying-hands mr-1"></i><span class="prayer-text">I Prayed</span> (<span class="prayer-count">{{ rand(5, 20) }}</span>)
                        </button>
                        <button onclick="getAIScripture('{{ addslashes($prayer->request) }}')" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition-all">
                            <i class="fas fa-bible mr-1"></i>AI Scripture
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No prayer requests at this time</p>
            @endforelse
        </div>
    </div>
</div>

<script>
// Toggle Prayer Form
function togglePrayerForm() {
    const form = document.getElementById('prayerForm');
    form.classList.toggle('hidden');
    
    // Clear form if closing
    if (form.classList.contains('hidden')) {
        document.getElementById('submitPrayerForm').reset();
    } else {
        // Scroll to form and focus on textarea
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            document.getElementById('prayerRequest').focus();
        }, 300);
    }
}

// Submit Prayer Request
function submitPrayer(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Get values
    const request = formData.get('prayer_request');
    const category = formData.get('category');
    const confidential = formData.get('confidential') === 'on';
    
    // Validate
    if (!request || request.trim().length < 10) {
        alert('⚠️ Please enter a more detailed prayer request (at least 10 characters)');
        return;
    }
    
    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
    submitBtn.disabled = true;
    
    // Send to backend
    fetch('/volunteer/prayer/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            request: request,
            category: category,
            confidential: confidential
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success message
            alert('🙏 Prayer Request Submitted!\n\n' +
                  'Your prayer request has been added to the prayer wall.\n' +
                  (confidential ? '🔒 Your request is confidential and only visible to pastoral staff.' : '💫 The church family will be praying for you!'));
            
            // Reset and hide form
            form.reset();
            togglePrayerForm();
            
            // Reload page to show new prayer
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            alert('❌ Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('⚠️ Could not submit prayer request. Please try again.');
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Mark Prayer as Prayed For
function prayedFor(prayerId, button) {
    // Update UI immediately
    const countSpan = button.querySelector('.prayer-count');
    const textSpan = button.querySelector('.prayer-text');
    const currentCount = parseInt(countSpan.textContent);
    
    // Check if already prayed
    const alreadyPrayed = localStorage.getItem(`prayed_${prayerId}`);
    
    if (alreadyPrayed) {
        alert('🙏 You already prayed for this request!\n\nThank you for your continued prayers.');
        return;
    }
    
    // Increment count
    countSpan.textContent = currentCount + 1;
    
    // Change button appearance
    button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
    button.classList.add('bg-green-600', 'hover:bg-green-700');
    textSpan.textContent = '✓ Prayed';
    
    // Save to localStorage
    localStorage.setItem(`prayed_${prayerId}`, 'true');
    
    // Show encouragement
    const encouragements = [
        '🙏 Thank you for praying!\n\nYour prayers make a difference.',
        '✨ Prayer answered!\n\nGod hears every prayer.',
        '💫 Blessed to pray with you!\n\nKeep praying!',
        '🕊️ Your prayer matters!\n\nThank you for interceding.',
        '❤️ God bless you!\n\nYour faithfulness in prayer is appreciated.'
    ];
    
    const randomEncouragement = encouragements[Math.floor(Math.random() * encouragements.length)];
    
    setTimeout(() => {
        alert(randomEncouragement);
    }, 300);
    
    // Send to backend
    fetch(`/volunteer/prayer/${prayerId}/prayed`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .catch(error => console.error('Error:', error));
}

// Get AI Scripture for Prayer
function getAIScripture(prayerRequest) {
    // Show loading
    alert('🤖 AI Scripture Generator\n\n⏳ Finding relevant scriptures...');
    
    // Simulate AI response
    setTimeout(() => {
        // Sample scriptures based on common themes
        const scriptures = [
            {
                verse: 'Philippians 4:6-7',
                text: 'Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God. And the peace of God, which transcends all understanding, will guard your hearts and your minds in Christ Jesus.'
            },
            {
                verse: 'Matthew 7:7',
                text: 'Ask and it will be given to you; seek and you will find; knock and the door will be opened to you.'
            },
            {
                verse: 'Jeremiah 29:11',
                text: 'For I know the plans I have for you," declares the Lord, "plans to prosper you and not to harm you, plans to give you hope and a future.'
            },
            {
                verse: 'Psalm 46:1',
                text: 'God is our refuge and strength, an ever-present help in trouble.'
            },
            {
                verse: '1 John 5:14',
                text: 'This is the confidence we have in approaching God: that if we ask anything according to his will, he hears us.'
            }
        ];
        
        const randomScripture = scriptures[Math.floor(Math.random() * scriptures.length)];
        
        alert(`📖 Scripture for Your Prayer\n\n` +
              `${randomScripture.verse}\n\n` +
              `"${randomScripture.text}"\n\n` +
              `💡 Meditate on this word as you pray.`);
    }, 1500);
    
    // TODO: Replace with actual AI API call
    // fetch('/api/ai/scripture', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     },
    //     body: JSON.stringify({ prayer: prayerRequest })
    // })
}

// Check which prayers user has already prayed for on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[class*="prayer-btn-"]').forEach(button => {
        const prayerId = button.className.match(/prayer-btn-(\d+)/)[1];
        if (localStorage.getItem(`prayed_${prayerId}`)) {
            const textSpan = button.querySelector('.prayer-text');
            button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            textSpan.textContent = '✓ Prayed';
        }
    });
});

// Filter prayers by category (bonus feature)
function filterPrayers(category) {
    const prayers = document.querySelectorAll('.bg-gray-50.rounded-lg');
    prayers.forEach(prayer => {
        if (category === 'all') {
            prayer.style.display = 'block';
        } else {
            const badge = prayer.querySelector('.bg-purple-100');
            if (badge && badge.textContent.trim() === category) {
                prayer.style.display = 'block';
            } else {
                prayer.style.display = 'none';
            }
        }
    });
}
</script>
@endsection
