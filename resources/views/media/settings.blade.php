@extends('layouts.media')
@section('title', 'Settings')
@section('content')
<div class="space-y-6">
<div class="flex justify-between items-center mb-6">
<div><h1 class="text-4xl font-black text-white"><i class="fas fa-cog text-green-400 mr-3"></i>Settings</h1>
<p class="text-gray-400 mt-2">Configure media portal preferences and integrations</p></div>
<button onclick="saveAllSettings()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold"><i class="fas fa-save mr-2"></i>Save All</button></div>
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
<div class="lg:col-span-1"><div class="stat-card sticky top-6"><h3 class="text-lg font-bold text-white mb-4">Quick Navigation</h3>
<nav class="space-y-2"><a href="#preferences" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-sliders-h mr-2"></i>Media Preferences</a>
<a href="#integrations" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-plug mr-2"></i>Integrations</a>
<a href="#notifications" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-bell mr-2"></i>Notifications</a>
<a href="#permissions" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-users-cog mr-2"></i>Permissions</a>
<a href="#theme" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-palette mr-2"></i>Theme</a>
<a href="#backup" class="block px-4 py-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-white transition-all"><i class="fas fa-database mr-2"></i>Backup</a></nav></div></div>
<div class="lg:col-span-3 space-y-6">
<div id="preferences" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-sliders-h text-green-400 mr-2"></i>Media Preferences</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-white font-semibold mb-2">Default Video Resolution</label>
<select class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="720p">720p (HD)</option><option value="1080p" selected>1080p (Full HD)</option><option value="1440p">1440p (2K)</option><option value="2160p">2160p (4K)</option></select></div>
<div><label class="block text-white font-semibold mb-2">Default Video Format</label>
<select class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="mp4" selected>MP4 (Most Compatible)</option><option value="webm">WebM (Web Optimized)</option><option value="mov">MOV (Apple)</option></select></div>
<div><label class="block text-white font-semibold mb-2">Default Image Format</label>
<select class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="jpg" selected>JPG (Smaller Size)</option><option value="png">PNG (Better Quality)</option><option value="webp">WebP (Modern)</option></select></div>
<div><label class="block text-white font-semibold mb-2">Max Upload Size</label>
<select class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="50">50 MB</option><option value="100" selected>100 MB</option><option value="200">200 MB</option><option value="500">500 MB</option></select></div></div>
<div class="mt-4"><label class="flex items-center"><input type="checkbox" checked class="mr-2"><span class="text-white">Auto-optimize images on upload</span></label>
<label class="flex items-center mt-2"><input type="checkbox" checked class="mr-2"><span class="text-white">Generate thumbnails automatically</span></label>
<label class="flex items-center mt-2"><input type="checkbox" class="mr-2"><span class="text-white">Watermark images with church logo</span></label></div></div>
<div id="integrations" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-plug text-blue-400 mr-2"></i>Platform Integrations</h2>
<div class="space-y-4"><div class="p-4 bg-white/5 rounded-xl"><div class="flex items-center justify-between mb-3">
<div class="flex items-center"><i class="fab fa-youtube text-3xl text-red-500 mr-3"></i><div><h3 class="text-white font-bold">YouTube</h3>
<p class="text-gray-400 text-sm">Upload videos to YouTube channel</p></div></div>
<label class="switch"><input type="checkbox" id="youtube_enabled"><span class="slider"></span></label></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-3"><input type="text" placeholder="API Key" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<input type="text" placeholder="Channel ID" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div></div>
<div class="p-4 bg-white/5 rounded-xl"><div class="flex items-center justify-between mb-3">
<div class="flex items-center"><i class="fab fa-facebook text-3xl text-blue-600 mr-3"></i><div><h3 class="text-white font-bold">Facebook</h3>
<p class="text-gray-400 text-sm">Post to Facebook page</p></div></div>
<label class="switch"><input type="checkbox"><span class="slider"></span></label></div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-3"><input type="text" placeholder="Page Access Token" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm">
<input type="text" placeholder="Page ID" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm"></div></div>
<div class="p-4 bg-white/5 rounded-xl"><div class="flex items-center justify-between mb-3">
<div class="flex items-center"><i class="fab fa-vimeo text-3xl text-blue-400 mr-3"></i><div><h3 class="text-white font-bold">Vimeo</h3>
<p class="text-gray-400 text-sm">Upload videos to Vimeo</p></div></div>
<label class="switch"><input type="checkbox"><span class="slider"></span></label></div>
<input type="text" placeholder="Access Token" class="px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm w-full"></div></div></div>
<div id="notifications" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-bell text-yellow-400 mr-2"></i>Notification Preferences</h2>
<div class="space-y-3"><label class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
<div><span class="text-white font-semibold">New Upload Notifications</span><p class="text-gray-400 text-sm">Get notified when team members upload media</p></div>
<input type="checkbox" checked class="ml-4"></label>
<label class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
<div><span class="text-white font-semibold">Livestream Alerts</span><p class="text-gray-400 text-sm">Notify when livestream starts/ends</p></div>
<input type="checkbox" checked class="ml-4"></label>
<label class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
<div><span class="text-white font-semibold">Storage Warnings</span><p class="text-gray-400 text-sm">Alert when storage reaches 80%</p></div>
<input type="checkbox" checked class="ml-4"></label>
<label class="flex items-center justify-between p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-all">
<div><span class="text-white font-semibold">Weekly Reports</span><p class="text-gray-400 text-sm">Send weekly activity summary</p></div>
<input type="checkbox" class="ml-4"></label></div></div>
<div id="permissions" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-users-cog text-purple-400 mr-2"></i>Team Permissions</h2>
<div class="overflow-x-auto"><table class="w-full"><thead><tr class="border-b border-white/20">
<th class="text-left text-white font-semibold py-3">Permission</th><th class="text-center text-white font-semibold py-3">Upload</th>
<th class="text-center text-white font-semibold py-3">Edit</th><th class="text-center text-white font-semibold py-3">Delete</th>
<th class="text-center text-white font-semibold py-3">Publish</th></tr></thead><tbody>
<tr class="border-b border-white/10"><td class="py-3 text-white">Media Team Members</td>
<td class="text-center"><input type="checkbox" checked></td><td class="text-center"><input type="checkbox" checked></td>
<td class="text-center"><input type="checkbox"></td><td class="text-center"><input type="checkbox" checked></td></tr>
<tr class="border-b border-white/10"><td class="py-3 text-white">Volunteers</td>
<td class="text-center"><input type="checkbox" checked></td><td class="text-center"><input type="checkbox"></td>
<td class="text-center"><input type="checkbox"></td><td class="text-center"><input type="checkbox"></td></tr>
<tr><td class="py-3 text-white">Viewers Only</td><td class="text-center"><input type="checkbox"></td>
<td class="text-center"><input type="checkbox"></td><td class="text-center"><input type="checkbox"></td>
<td class="text-center"><input type="checkbox"></td></tr></tbody></table></div></div>
<div id="theme" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-palette text-pink-400 mr-2"></i>Theme Customization</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block text-white font-semibold mb-2">Theme Mode</label>
<div class="flex space-x-2"><button id="darkMode" onclick="setThemeMode('dark')" class="flex-1 px-4 py-3 bg-white/10 border-2 border-green-400 rounded-lg text-white font-semibold transition-all">
<i class="fas fa-moon mr-2"></i>Dark</button><button id="lightMode" onclick="setThemeMode('light')" class="flex-1 px-4 py-3 bg-white/5 border-2 border-white/20 rounded-lg text-gray-400 transition-all">
<i class="fas fa-sun mr-2"></i>Light</button></div></div>
<div><label class="block text-white font-semibold mb-2">Accent Color</label>
<div class="grid grid-cols-4 gap-2"><button onclick="setAccentColor('green')" class="color-btn w-12 h-12 rounded-lg bg-green-500 border-4 border-white transition-all"></button>
<button onclick="setAccentColor('blue')" class="color-btn w-12 h-12 rounded-lg bg-blue-500 border-2 border-white/20 transition-all"></button>
<button onclick="setAccentColor('purple')" class="color-btn w-12 h-12 rounded-lg bg-purple-500 border-2 border-white/20 transition-all"></button>
<button onclick="setAccentColor('orange')" class="color-btn w-12 h-12 rounded-lg bg-orange-500 border-2 border-white/20 transition-all"></button></div></div></div></div>
<div id="backup" class="stat-card"><h2 class="text-2xl font-bold text-white mb-4"><i class="fas fa-database text-cyan-400 mr-2"></i>Backup & Restore</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="p-4 bg-white/5 rounded-xl"><h3 class="text-white font-bold mb-2">
<i class="fas fa-cloud-download-alt mr-2"></i>Backup Media</h3><p class="text-gray-400 text-sm mb-3">Download all media files and metadata</p>
<button class="w-full px-4 py-2 gradient-green rounded-lg text-white font-semibold"><i class="fas fa-download mr-2"></i>Create Backup</button></div>
<div class="p-4 bg-white/5 rounded-xl"><h3 class="text-white font-bold mb-2"><i class="fas fa-cloud-upload-alt mr-2"></i>Restore Media</h3>
<p class="text-gray-400 text-sm mb-3">Restore from previous backup</p>
<button class="w-full px-4 py-2 bg-white/10 border border-white/20 rounded-lg text-white font-semibold">
<i class="fas fa-upload mr-2"></i>Select Backup File</button></div></div>
<div class="mt-4"><label class="flex items-center"><input type="checkbox" checked class="mr-2">
<span class="text-white">Enable automatic daily backups</span></label></div></div></div></div></div>
@push('styles')<style>.switch{position:relative;display:inline-block;width:50px;height:24px}
.switch input{opacity:0;width:0;height:0}.slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;
background-color:rgba(255,255,255,0.2);transition:.4s;border-radius:24px}
.slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background-color:white;
transition:.4s;border-radius:50%}input:checked + .slider{background-color:#10b981}
input:checked + .slider:before{transform:translateX(26px)}</style>@endpush
@push('scripts')<script>
function saveAllSettings(){
    alert('Settings saved successfully!');
    localStorage.setItem('mediaSettings', JSON.stringify({
        theme: currentTheme,
        accentColor: currentAccent
    }));
}

let currentTheme = localStorage.getItem('themeMode') || 'dark';
let currentAccent = localStorage.getItem('accentColor') || 'green';

function setThemeMode(mode) {
    currentTheme = mode;
    localStorage.setItem('themeMode', mode);
    
    const darkBtn = document.getElementById('darkMode');
    const lightBtn = document.getElementById('lightMode');
    const body = document.body;
    
    if (mode === 'dark') {
        darkBtn.className = 'flex-1 px-4 py-3 bg-white/10 border-2 border-green-400 rounded-lg text-white font-semibold transition-all';
        lightBtn.className = 'flex-1 px-4 py-3 bg-white/5 border-2 border-white/20 rounded-lg text-gray-400 transition-all';
        body.style.background = 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%)';
    } else {
        lightBtn.className = 'flex-1 px-4 py-3 bg-white/10 border-2 border-blue-400 rounded-lg text-gray-900 font-semibold transition-all';
        darkBtn.className = 'flex-1 px-4 py-3 bg-white/5 border-2 border-gray-300 rounded-lg text-gray-400 transition-all';
        body.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%)';
    }
}

function setAccentColor(color) {
    currentAccent = color;
    localStorage.setItem('accentColor', color);
    
    // Update all color buttons
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.className = 'color-btn w-12 h-12 rounded-lg border-2 border-white/20 transition-all ' + 
                       (btn.onclick.toString().includes(color) ? 'border-4 border-white' : '');
    });
    
    // Update accent colors throughout the page
    const accentMap = {
        green: '#10b981',
        blue: '#3b82f6',
        purple: '#a855f7',
        orange: '#f97316'
    };
    
    document.querySelectorAll('.gradient-green, .text-green-400, .border-green-400').forEach(el => {
        if (el.classList.contains('gradient-green')) {
            el.style.background = `linear-gradient(135deg, ${accentMap[color]} 0%, ${accentMap[color]}dd 100%)`;
        }
    });
    
    // Show feedback
    const feedback = document.createElement('div');
    feedback.className = 'fixed bottom-4 right-4 px-6 py-3 rounded-xl text-white font-semibold shadow-lg z-50';
    feedback.style.background = accentMap[color];
    feedback.innerHTML = `<i class="fas fa-check mr-2"></i>Accent color changed to ${color}!`;
    document.body.appendChild(feedback);
    setTimeout(() => feedback.remove(), 2000);
}

// Apply saved theme on page load
window.addEventListener('DOMContentLoaded', () => {
    if (currentTheme === 'light') {
        setThemeMode('light');
    }
    setAccentColor(currentAccent);
});
</script>@endpush
@endsection
