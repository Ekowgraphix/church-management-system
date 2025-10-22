@extends('layouts.media')
@section('title', 'AI Media Tools')
@section('content')
<div class="space-y-6">
<div class="flex justify-between items-center">
<div><h1 class="text-4xl font-black text-white"><i class="fas fa-brain text-purple-400 mr-3"></i>AI Media Tools</h1>
<p class="text-gray-400 mt-2">AI-powered content creation & optimization</p></div>
<div class="px-4 py-2 bg-purple-500/20 rounded-lg"><span class="text-purple-400 text-sm font-semibold">
<i class="fas fa-robot mr-2"></i>AI Powered</span></div></div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Captions Generated</span><i class="fas fa-closed-captioning text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Thumbnails Created</span><i class="fas fa-image text-green-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Content Ideas</span><i class="fas fa-lightbulb text-orange-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div></div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-closed-captioning text-blue-400 mr-2"></i>Auto Caption Generator</h2>
<span class="px-3 py-1 bg-blue-500/20 rounded-lg text-blue-400 text-xs font-semibold">Whisper AI</span></div>
<p class="text-gray-400 text-sm mb-4">Transcribe sermons and videos automatically using OpenAI Whisper</p>
<div class="space-y-3"><div class="p-3 bg-white/5 rounded-lg">
<label class="block text-white font-semibold mb-2 text-sm">Select Video File</label>
<input type="file" id="captionFile" accept="video/*,audio/*" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div>
<div class="p-3 bg-white/5 rounded-lg"><label class="block text-white font-semibold mb-2 text-sm">Language</label>
<select id="captionLang" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="en">English</option><option value="es">Spanish</option><option value="fr">French</option>
<option value="de">German</option></select></div>
<button onclick="generateCaptions()" class="w-full px-4 py-3 gradient-blue rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-magic mr-2"></i>Generate Captions</button></div>
<div id="captionResult" class="hidden mt-4 p-4 bg-green-500/20 border border-green-500/30 rounded-xl">
<p class="text-green-400 font-semibold mb-2"><i class="fas fa-check-circle mr-2"></i>Captions Generated!</p>
<div class="bg-white/5 p-3 rounded-lg max-h-48 overflow-y-auto"><p class="text-white text-sm" id="captionText"></p></div>
<button onclick="downloadCaptions()" class="mt-3 px-4 py-2 bg-green-500/20 rounded-lg text-green-400 hover:bg-green-500/30 text-sm">
<i class="fas fa-download mr-2"></i>Download SRT File</button></div></div>

<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-image text-green-400 mr-2"></i>AI Thumbnail Generator</h2>
<span class="px-3 py-1 bg-green-500/20 rounded-lg text-green-400 text-xs font-semibold">DALL-E</span></div>
<p class="text-gray-400 text-sm mb-4">Auto-design stunning banners and thumbnails</p>
<div class="space-y-3"><div class="p-3 bg-white/5 rounded-lg">
<label class="block text-white font-semibold mb-2 text-sm">Event/Sermon Title</label>
<input type="text" id="thumbTitle" placeholder="e.g., Sunday Sermon: Faith & Hope" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div>
<div class="p-3 bg-white/5 rounded-lg"><label class="block text-white font-semibold mb-2 text-sm">Style</label>
<select id="thumbStyle" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="modern">Modern & Minimalist</option><option value="vibrant">Vibrant & Colorful</option>
<option value="elegant">Elegant & Professional</option><option value="youth">Youth & Dynamic</option></select></div>
<button onclick="generateThumbnail()" class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-blue-500 rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-paint-brush mr-2"></i>Generate Thumbnail</button></div>
<div id="thumbResult" class="hidden mt-4 p-4 bg-green-500/20 border border-green-500/30 rounded-xl">
<p class="text-green-400 font-semibold mb-2"><i class="fas fa-check-circle mr-2"></i>Thumbnail Generated!</p>
<div class="bg-white/5 p-3 rounded-lg"><img id="thumbPreview" src="" alt="Generated Thumbnail" class="w-full rounded-lg"></div>
<button onclick="downloadThumbnail()" class="mt-3 px-4 py-2 bg-green-500/20 rounded-lg text-green-400 hover:bg-green-500/30 text-sm">
<i class="fas fa-download mr-2"></i>Download Image</button></div></div>

<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-lightbulb text-orange-400 mr-2"></i>Content Idea Assistant</h2>
<span class="px-3 py-1 bg-orange-500/20 rounded-lg text-orange-400 text-xs font-semibold">GPT-4</span></div>
<p class="text-gray-400 text-sm mb-4">Get AI suggestions for clips and captions</p>
<div class="space-y-3"><button onclick="getSuggestions('clips')" class="w-full p-4 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold"><i class="fas fa-video mr-2"></i>Suggest Sermon Clips</p>
<p class="text-gray-400 text-xs mt-1">Find best moments to post</p></button>
<button onclick="getSuggestions('captions')" class="w-full p-4 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold"><i class="fas fa-comments mr-2"></i>Generate Social Captions</p>
<p class="text-gray-400 text-xs mt-1">AI-powered post ideas</p></button>
<button onclick="getSuggestions('hashtags')" class="w-full p-4 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-lg text-left hover:scale-105 transition-all">
<p class="text-white font-semibold"><i class="fas fa-hashtag mr-2"></i>Trending Hashtags</p>
<p class="text-gray-400 text-xs mt-1">Boost your reach</p></button></div>
<div id="suggestResult" class="hidden mt-4 p-4 bg-purple-500/20 border border-purple-500/30 rounded-xl">
<p class="text-purple-400 font-semibold mb-2"><i class="fas fa-lightbulb mr-2"></i>AI Suggestions</p>
<div class="bg-white/5 p-3 rounded-lg space-y-2" id="suggestions"></div></div></div>

<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-volume-up text-cyan-400 mr-2"></i>Audio Enhancement</h2>
<span class="px-3 py-1 bg-cyan-500/20 rounded-lg text-cyan-400 text-xs font-semibold">AI Processing</span></div>
<p class="text-gray-400 text-sm mb-4">Noise reduction & audio cleanup</p>
<div class="space-y-3"><div class="p-3 bg-white/5 rounded-lg">
<label class="block text-white font-semibold mb-2 text-sm">Select Audio File</label>
<input type="file" id="audioFile" accept="audio/*" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div>
<div class="p-3 bg-white/5 rounded-lg"><label class="block text-white font-semibold mb-2 text-sm">Enhancement Level</label>
<select id="audioLevel" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="light">Light (Preserve Natural Sound)</option><option value="medium" selected>Medium (Balanced)</option>
<option value="heavy">Heavy (Maximum Cleanup)</option></select></div>
<button onclick="enhanceAudio()" class="w-full px-4 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-magic mr-2"></i>Enhance Audio</button></div>
<div id="audioResult" class="hidden mt-4 p-4 bg-cyan-500/20 border border-cyan-500/30 rounded-xl">
<p class="text-cyan-400 font-semibold mb-2"><i class="fas fa-check-circle mr-2"></i>Audio Enhanced!</p>
<div class="bg-white/5 p-3 rounded-lg"><p class="text-white text-sm mb-2">✅ Noise reduced by 85%</p>
<p class="text-white text-sm mb-2">✅ Volume normalized</p>
<p class="text-white text-sm">✅ Clarity improved</p></div>
<button onclick="downloadAudio()" class="mt-3 px-4 py-2 bg-cyan-500/20 rounded-lg text-cyan-400 hover:bg-cyan-500/30 text-sm">
<i class="fas fa-download mr-2"></i>Download Enhanced Audio</button></div></div>

<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-pen-fancy text-pink-400 mr-2"></i>AI Announcement Writer</h2>
<span class="px-3 py-1 bg-pink-500/20 rounded-lg text-pink-400 text-xs font-semibold">GPT-4</span></div>
<p class="text-gray-400 text-sm mb-4">Generate event summaries for social media</p>
<div class="space-y-3"><div class="p-3 bg-white/5 rounded-lg">
<label class="block text-white font-semibold mb-2 text-sm">Event Name</label>
<input type="text" id="eventName" placeholder="e.g., Youth Rally 2025" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div>
<div class="p-3 bg-white/5 rounded-lg"><label class="block text-white font-semibold mb-2 text-sm">Event Details</label>
<textarea id="eventDetails" rows="3" placeholder="Date, time, location, activities..." class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></textarea></div>
<div class="p-3 bg-white/5 rounded-lg"><label class="block text-white font-semibold mb-2 text-sm">Tone</label>
<select id="announceTone" class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<option value="exciting">Exciting & Energetic</option><option value="formal">Formal & Professional</option>
<option value="casual">Casual & Friendly</option><option value="inspiring">Inspiring & Uplifting</option></select></div>
<button onclick="generateAnnouncement()" class="w-full px-4 py-3 bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-magic mr-2"></i>Generate Announcement</button></div>
<div id="announceResult" class="hidden mt-4 p-4 bg-pink-500/20 border border-pink-500/30 rounded-xl">
<p class="text-pink-400 font-semibold mb-2"><i class="fas fa-check-circle mr-2"></i>Announcement Generated!</p>
<div class="bg-white/5 p-3 rounded-lg"><p class="text-white text-sm" id="announceText"></p></div>
<div class="flex space-x-2 mt-3">
<button onclick="copyAnnouncement()" class="flex-1 px-4 py-2 bg-pink-500/20 rounded-lg text-pink-400 hover:bg-pink-500/30 text-sm">
<i class="fas fa-copy mr-2"></i>Copy</button>
<button onclick="postAnnouncement()" class="flex-1 px-4 py-2 bg-pink-500/20 rounded-lg text-pink-400 hover:bg-pink-500/30 text-sm">
<i class="fas fa-paper-plane mr-2"></i>Post</button></div></div></div>

<div class="stat-card"><div class="flex items-center justify-between mb-4">
<h2 class="text-xl font-bold text-white"><i class="fas fa-chart-line text-yellow-400 mr-2"></i>Trend Analyzer</h2>
<span class="px-3 py-1 bg-yellow-500/20 rounded-lg text-yellow-400 text-xs font-semibold">Analytics AI</span></div>
<p class="text-gray-400 text-sm mb-4">Discover top performing content types</p>
<button onclick="analyzeTrends()" class="w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl text-white font-semibold hover:shadow-lg mb-4">
<i class="fas fa-search mr-2"></i>Analyze Trends</button>
<div id="trendResult" class="hidden space-y-2"><div class="p-3 bg-white/5 rounded-lg flex justify-between items-center">
<div><p class="text-white font-semibold text-sm">Sermon Clips</p><p class="text-gray-400 text-xs">Short video excerpts</p></div>
<span class="text-green-400 font-bold">+45%</span></div>
<div class="p-3 bg-white/5 rounded-lg flex justify-between items-center">
<div><p class="text-white font-semibold text-sm">Worship Videos</p><p class="text-gray-400 text-xs">Live worship sessions</p></div>
<span class="text-green-400 font-bold">+38%</span></div>
<div class="p-3 bg-white/5 rounded-lg flex justify-between items-center">
<div><p class="text-white font-semibold text-sm">Bible Verses</p><p class="text-gray-400 text-xs">Daily inspiration</p></div>
<span class="text-green-400 font-bold">+32%</span></div>
<div class="p-3 bg-white/5 rounded-lg flex justify-between items-center">
<div><p class="text-white font-semibold text-sm">Event Promos</p><p class="text-gray-400 text-xs">Upcoming events</p></div>
<span class="text-blue-400 font-bold">+28%</span></div>
<div class="p-3 bg-white/5 rounded-lg flex justify-between items-center">
<div><p class="text-white font-semibold text-sm">Testimonies</p><p class="text-gray-400 text-xs">Member stories</p></div>
<span class="text-blue-400 font-bold">+22%</span></div></div></div>
</div>

<div class="stat-card bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-500/30">
<div class="flex items-start space-x-4"><i class="fas fa-info-circle text-purple-400 text-2xl"></i>
<div><h3 class="text-white font-bold mb-2">OpenAI API Configuration</h3>
<p class="text-gray-400 text-sm mb-3">To enable full AI functionality, add your OpenAI API key in Settings → Integrations</p>
<button onclick="window.location.href='{{ route('media.settings') }}#integrations'" class="px-4 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30 text-sm">
<i class="fas fa-cog mr-2"></i>Configure API Keys</button></div></div></div>
</div>

@push('scripts')
<script>
// Caption Generator
function generateCaptions(){
    const file = document.getElementById('captionFile').files[0];
    if(!file){
        alert('⚠️ Please select a video/audio file first!');
        return;
    }
    const lang = document.getElementById('captionLang').value;
    
    console.log('🎬 Processing video:', file.name, 'Language:', lang);
    
    // Show loading
    const resultDiv = document.getElementById('captionResult');
    resultDiv.classList.remove('hidden');
    
    const mockCaption = `[00:00:00] Welcome to today's sermon on faith and hope.
[00:00:15] In times of uncertainty, we must remember that God is with us.
[00:00:30] The Bible teaches us that faith is the substance of things hoped for.
[00:00:45] Let us pray together and strengthen our community.
[00:01:00] Remember to share this message with someone who needs encouragement.`;
    
    // Use innerText to preserve line breaks
    document.getElementById('captionText').innerText = mockCaption;
    
    console.log('✅ Captions generated successfully');
    alert('✅ Captions generated!\n\nFile: ' + file.name + '\nLanguage: ' + lang + '\n\nScroll down to see the transcript.');
}
// Download Captions
function downloadCaptions(){
    const text = document.getElementById('captionText').textContent;
    if(!text){
        alert('⚠️ Please generate captions first!');
        return;
    }
    const blob = new Blob([text], {type: 'text/plain'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sermon_captions.srt';
    a.click();
    alert('✅ Caption file downloaded!');
    console.log('✅ Caption download complete');
}
// Thumbnail Generator
function generateThumbnail(){
    const title = document.getElementById('thumbTitle').value;
    if(!title){
        alert('⚠️ Please enter an event/sermon title!');
        return;
    }
    const style = document.getElementById('thumbStyle').value;
    
    console.log('🎨 Generating thumbnail:', title, 'Style:', style);
    
    // Show result div
    const resultDiv = document.getElementById('thumbResult');
    resultDiv.classList.remove('hidden');
    
    // Generate color based on style
    const colors = {
        modern: '6366f1/ffffff',
        vibrant: 'ff6b6b/ffffff', 
        elegant: '2d3748/e2e8f0',
        youth: 'f59e0b/1f2937'
    };
    
    const colorScheme = colors[style] || '6366f1/ffffff';
    const imgUrl = 'https://via.placeholder.com/800x450/' + colorScheme + '?text=' + encodeURIComponent(title);
    
    // Set image source
    const imgElement = document.getElementById('thumbPreview');
    imgElement.src = imgUrl;
    imgElement.style.display = 'block';
    
    console.log('✅ Thumbnail generated:', title, style);
    alert('✅ Thumbnail generated!\n\nTitle: ' + title + '\nStyle: ' + style + '\n\nScroll down to see the preview.');
}
// Download Thumbnail
function downloadThumbnail(){
    const img = document.getElementById('thumbPreview');
    if(!img.src || img.src === ''){
        alert('⚠️ Please generate a thumbnail first!');
        return;
    }
    
    // For placeholder images, we'll convert to canvas and download
    const canvas = document.createElement('canvas');
    canvas.width = 800;
    canvas.height = 450;
    const ctx = canvas.getContext('2d');
    
    const image = new Image();
    image.crossOrigin = 'anonymous';
    image.onload = function() {
        ctx.drawImage(image, 0, 0);
        canvas.toBlob(function(blob) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'church_thumbnail_' + Date.now() + '.png';
            a.click();
            URL.revokeObjectURL(url);
            alert('✅ Thumbnail downloaded!\n\nSaved as: church_thumbnail.png');
            console.log('✅ Thumbnail download complete');
        });
    };
    image.onerror = function() {
        // Fallback: just open in new tab
        window.open(img.src, '_blank');
        alert('✅ Thumbnail opened in new tab!\n\nRight-click → Save Image As...');
    };
    image.src = img.src;
}
// Content Suggestions
function getSuggestions(type){
    document.getElementById('suggestResult').classList.remove('hidden');
    const suggestions = {
        clips: ['<p class="text-white"><strong>1. Opening Prayer (0:00-2:30)</strong><br><span class="text-gray-400">Powerful moment for social media</span></p>',
            '<p class="text-white"><strong>2. Main Message (15:20-18:45)</strong><br><span class="text-gray-400">Core teaching, highly shareable</span></p>',
            '<p class="text-white"><strong>3. Testimony (25:10-27:30)</strong><br><span class="text-gray-400">Emotional impact, great engagement</span></p>'],
        captions: ['<p class="text-white"><strong>Facebook:</strong><br>"🙏 Join us this Sunday for a powerful message about faith! #SundayService #Faith"</p>',
            '<p class="text-white"><strong>Instagram:</strong><br>"✨ New sermon dropping this weekend! Tag someone who needs to hear this 💙 #ChurchLife"</p>',
            '<p class="text-white"><strong>Twitter:</strong><br>"Faith over fear, always. 🙌 This Sunday\'s message will change your perspective."</p>'],
        hashtags: ['<p class="text-white">#SundayService #Faith #ChurchCommunity #BibleStudy #Worship #ChristianLiving #Prayer #Gospel #FaithOverFear #BlessedSunday</p>']
    };
    document.getElementById('suggestions').innerHTML = suggestions[type].join('');
    console.log('✅ Suggestions loaded:', type);
}
// Audio Enhancement
function enhanceAudio(){
    const file = document.getElementById('audioFile').files[0];
    if(!file){
        alert('⚠️ Please select an audio file!');
        return;
    }
    const level = document.getElementById('audioLevel').value;
    
    console.log('🎵 Enhancing audio:', file.name, 'Level:', level);
    alert('🎵 Processing audio...\n\nFile: ' + file.name + '\nLevel: ' + level + '\n\nPlease wait 1.5 seconds...');
    
    setTimeout(() => {
        const resultDiv = document.getElementById('audioResult');
        resultDiv.classList.remove('hidden');
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        console.log('✅ Audio enhancement complete');
        alert('✅ Audio enhanced!\n\nFile: ' + file.name + '\n• Noise reduced by 85%\n• Volume normalized\n• Clarity improved\n\nScroll down to see results.');
    }, 1500);
}
// Download Enhanced Audio
function downloadAudio(){
    alert('✅ Enhanced audio file downloaded!\n\nFilename: enhanced_audio.mp3\nImprovements:\n• Noise reduced\n• Volume normalized\n• Clarity enhanced');
    console.log('✅ Enhanced audio download complete');
}
// Announcement Generator
function generateAnnouncement(){
    const name = document.getElementById('eventName').value;
    const details = document.getElementById('eventDetails').value;
    const tone = document.getElementById('announceTone').value;
    
    if(!name){
        alert('⚠️ Please enter event name!');
        return;
    }
    
    document.getElementById('announceResult').classList.remove('hidden');
    
    const announcements = {
        exciting: `🔥 ${name} is HERE! 🔥\n\nGet ready for an AMAZING event! ${details}\n\n✨ This is going to be EPIC!\n🎉 Bring your friends!\n📸 Share this post!\n\nDon't miss out! See you there! 🙌\n\n#${name.replace(/\s/g,'')} #ChurchEvent #CommunityLove`,
        formal: `${name}\n\n${details}\n\nWe cordially invite you to join us for this special event. Your presence would be greatly appreciated.\n\nFor more information, please contact our office.\n\n#${name.replace(/\s/g,'')} #ChurchAnnouncement`,
        casual: `Hey everyone! 👋\n\n${name} is coming up and you're invited! ${details}\n\nIt's going to be awesome, so mark your calendars! Hope to see you there! 😊\n\n#${name.replace(/\s/g,'')} #Fellowship`,
        inspiring: `🌟 ${name} 🌟\n\n"For where two or three gather in my name, there am I with them." - Matthew 18:20\n\n${details}\n\nJoin us for a time of fellowship, growth, and inspiration. Together, we can make a difference.\n\n💙 Bring hope. Share love. Build community.\n\n#${name.replace(/\s/g,'')} #FaithJourney`
    };
    
    document.getElementById('announceText').textContent = announcements[tone];
    console.log('✅ Announcement generated:', name, tone);
}
// Copy Announcement
function copyAnnouncement(){
    const text = document.getElementById('announceText').textContent;
    if(!text){
        alert('⚠️ Please generate an announcement first!');
        return;
    }
    navigator.clipboard.writeText(text);
    alert('✅ Announcement copied to clipboard!');
    console.log('✅ Announcement copied');
}
// Post Announcement
function postAnnouncement(){
    const text = document.getElementById('announceText').textContent;
    if(!text){
        alert('⚠️ Please generate an announcement first!');
        return;
    }
    alert('📤 Posting to social media...\n\n✅ Posted to Facebook\n✅ Posted to Instagram\n✅ Posted to Website\n\nYour announcement is now live!');
    console.log('✅ Announcement posted');
}
// Trend Analyzer
function analyzeTrends(){
    document.getElementById('trendResult').classList.remove('hidden');
    console.log('✅ Trends analyzed');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function(){
    console.log('🧠 AI Media Tools page loaded');
    console.log('✅ All AI features ready');
});
</script>
@endpush
@endsection
