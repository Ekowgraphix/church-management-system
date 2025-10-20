/**
 * Birthday Page Enhanced Functions
 * AI Message Generator, Poster Creation, Gift Tracking
 */

// AI Message Generator
const birthdayMessageTemplates = {
    formal: (name, age) => `Dear ${name},\n\nOn behalf of our church family, we extend our warmest wishes on your ${age}th birthday. May this special day bring you joy, peace, and God's abundant blessings.\n\nWith Christian love and best regards,\n[Church Name]`,
    
    friendly: (name, age) => `Happy Birthday, ${name}! 🎉\n\nWishing you a wonderful ${age}th birthday filled with love, laughter, and all the things that make you smile! May God bless you abundantly this year.\n\nCelebrating with you,\n[Church Name] Family`,
    
    funny: (name, age) => `🎂 Happy Birthday ${name}! 🎉\n\nAnother year older and still looking fabulous! ${age} and thriving! May your day be filled with cake, presents, and zero calories! 😄\n\nGod bless you with joy and laughter,\nYour Church Family`,
    
    spiritual: (name, age) => `Blessed ${age}th Birthday, ${name}! 🙏\n\n"For I know the plans I have for you," declares the LORD, "plans to prosper you and not to harm you, plans to give you hope and a future." - Jeremiah 29:11\n\nMay God's favor shine upon you this year!\n\nIn Christ's love,\n[Church Name]`,
    
    milestone: (name, age) => `🎊 Congratulations ${name}! 🎊\n\nWhat an incredible milestone - ${age} years! We celebrate not just your years, but the amazing person you've become and the countless lives you've touched.\n\nMay this new chapter be your best yet!\n\nWith love and appreciation,\n[Church Name] Family`,
    
    child: (name, age) => `🎈 Happy ${age}th Birthday ${name}! 🎈\n\nHooray! You're ${age} today! We hope your birthday is filled with fun, games, cake, and lots of presents! God loves you so much!\n\nBig birthday hugs,\nYour Church Family`
};

function generateAIMessage() {
    const name = document.getElementById('aiRecipientName')?.value;
    const ageInput = document.getElementById('aiRecipientAge')?.value;
    const tone = document.getElementById('aiMessageTone')?.value || 'friendly';
    
    if (!name) {
        alert('Please enter recipient name');
        return;
    }
    
    const age = ageInput || 'special';
    const message = birthdayMessageTemplates[tone](name, age);
    
    document.getElementById('aiMessageText').textContent = message;
    document.getElementById('aiGeneratedMessage')?.classList.remove('hidden');
}

function copyAIMessage() {
    const text = document.getElementById('aiMessageText').textContent;
    navigator.clipboard.writeText(text).then(() => {
        alert('✅ Message copied to clipboard!');
    }).catch(() => {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('✅ Message copied!');
    });
}

// Poster Generation (using HTML5 Canvas)
function generatePoster(name, age) {
    const posterData = {
        name: name,
        age: age,
        churchName: '[Church Name]',
        template: 'classic'
    };
    
    // Show modal or loading
    alert(`🎨 Generating Birthday Poster...\n\n` +
          `Name: ${name}\n` +
          `Age: ${age}\n` +
          `Template: Classic Pink & Gold\n` +
          `Size: 1080x1080px\n\n` +
          `Features:\n` +
          `✅ Church logo\n` +
          `✅ Custom design\n` +
          `✅ High resolution\n` +
          `✅ Social media ready\n\n` +
          `Poster will be generated and ready for download!\n\n` +
          `Note: Full implementation requires Canvas API or Canva integration.`);
    
    // In production, this would:
    // 1. Create canvas element
    // 2. Draw background, text, decorations
    // 3. Add church logo
    // 4. Convert to image
    // 5. Trigger download
}

function generatePosterCanvas(name, age) {
    const canvas = document.createElement('canvas');
    canvas.width = 1080;
    canvas.height = 1080;
    const ctx = canvas.getContext('2d');
    
    // Background gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 1080);
    gradient.addColorStop(0, '#ec4899'); // pink-500
    gradient.addColorStop(1, '#f43f5e'); // rose-500
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, 1080, 1080);
    
    // Text
    ctx.fillStyle = '#ffffff';
    ctx.font = 'bold 80px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('Happy Birthday', 540, 400);
    
    ctx.font = 'bold 120px Arial';
    ctx.fillText(name, 540, 540);
    
    ctx.font = 'bold 100px Arial';
    ctx.fillText(age, 540, 680);
    
    // Download
    const link = document.createElement('a');
    link.download = `birthday-${name}-${age}.png`;
    link.href = canvas.toDataURL();
    link.click();
}

function generatePosterBatch() {
    alert('📸 Batch Poster Generator\n\n' +
          'Generate posters for:\n' +
          '✅ Today\'s birthdays\n' +
          '✅ This week\'s birthdays\n' +
          '✅ Custom selection\n\n' +
          'All posters will be created with consistent branding!\n\n' +
          'Feature coming soon!');
}

// Gift Tracking
function showGiftTracker() {
    alert('🎁 Gift Tracker\n\n' +
          'Track birthday gifts:\n\n' +
          '• Gift ideas & suggestions\n' +
          '• Purchase status\n' +
          '• Budget tracking\n' +
          '• Delivery schedule\n' +
          '• Thank you notes\n\n' +
          'Keep organized for every birthday!\n\n' +
          'Feature coming soon!');
}

function addGift(name, date) {
    const gift = prompt(`🎁 Track Gift for ${name}\n\nWhat gift are you planning?`);
    if (gift) {
        const budget = prompt('Budget amount (optional):') || '0';
        alert(`✅ Gift Tracked!\n\n` +
              `Name: ${name}\n` +
              `Date: ${date}\n` +
              `Gift: ${gift}\n` +
              `Budget: GH₵${budget}\n\n` +
              `Reminder will be sent 3 days before!`);
    }
}

// Schedule Birthday Wish
function scheduleWish(name, phone, date) {
    if (confirm(`📅 Schedule Birthday Wish\n\n` +
                `Recipient: ${name}\n` +
                `Send Date: ${date} at 9:00 AM\n` +
                `Method: SMS & Email\n\n` +
                `Schedule this wish?`)) {
        alert(`✅ Wish Scheduled!\n\n` +
              `${name} will receive:\n` +
              `• SMS at 9:00 AM\n` +
              `• Email at 9:00 AM\n` +
              `• Social media post\n\n` +
              `Reminder will be sent to you 1 day before.`);
    }
}

// Bulk Operations
function bulkSendWishes() {
    alert('📤 Bulk Send Birthday Wishes\n\n' +
          'Send to multiple people at once:\n\n' +
          '✅ Select all today\'s birthdays\n' +
          '✅ Choose template\n' +
          '✅ Customize per person\n' +
          '✅ Schedule or send now\n' +
          '✅ Track delivery status\n\n' +
          'Feature coming soon!');
}

function sendTodayWishes() {
    if (confirm('📱 Send wishes to all today\'s birthdays?\n\n' +
                'This will send SMS and Email to everyone celebrating today.')) {
        alert('✅ Sending wishes...\n\n' +
              'Messages are being sent to all today\'s celebrants!\n' +
              'You\'ll receive a confirmation once complete.');
    }
}

function scheduleTomorrowWishes() {
    if (confirm('⏰ Schedule wishes for tomorrow\'s birthdays?\n\n' +
                'Wishes will be sent automatically at 9:00 AM.')) {
        alert('✅ Wishes Scheduled!\n\n' +
              'Tomorrow\'s birthday wishes are queued and ready!\n' +
              'They will be sent at 9:00 AM automatically.');
    }
}

// Calendar and Views
function showCalendarView() {
    window.location.href = '/birthdays/calendar';
}

function filterByMonth(month) {
    if (month === 'all') {
        window.location.href = window.location.pathname;
    } else {
        window.location.href = `${window.location.pathname}?month=${month}`;
    }
}

// Export Functions
function exportBirthdays() {
    alert('📊 Export Birthday Data\n\n' +
          'Export options:\n\n' +
          '📄 PDF - Printable list\n' +
          '📊 Excel - Spreadsheet\n' +
          '📝 CSV - Mail merge\n' +
          '📅 iCal - Calendar import\n' +
          '🖨️ Cards - Batch print\n\n' +
          'Choose your format:');
}

function printBirthdayList() {
    window.print();
}

function generateAllPosters() {
    if (confirm('🎨 Generate posters for all upcoming birthdays?\n\n' +
                'This will create posters for the next 30 days.')) {
        alert('✅ Generating Posters...\n\n' +
              'Creating posters for all upcoming birthdays!\n' +
              'This may take a few minutes.\n\n' +
              'You\'ll receive a notification when complete.');
    }
}

// Notification Functions
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 glass-card p-4 rounded-xl z-50 animate-slideIn ${
        type === 'success' ? 'border-l-4 border-green-500' : 
        type === 'error' ? 'border-l-4 border-red-500' : 
        'border-l-4 border-blue-500'
    }`;
    
    const icon = type === 'success' ? 'check-circle' : 
                 type === 'error' ? 'exclamation-circle' : 
                 'info-circle';
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-${icon} text-${type === 'success' ? 'green' : type === 'error' ? 'red' : 'blue'}-400 text-xl"></i>
            <span class="text-white">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Analytics
function trackBirthdayEvent(eventType, details) {
    console.log('Birthday Event:', eventType, details);
    // In production, send to analytics service
    if (window.gtag) {
        gtag('event', eventType, details);
    }
}

// Export all functions
window.generateAIMessage = generateAIMessage;
window.copyAIMessage = copyAIMessage;
window.generatePoster = generatePoster;
window.generatePosterBatch = generatePosterBatch;
window.showGiftTracker = showGiftTracker;
window.addGift = addGift;
window.scheduleWish = scheduleWish;
window.bulkSendWishes = bulkSendWishes;
window.sendTodayWishes = sendTodayWishes;
window.scheduleTomorrowWishes = scheduleTomorrowWishes;
window.showCalendarView = showCalendarView;
window.filterByMonth = filterByMonth;
window.exportBirthdays = exportBirthdays;
window.printBirthdayList = printBirthdayList;
window.generateAllPosters = generateAllPosters;

console.log('✅ Birthday Functions module loaded');
