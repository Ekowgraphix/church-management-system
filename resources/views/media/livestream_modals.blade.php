<!-- New Stream Modal -->
<div id="newStreamModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-3xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-broadcast-tower text-red-500 mr-2"></i>Create New Livestream</h2>
<button onclick="closeNewStreamModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<form id="newStreamForm" class="space-y-4">
@csrf
<div>
<label class="block text-white font-semibold mb-2">Stream Title *</label>
<input type="text" name="title" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Sunday Service - October 22"></div>

<div>
<label class="block text-white font-semibold mb-2">Description</label>
<textarea name="description" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Join us for worship..."></textarea></div>

<div class="grid grid-cols-2 gap-4">
<div>
<label class="block text-white font-semibold mb-2">Platform *</label>
<select name="platform" id="platform_select" onchange="updatePlatformFields()" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select platform...</option>
<option value="youtube">📺 YouTube Live</option>
<option value="facebook">📘 Facebook Live</option>
<option value="custom_rtmp">🖥️ Custom RTMP</option></select></div>

<div>
<label class="block text-white font-semibold mb-2">Scheduled Time</label>
<input type="datetime-local" name="scheduled_at" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div></div>

<div id="platform_fields"></div>

<div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<h4 class="text-white font-semibold mb-3">AI Features</h4>
<label class="flex items-center space-x-2 cursor-pointer mb-2">
<input type="checkbox" name="enable_captions" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Enable Auto-Captions (AI Generated)</span></label>
<label class="flex items-center space-x-2 cursor-pointer mb-2">
<input type="checkbox" name="enable_translation" class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Enable Real-time Translation (10+ languages)</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" name="auto_upload" checked class="w-4 h-4 rounded">
<span class="text-blue-300 text-sm">Auto-upload recording to Media Library</span></label></div>

<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 rounded-xl text-white font-semibold">
<i class="fas fa-plus-circle mr-2"></i>Create Stream</button>
<button type="button" onclick="closeNewStreamModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Stream Keys Modal -->
<div id="streamKeysModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-key text-yellow-400 mr-2"></i>Stream Keys & URLs</h2>
<button onclick="closeStreamKeysModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-4">
<div class="p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
<div class="flex items-center space-x-2 mb-2">
<i class="fab fa-youtube text-red-500 text-xl"></i>
<h3 class="text-white font-bold">YouTube Live</h3></div>
<div class="space-y-2">
<div>
<label class="text-gray-400 text-sm">Stream URL:</label>
<div class="flex space-x-2 mt-1">
<input type="text" id="youtube_url" value="rtmp://a.rtmp.youtube.com/live2" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="copyToClipboard('youtube_url')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div>
<div>
<label class="text-gray-400 text-sm">Stream Key:</label>
<div class="flex space-x-2 mt-1">
<input type="password" id="youtube_key" value="xxxx-xxxx-xxxx-xxxx-xxxx" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="toggleVisibility('youtube_key')" class="px-3 py-2 bg-gray-500 rounded-lg text-white">
<i class="fas fa-eye"></i></button>
<button onclick="copyToClipboard('youtube_key')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div></div></div>

<div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<div class="flex items-center space-x-2 mb-2">
<i class="fab fa-facebook text-blue-500 text-xl"></i>
<h3 class="text-white font-bold">Facebook Live</h3></div>
<div class="space-y-2">
<div>
<label class="text-gray-400 text-sm">Stream URL:</label>
<div class="flex space-x-2 mt-1">
<input type="text" id="facebook_url" value="rtmps://live-api-s.facebook.com:443/rtmp/" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="copyToClipboard('facebook_url')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div>
<div>
<label class="text-gray-400 text-sm">Stream Key:</label>
<div class="flex space-x-2 mt-1">
<input type="password" id="facebook_key" value="yyyy-yyyy-yyyy-yyyy-yyyy" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="toggleVisibility('facebook_key')" class="px-3 py-2 bg-gray-500 rounded-lg text-white">
<i class="fas fa-eye"></i></button>
<button onclick="copyToClipboard('facebook_key')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div></div></div>

<div class="p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg">
<div class="flex items-center space-x-2 mb-2">
<i class="fas fa-server text-purple-500 text-xl"></i>
<h3 class="text-white font-bold">Custom RTMP</h3></div>
<p class="text-gray-400 text-sm mb-2">Use these credentials with OBS, Streamlabs, or any RTMP encoder</p>
<div class="space-y-2">
<div>
<label class="text-gray-400 text-sm">Server URL:</label>
<div class="flex space-x-2 mt-1">
<input type="text" id="rtmp_url" value="rtmp://stream.church.com/live" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="copyToClipboard('rtmp_url')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div>
<div>
<label class="text-gray-400 text-sm">Stream Key:</label>
<div class="flex space-x-2 mt-1">
<input type="password" id="rtmp_key" value="zzzz-zzzz-zzzz-zzzz-zzzz" readonly class="flex-1 px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm font-mono">
<button onclick="toggleVisibility('rtmp_key')" class="px-3 py-2 bg-gray-500 rounded-lg text-white">
<i class="fas fa-eye"></i></button>
<button onclick="copyToClipboard('rtmp_key')" class="px-3 py-2 bg-blue-500 rounded-lg text-white">
<i class="fas fa-copy"></i></button></div></div></div></div>
</div>

<button onclick="closeStreamKeysModal()" class="w-full mt-4 px-6 py-3 bg-white/10 rounded-xl text-white font-semibold">Close</button>
</div></div>

<!-- Viewer Stats Modal -->
<div id="viewerStatsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-4xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-chart-line text-green-400 mr-2"></i>Viewer Statistics</h2>
<button onclick="closeViewerStatsModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="grid grid-cols-3 gap-4 mb-6">
<div class="stat-card">
<p class="text-gray-400 text-sm mb-1">Current Viewers</p>
<p class="text-3xl font-bold text-white">234</p></div>
<div class="stat-card">
<p class="text-gray-400 text-sm mb-1">Peak Viewers</p>
<p class="text-3xl font-bold text-green-400">467</p></div>
<div class="stat-card">
<p class="text-gray-400 text-sm mb-1">Total Views</p>
<p class="text-3xl font-bold text-blue-400">1,234</p></div></div>

<div class="stat-card mb-4">
<h3 class="text-white font-bold mb-3">Live + Replay Views</h3>
<div class="h-64 bg-white/5 rounded-lg flex items-center justify-center">
<p class="text-gray-400">📊 Chart Placeholder - Real-time viewer graph</p></div></div>

<div class="grid grid-cols-2 gap-4">
<div class="stat-card">
<h3 class="text-white font-bold mb-3">Top Platforms</h3>
<div class="space-y-2">
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">YouTube</span>
<span class="text-blue-400 font-bold">60%</span></div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">Facebook</span>
<span class="text-blue-400 font-bold">30%</span></div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">Website</span>
<span class="text-blue-400 font-bold">10%</span></div></div></div>

<div class="stat-card">
<h3 class="text-white font-bold mb-3">Geographic Distribution</h3>
<div class="space-y-2">
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">United States</span>
<span class="text-green-400 font-bold">450</span></div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">Canada</span>
<span class="text-green-400 font-bold">123</span></div>
<div class="flex justify-between items-center p-2 bg-white/5 rounded">
<span class="text-white">UK</span>
<span class="text-green-400 font-bold">89</span></div></div></div></div>
</div></div>

<!-- Chat Moderation Modal -->
<div id="chatModerationModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-4xl w-full h-[80vh] flex flex-col">
<div class="flex justify-between items-center mb-4">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-comments text-purple-400 mr-2"></i>Live Chat Moderation</h2>
<button onclick="closeChatModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="grid grid-cols-4 gap-2 mb-4">
<div class="stat-card">
<p class="text-gray-400 text-xs">Total Messages</p>
<p class="text-xl font-bold text-white">1,234</p></div>
<div class="stat-card">
<p class="text-gray-400 text-xs">Active Users</p>
<p class="text-xl font-bold text-green-400">89</p></div>
<div class="stat-card">
<p class="text-gray-400 text-xs">Removed</p>
<p class="text-xl font-bold text-red-400">12</p></div>
<div class="stat-card">
<p class="text-gray-400 text-xs">Filtered</p>
<p class="text-xl font-bold text-orange-400">5</p></div></div>

<div class="flex-1 overflow-y-auto bg-white/5 rounded-lg p-4 mb-4 space-y-2" id="chatMessages">
<div class="p-2 bg-white/5 rounded">
<div class="flex items-center justify-between mb-1">
<span class="text-blue-400 font-semibold text-sm">John Smith</span>
<button class="text-red-400 hover:text-red-300 text-xs">
<i class="fas fa-times"></i> Remove</button></div>
<p class="text-white text-sm">Great message! Praise God! 🙏</p></div>
<div class="p-2 bg-white/5 rounded">
<div class="flex items-center justify-between mb-1">
<span class="text-green-400 font-semibold text-sm">Sarah Johnson</span>
<button class="text-red-400 hover:text-red-300 text-xs">
<i class="fas fa-times"></i> Remove</button></div>
<p class="text-white text-sm">Amen! This is wonderful worship</p></div></div>

<div class="flex space-x-2">
<input type="text" placeholder="Type message..." class="flex-1 px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white">
<button class="px-4 py-2 bg-blue-500 rounded-lg text-white font-semibold">
<i class="fas fa-paper-plane mr-1"></i>Send</button>
<button onclick="enableSlowMode()" class="px-4 py-2 bg-orange-500/20 rounded-lg text-orange-400">
<i class="fas fa-hourglass mr-1"></i>Slow Mode</button>
<button onclick="clearChat()" class="px-4 py-2 bg-red-500/20 rounded-lg text-red-400">
<i class="fas fa-trash mr-1"></i>Clear</button></div>
</div></div>

<!-- AI Captions Modal -->
<div id="captionsModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-closed-captioning text-blue-400 mr-2"></i>AI Auto-Captions & Translation</h2>
<button onclick="closeCaptionsModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-4">
<div class="p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<label class="flex items-center justify-between cursor-pointer">
<div class="flex items-center space-x-3">
<i class="fas fa-microphone text-blue-400 text-xl"></i>
<div>
<p class="text-white font-semibold">Auto-Generated Captions</p>
<p class="text-gray-400 text-sm">AI transcribes speech to text in real-time</p></div></div>
<input type="checkbox" checked class="w-5 h-5 rounded"></label></div>

<div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
<h3 class="text-white font-semibold mb-3">
<i class="fas fa-language text-green-400 mr-2"></i>Real-time Translation</h3>
<p class="text-gray-400 text-sm mb-3">Select languages for automatic translation:</p>
<div class="grid grid-cols-2 gap-2">
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" checked class="w-4 h-4 rounded">
<span class="text-white text-sm">Spanish</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" class="w-4 h-4 rounded">
<span class="text-white text-sm">French</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" checked class="w-4 h-4 rounded">
<span class="text-white text-sm">Portuguese</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" class="w-4 h-4 rounded">
<span class="text-white text-sm">Chinese</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" class="w-4 h-4 rounded">
<span class="text-white text-sm">Arabic</span></label>
<label class="flex items-center space-x-2 cursor-pointer">
<input type="checkbox" class="w-4 h-4 rounded">
<span class="text-white text-sm">Korean</span></label></div></div>

<div class="flex space-x-3">
<button class="flex-1 px-6 py-3 bg-blue-500 rounded-xl text-white font-semibold">
<i class="fas fa-save mr-2"></i>Save Settings</button>
<button onclick="closeCaptionsModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</div>
</div></div>

<!-- Archive Manager Modal -->
<div id="archiveModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-5xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white">
<i class="fas fa-archive text-purple-400 mr-2"></i>Stream Archive</h2>
<button onclick="closeArchiveModal()" class="text-gray-400 hover:text-white">
<i class="fas fa-times text-xl"></i></button></div>

<div class="space-y-3">
<div class="p-4 bg-white/5 rounded-xl">
<div class="flex items-start justify-between">
<div class="flex-1">
<h3 class="text-white font-bold">Sunday Service - Oct 15, 2025</h3>
<p class="text-gray-400 text-sm">YouTube • 2:15:34 duration</p>
<div class="flex items-center space-x-3 mt-2 text-sm">
<span class="text-green-400"><i class="fas fa-eye mr-1"></i>1,234 views</span>
<span class="text-blue-400"><i class="fas fa-users mr-1"></i>467 peak</span></div></div>
<div class="flex space-x-2">
<button class="px-3 py-2 bg-blue-500 rounded-lg text-white text-sm">
<i class="fas fa-play mr-1"></i>Watch</button>
<button class="px-3 py-2 bg-green-500 rounded-lg text-white text-sm">
<i class="fas fa-download mr-1"></i>Download</button></div></div></div>

<div class="p-4 bg-white/5 rounded-xl">
<div class="flex items-start justify-between">
<div class="flex-1">
<h3 class="text-white font-bold">Youth Rally - Oct 12, 2025</h3>
<p class="text-gray-400 text-sm">Facebook • 1:45:20 duration</p>
<div class="flex items-center space-x-3 mt-2 text-sm">
<span class="text-green-400"><i class="fas fa-eye mr-1"></i>892 views</span>
<span class="text-blue-400"><i class="fas fa-users mr-1"></i>312 peak</span></div></div>
<div class="flex space-x-2">
<button class="px-3 py-2 bg-blue-500 rounded-lg text-white text-sm">
<i class="fas fa-play mr-1"></i>Watch</button>
<button class="px-3 py-2 bg-green-500 rounded-lg text-white text-sm">
<i class="fas fa-download mr-1"></i>Download</button></div></div></div>
</div>
</div></div>
