@extends('layouts.organization')

@section('content')
<style>
    .desktop-grid-6 { display: grid !important; grid-template-columns: repeat(6, 1fr) !important; }
    .desktop-grid-4 { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; }
    @media (max-width: 9999px) {
        .desktop-grid-6 { grid-template-columns: repeat(6, 1fr) !important; }
        .desktop-grid-4 { grid-template-columns: repeat(4, 1fr) !important; }
    }
</style>

<!-- Welcome Section (Matching ChurchMang Style) -->
    <div class="bg-gradient-to-br from-teal-800/40 to-slate-800/40 backdrop-blur-sm rounded-3xl p-8 mb-8 border border-teal-500/20 shadow-2xl shadow-teal-500/10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-white mb-3">Welcome to Branch Management! 👋</h1>
                <p class="text-teal-100 text-lg">Oversee and manage all church branches across locations</p>
            </div>
            <div class="bg-gradient-to-br from-teal-600/30 to-teal-700/30 backdrop-blur-md border-2 border-teal-400/40 rounded-2xl px-6 py-4 shadow-lg shadow-teal-500/20">
                <div class="flex items-center gap-3">
                    <i class="fas fa-calendar-alt text-teal-300 text-2xl"></i>
                    <p class="text-white font-bold text-lg">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Buttons (Matching ChurchMang Style) -->
    <div class="desktop-grid-6 gap-6 mb-8" style="display: grid; grid-template-columns: repeat(6, 1fr);">
        <button onclick="addNewBranch()" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-3xl p-7 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50 border border-blue-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-plus text-3xl"></i>
                </div>
                <span class="font-bold text-base">Add Branch</span>
            </div>
        </button>

        <button onclick="viewBranchMap()" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 rounded-3xl p-7 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50 border border-purple-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-map-marked-alt text-3xl"></i>
                </div>
                <span class="font-bold text-base">View Map</span>
            </div>
        </button>

        <button onclick="exportBranches()" class="group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 rounded-3xl p-7 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50 border border-green-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-file-export text-3xl"></i>
                </div>
                <span class="font-bold text-base">Export Data</span>
            </div>
        </button>

        <button onclick="generateReport('branches')" class="group bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-3xl p-7 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/50 border border-orange-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-chart-bar text-3xl"></i>
                </div>
                <span class="font-bold text-base">Reports</span>
            </div>
        </button>

        <button onclick="sendBroadcast()" class="group bg-gradient-to-br from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 rounded-3xl p-7 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-pink-500/50 border border-pink-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-bullhorn text-3xl"></i>
                </div>
                <span class="font-bold text-base">Broadcast</span>
            </div>
        </button>

        <button onclick="refreshData()" class="group bg-gradient-to-br from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 rounded-3xl p-7 text-white transition-all duration-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/50 border border-cyan-400/20">
            <div class="flex flex-col items-center gap-3">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-sync-alt text-3xl"></i>
                </div>
                <span class="font-bold text-base">Refresh</span>
            </div>
        </button>
    </div>

    <!-- Statistics Cards (Matching ChurchMang Style) -->
    <div class="desktop-grid-4 gap-6 mb-8" style="display: grid; grid-template-columns: repeat(4, 1fr);">
        
        <!-- Total Branches Card -->
        <div class="group bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/50 border border-blue-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-code-branch text-3xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-extrabold bg-blue-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Total</span>
                </div>
            </div>
            <h3 class="text-6xl font-black mb-3">12</h3>
            <p class="text-lg font-bold opacity-90">Total Branches</p>
            <p class="text-sm opacity-75 mt-2">+2 this month</p>
        </div>

        <!-- Active Branches Card -->
        <div class="group bg-gradient-to-br from-green-500 via-green-600 to-green-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-green-500/50 border border-green-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-extrabold bg-green-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Active</span>
                </div>
            </div>
            <h3 class="text-6xl font-black mb-3">10</h3>
            <p class="text-lg font-bold opacity-90">Active Branches</p>
            <p class="text-sm opacity-75 mt-2">83.3% ratio</p>
        </div>

        <!-- Total Members Card -->
        <div class="group bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/50 border border-orange-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-extrabold bg-orange-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Members</span>
                </div>
            </div>
            <h3 class="text-6xl font-black mb-3">1.9K</h3>
            <p class="text-lg font-bold opacity-90">Total Members</p>
            <p class="text-sm opacity-75 mt-2">↑ 15% growth</p>
        </div>

        <!-- Pending Card -->
        <div class="group bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 rounded-3xl p-8 text-white transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-purple-500/50 border border-purple-400/30">
            <div class="flex items-start justify-between mb-6">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center group-hover:bg-white/30 transition-all shadow-lg">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
                <div class="text-right">
                    <span class="text-xs font-extrabold bg-purple-400/30 px-3 py-1.5 rounded-full uppercase tracking-wider">Pending</span>
                </div>
            </div>
            <h3 class="text-6xl font-black mb-3">2</h3>
            <p class="text-lg font-bold opacity-90">Pending Approval</p>
            <p class="text-sm opacity-75 mt-2">Needs review</p>
        </div>
    </div>

    <!-- Branches Table (Matching ChurchMang Style) -->
    <div class="bg-gradient-to-br from-gray-800/80 to-slate-900/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-700/50 overflow-hidden">
        
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-gray-900/90 to-slate-900/90 border-b border-teal-500/20 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-white mb-2">All Branches</h2>
                    <p class="text-teal-300 text-sm font-semibold">{{ count($branches) }} branches registered</p>
                </div>
                <div class="flex gap-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search branches..." 
                               class="bg-gray-700/50 backdrop-blur-sm border-2 border-teal-500/30 text-white placeholder-gray-400 rounded-xl px-5 py-3 pl-12 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-72 transition-all">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-teal-400 text-lg"></i>
                    </div>
                    <button onclick="exportBranches()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50">
                        <i class="fas fa-download text-lg"></i>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Branch Name</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Pastor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Members</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($branches as $branch)
                        <tr class="hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-church text-white"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">{{ $branch->name }}</p>
                                        <p class="text-xs text-gray-400">Est. 2020</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-red-400"></i>
                                    <span class="text-gray-300">{{ $branch->location }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user text-blue-400"></i>
                                    <span class="text-gray-300">{{ $branch->pastor }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-users text-purple-400"></i>
                                    <span class="text-white font-bold">{{ number_format($branch->members) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($branch->status == 'Active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500/20 text-green-400 rounded-lg text-xs font-bold border border-green-500/30">
                                        <i class="fas fa-check-circle"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-bold border border-yellow-500/30">
                                        <i class="fas fa-clock"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <button onclick="viewBranch('{{ $branch->name }}')" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </button>
                                    <button onclick="editBranch('{{ $branch->name }}')" 
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<!-- Map Modal -->
<div id="mapModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl w-full max-w-6xl max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between p-6 border-b bg-gradient-to-r from-teal-600 to-teal-700">
            <h2 class="text-2xl font-bold text-white">
                <i class="fas fa-map-marked-alt mr-2"></i>Branch Locations Map
            </h2>
            <button onclick="closeMapModal()" class="text-white hover:text-gray-200 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="map" style="height: 600px; width: 100%;"></div>
        <div class="p-4 bg-gray-50 border-t">
            <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-2"></i>
                Click on markers to see branch details. Powered by OpenStreetMap (Free & Open Source)
            </p>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map = null;

function addNewBranch() {
    alert('➕ Add New Branch\n\nOpening branch creation form...');
    // TODO: Open modal or redirect to form page
}

function viewBranchMap() {
    // Show modal
    document.getElementById('mapModal').classList.remove('hidden');
    
    // Initialize map if not already created
    if (!map) {
        // Center on Ghana (Accra)
        map = L.map('map').setView([5.6037, -0.1870], 7);
        
        // Add OpenStreetMap tiles (FREE - No API key needed!)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Branch locations (Replace with actual coordinates from your database)
        const branches = [
            {
                name: 'Faith Temple',
                location: 'Accra',
                pastor: 'Ps. Owusu',
                members: 850,
                lat: 5.6037,
                lng: -0.1870
            },
            {
                name: 'Grace Centre',
                location: 'Kumasi',
                pastor: 'Ps. K. Appiah',
                members: 600,
                lat: 6.6885,
                lng: -1.6244
            },
            {
                name: 'Hope Sanctuary',
                location: 'Takoradi',
                pastor: 'Ps. Mensah',
                members: 420,
                lat: 4.8845,
                lng: -1.7554
            }
        ];
        
        // Add markers for each branch
        branches.forEach(branch => {
            // Custom icon
            const markerIcon = L.divIcon({
                html: `<div style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); 
                              width: 40px; height: 40px; border-radius: 50% 50% 50% 0; 
                              transform: rotate(-45deg); display: flex; align-items: center; 
                              justify-content: center; border: 3px solid white; 
                              box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                         <i class="fas fa-church" style="color: white; transform: rotate(45deg); font-size: 16px;"></i>
                       </div>`,
                className: 'custom-marker',
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });
            
            const marker = L.marker([branch.lat, branch.lng], { icon: markerIcon }).addTo(map);
            
            // Popup content
            marker.bindPopup(`
                <div style="min-width: 200px;">
                    <h3 style="font-weight: bold; font-size: 16px; margin-bottom: 8px; color: #0891b2;">
                        <i class="fas fa-church"></i> ${branch.name}
                    </h3>
                    <p style="margin: 4px 0; color: #666;">
                        <i class="fas fa-map-marker-alt" style="width: 20px;"></i> ${branch.location}
                    </p>
                    <p style="margin: 4px 0; color: #666;">
                        <i class="fas fa-user-tie" style="width: 20px;"></i> ${branch.pastor}
                    </p>
                    <p style="margin: 4px 0; color: #666;">
                        <i class="fas fa-users" style="width: 20px;"></i> ${branch.members} members
                    </p>
                    <button onclick="viewBranch('${branch.name}')" 
                            style="margin-top: 10px; background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%); 
                                   color: white; padding: 6px 12px; border-radius: 6px; 
                                   border: none; cursor: pointer; font-weight: 600; width: 100%;">
                        View Details
                    </button>
                </div>
            `);
        });
    }
    
    // Refresh map size
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

function closeMapModal() {
    document.getElementById('mapModal').classList.add('hidden');
}

function exportBranches() {
    alert('📊 Export Branches\n\nExporting branch data to Excel...');
    // TODO: Implement Excel export
}

function bulkImport() {
    alert('📥 Bulk Import\n\nUpload CSV/Excel file to import multiple branches.');
    // TODO: Implement CSV import
}

function generateReport() {
    alert('📈 Generate Report\n\nCreating comprehensive branch performance report...');
    // TODO: Generate PDF report
}

function sendNotification() {
    alert('📬 Send Notification\n\nSend announcements to all branches.');
    // TODO: Open notification form
}

function viewBranch(branchName) {
    closeMapModal();
    alert(`👁️ View Branch: ${branchName}\n\nOpening detailed branch information...`);
    // TODO: Redirect to branch details page
}

function editBranch(branchName) {
    alert(`✏️ Edit Branch: ${branchName}\n\nOpening branch edit form...`);
    // TODO: Open edit modal or redirect
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('mapModal');
    if (e.target === modal) {
        closeMapModal();
    }
});
</script>
@endsection
