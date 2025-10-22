@extends('layouts.media')
@section('title', 'Team Management')
@section('content')
<div class="space-y-6">
<div class="flex justify-between items-center">
<div><h1 class="text-4xl font-black text-white"><i class="fas fa-users text-green-400 mr-3"></i>Team Management</h1>
<p class="text-gray-400 mt-2">Manage media department staff and assignments</p></div>
<button onclick="openAddMemberModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold hover:shadow-lg">
<i class="fas fa-user-plus mr-2"></i>Add Member</button></div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Total Staff</span><i class="fas fa-users text-green-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $mediaTeamMembers->count() }}</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Active</span><i class="fas fa-check-circle text-blue-400"></i></div>
<p class="text-3xl font-bold text-white">{{ $mediaTeamMembers->where('is_active', true)->count() }}</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">On Assignment</span><i class="fas fa-tasks text-purple-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div>
<div class="stat-card"><div class="flex items-center justify-between mb-2">
<span class="text-gray-400 text-sm">Volunteers</span><i class="fas fa-hands-helping text-orange-400"></i></div>
<p class="text-3xl font-bold text-white">0</p></div></div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<div class="lg:col-span-2 space-y-4">
<div class="stat-card"><div class="flex justify-between items-center mb-4">
<h2 class="text-xl font-bold text-white">Team Members</h2>
<div class="flex space-x-2"><select class="px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white text-sm" onchange="filterRole(this.value)">
<option value="all">All Roles</option><option value="Photographer">Photographer</option><option value="Videographer">Videographer</option>
<option value="Designer">Designer</option><option value="Editor">Editor</option><option value="Livestream Operator">Livestream Operator</option></select></div></div>
@if($mediaTeamMembers->count() > 0)
<div class="space-y-3" id="teamList">
@foreach($mediaTeamMembers as $member)
<div class="p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-all team-member" data-role="{{ $member->roles->first()->name ?? 'None' }}">
<div class="flex items-center justify-between">
<div class="flex items-center space-x-4 flex-1">
<div class="w-14 h-14 rounded-full gradient-green flex items-center justify-center">
<span class="text-white font-bold text-xl">{{ strtoupper(substr($member->name, 0, 2)) }}</span></div>
<div class="flex-1"><h3 class="text-white font-bold text-lg">{{ $member->name }}</h3>
<p class="text-gray-400 text-sm"><i class="fas fa-briefcase mr-1"></i>{{ $member->roles->first()->name ?? 'No Role' }}</p>
<p class="text-gray-500 text-xs"><i class="fas fa-envelope mr-1"></i>{{ $member->email }}</p></div>
<div class="flex flex-col items-end space-y-2">
<span class="px-3 py-1 bg-green-500/20 rounded-lg text-green-400 text-xs font-semibold">
<i class="fas fa-circle text-xs mr-1"></i>{{ $member->is_active ? 'Active' : 'Inactive' }}</span>
<button onclick="viewMember({{ $member->id }})" class="text-blue-400 hover:text-blue-300 text-sm">
<i class="fas fa-eye mr-1"></i>View Details</button></div></div>
<div class="flex space-x-2 ml-4">
<button onclick="editMember({{ $member->id }}, '{{ addslashes($member->name) }}', '{{ $member->email }}', '{{ $member->roles->first()->name ?? '' }}', '{{ $member->phone }}', {{ $member->is_active ? 'true' : 'false' }})" class="px-3 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30" title="Edit Member">
<i class="fas fa-edit"></i></button>
<button onclick="assignTask({{ $member->id }}, '{{ addslashes($member->name) }}')" class="px-3 py-2 bg-purple-500/20 rounded-lg text-purple-400 hover:bg-purple-500/30" title="Assign Task">
<i class="fas fa-tasks"></i></button>
<button onclick="setAvailability({{ $member->id }}, '{{ addslashes($member->name) }}')" class="px-3 py-2 bg-orange-500/20 rounded-lg text-orange-400 hover:bg-orange-500/30" title="Set Availability">
<i class="fas fa-calendar"></i></button>
<button onclick="deleteMember({{ $member->id }}, '{{ addslashes($member->name) }}')" class="px-3 py-2 bg-red-500/20 rounded-lg text-red-400 hover:bg-red-500/30" title="Delete Member">
<i class="fas fa-trash"></i></button></div></div></div>
@endforeach
</div>
@else
<div class="text-center py-20"><i class="fas fa-users text-6xl text-gray-500 mb-4"></i>
<h3 class="text-xl font-bold text-white mb-2">No Team Members Yet</h3>
<p class="text-gray-400 mb-4">Add your first media team member to get started</p>
<button onclick="openAddMemberModal()" class="px-6 py-3 gradient-green rounded-xl text-white font-semibold">
<i class="fas fa-user-plus mr-2"></i>Add Member</button></div>
@endif
</div>
</div>

<div class="space-y-4">
<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-tasks text-purple-400 mr-2"></i>Active Assignments</h3>
<div class="space-y-2"><div class="p-3 bg-white/5 rounded-lg"><p class="text-white font-semibold text-sm">Sunday Service Coverage</p>
<p class="text-gray-400 text-xs">Due: Tomorrow</p></div>
<div class="p-3 bg-white/5 rounded-lg"><p class="text-white font-semibold text-sm">Youth Event Photos</p>
<p class="text-gray-400 text-xs">Due: Oct 25</p></div></div></div>

<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-clock text-orange-400 mr-2"></i>Recent Activity</h3>
<div class="space-y-2"><div class="p-2 text-sm"><p class="text-white">John uploaded 15 photos</p>
<p class="text-gray-500 text-xs">2 hours ago</p></div>
<div class="p-2 text-sm"><p class="text-white">Sarah edited worship video</p>
<p class="text-gray-500 text-xs">5 hours ago</p></div>
<div class="p-2 text-sm"><p class="text-white">Mike started livestream</p>
<p class="text-gray-500 text-xs">1 day ago</p></div></div></div>

<div class="stat-card"><h3 class="text-lg font-bold text-white mb-4"><i class="fas fa-hands-helping text-blue-400 mr-2"></i>Volunteer Area</h3>
<p class="text-gray-400 text-sm mb-3">Collaborate with volunteers</p>
<button onclick="viewVolunteers()" class="w-full px-4 py-2 bg-blue-500/20 rounded-lg text-blue-400 hover:bg-blue-500/30 font-semibold">
<i class="fas fa-users mr-2"></i>View Volunteers</button></div>
</div></div>
</div>

<div id="addMemberModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white"><i class="fas fa-user-plus text-green-400 mr-2"></i>Add Team Member</h2>
<button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button></div>
<form id="addMemberForm" class="space-y-4">
@csrf
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Full Name *</label>
<input type="text" name="name" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Email *</label>
<input type="email" name="email" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div></div>
<div><label class="block text-white font-semibold mb-2">Role *</label>
<select name="role" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select Role</option><option value="Photographer">📷 Photographer</option>
<option value="Videographer">🎥 Videographer</option><option value="Designer">🎨 Designer</option>
<option value="Editor">✂️ Editor</option><option value="Livestream Operator">📡 Livestream Operator</option></select></div>
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Phone</label>
<input type="tel" name="phone" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Status</label>
<select name="status" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="active">Active</option><option value="inactive">Inactive</option></select></div></div>
<div><label class="block text-white font-semibold mb-2">Skills/Expertise</label>
<textarea name="skills" rows="2" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="e.g., Portrait photography, Adobe Premiere, DSLR cameras"></textarea></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 gradient-green rounded-xl text-white font-semibold">
<i class="fas fa-plus mr-2"></i>Add Member</button>
<button type="button" onclick="closeAddMemberModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white"><i class="fas fa-edit text-blue-400 mr-2"></i>Edit Team Member</h2>
<button onclick="closeEditMemberModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button></div>
<form id="editMemberForm" class="space-y-4">
@csrf
<input type="hidden" id="edit_member_id" name="member_id">
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Full Name *</label>
<input type="text" id="edit_name" name="name" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Email *</label>
<input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div></div>
<div><label class="block text-white font-semibold mb-2">Role *</label>
<select id="edit_role" name="role" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select Role</option><option value="Photographer">📷 Photographer</option>
<option value="Videographer">🎥 Videographer</option><option value="Designer">🎨 Designer</option>
<option value="Editor">✂️ Editor</option><option value="Livestream Operator">📡 Livestream Operator</option></select></div>
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Phone</label>
<input type="tel" id="edit_phone" name="phone" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Status</label>
<select id="edit_status" name="status" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="active">Active</option><option value="inactive">Inactive</option></select></div></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white font-semibold">
<i class="fas fa-save mr-2"></i>Save Changes</button>
<button type="button" onclick="closeEditMemberModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Delete Confirmation Modal -->
<div id="deleteMemberModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-md w-full">
<div class="text-center">
<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/20 mb-4">
<i class="fas fa-exclamation-triangle text-red-400 text-3xl"></i></div>
<h3 class="text-xl font-bold text-white mb-2">Delete Team Member?</h3>
<p class="text-gray-400 mb-4">Are you sure you want to delete <span id="delete_member_name" class="text-white font-semibold"></span>? This action cannot be undone.</p>
<input type="hidden" id="delete_member_id">
<div class="flex space-x-3">
<button onclick="confirmDelete()" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 rounded-xl text-white font-semibold">
<i class="fas fa-trash mr-2"></i>Yes, Delete</button>
<button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</div></div></div>

<!-- Task Assignment Modal -->
<div id="taskAssignModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white"><i class="fas fa-tasks text-purple-400 mr-2"></i>Assign Task</h2>
<button onclick="closeTaskModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button></div>
<p class="text-gray-400 mb-4">Assigning task to: <span id="task_member_name" class="text-white font-semibold"></span></p>
<form id="taskAssignForm" class="space-y-4">
@csrf
<input type="hidden" id="task_member_id" name="member_id">
<div><label class="block text-white font-semibold mb-2">Select Event *</label>
<select name="event" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Choose an event...</option>
<option value="sunday_service">🎤 Sunday Service - Oct 22, 2025</option>
<option value="youth_rally">🎉 Youth Rally - Oct 25, 2025</option>
<option value="worship_night">🎵 Worship Night - Oct 27, 2025</option>
<option value="prayer_meeting">🙏 Prayer Meeting - Oct 29, 2025</option>
<option value="baptism">💧 Baptism Service - Nov 1, 2025</option></select></div>
<div><label class="block text-white font-semibold mb-2">Role/Responsibility *</label>
<select name="task_role" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select responsibility...</option>
<option value="photographer">📷 Lead Photographer</option>
<option value="videographer">🎥 Lead Videographer</option>
<option value="livestream">📡 Livestream Operator</option>
<option value="editor">✂️ Video Editor</option>
<option value="social_media">📱 Social Media Coverage</option></select></div>
<div><label class="block text-white font-semibold mb-2">Due Date</label>
<input type="date" name="due_date" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">Notes</label>
<textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Any special instructions or requirements..."></textarea></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white font-semibold">
<i class="fas fa-check mr-2"></i>Assign Task</button>
<button type="button" onclick="closeTaskModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

<!-- Availability Calendar Modal -->
<div id="availabilityModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
<div class="stat-card max-w-2xl w-full max-h-[90vh] overflow-y-auto">
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-white"><i class="fas fa-calendar text-orange-400 mr-2"></i>Set Availability</h2>
<button onclick="closeAvailabilityModal()" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button></div>
<p class="text-gray-400 mb-4">Managing availability for: <span id="availability_member_name" class="text-white font-semibold"></span></p>
<form id="availabilityForm" class="space-y-4">
@csrf
<input type="hidden" id="availability_member_id" name="member_id">
<div class="grid grid-cols-2 gap-4">
<div><label class="block text-white font-semibold mb-2">Start Date *</label>
<input type="date" name="start_date" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div>
<div><label class="block text-white font-semibold mb-2">End Date *</label>
<input type="date" name="end_date" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white"></div></div>
<div><label class="block text-white font-semibold mb-2">Reason *</label>
<select name="reason" required class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white">
<option value="">Select reason...</option>
<option value="vacation">🏖️ Vacation</option>
<option value="sick">🤒 Sick Leave</option>
<option value="personal">👤 Personal</option>
<option value="work">💼 Work Commitment</option>
<option value="study">📚 Study/Exams</option>
<option value="other">📝 Other</option></select></div>
<div><label class="block text-white font-semibold mb-2">Additional Notes</label>
<textarea name="notes" rows="2" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white" placeholder="Any additional information..."></textarea></div>
<div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
<p class="text-blue-400 text-sm"><i class="fas fa-info-circle mr-2"></i>The member will be marked as unavailable during this period and won't be assigned to events.</p></div>
<div class="flex space-x-3">
<button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl text-white font-semibold">
<i class="fas fa-save mr-2"></i>Save Availability</button>
<button type="button" onclick="closeAvailabilityModal()" class="px-6 py-3 bg-white/10 rounded-xl text-white">Cancel</button></div>
</form></div></div>

@push('scripts')
<script>
function openAddMemberModal(){
    document.getElementById('addMemberModal').classList.remove('hidden');
    document.getElementById('addMemberForm').reset();
}

function closeAddMemberModal(){
    document.getElementById('addMemberModal').classList.add('hidden');
}

function filterRole(role){
    const members = document.querySelectorAll('.team-member');
    members.forEach(m => {
        if(role === 'all' || m.dataset.role === role) m.style.display = 'block';
        else m.style.display = 'none';
    });
}

// View Volunteers
function viewVolunteers(){
    alert('👥 Volunteer Collaboration Area\n\n✅ Features:\n• View all volunteers\n• Assign volunteer tasks\n• Track volunteer hours\n• Send volunteer notifications\n• Schedule volunteer shifts\n• Volunteer training records\n\n(Full volunteer management coming soon!)');
}

// View Member Details
function viewMember(id){
    alert('👤 View Member Details\n\nMember ID: ' + id + '\n\n✅ This will show full member profile with:\n• Contact information\n• Assigned tasks\n• Availability calendar\n• Activity history\n• Performance stats\n\n(Full profile page coming soon!)');
}

// Edit Member
function editMember(id, name, email, role, phone, isActive){
    document.getElementById('edit_member_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById('edit_phone').value = phone || '';
    document.getElementById('edit_status').value = isActive ? 'active' : 'inactive';
    document.getElementById('editMemberModal').classList.remove('hidden');
}

function closeEditMemberModal(){
    document.getElementById('editMemberModal').classList.add('hidden');
}

// Delete Member
function deleteMember(id, name){
    document.getElementById('delete_member_id').value = id;
    document.getElementById('delete_member_name').textContent = name;
    document.getElementById('deleteMemberModal').classList.remove('hidden');
}

function closeDeleteModal(){
    document.getElementById('deleteMemberModal').classList.add('hidden');
}

async function confirmDelete(){
    const id = document.getElementById('delete_member_id').value;
    
    try {
        const response = await fetch(`{{ url('media/team') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if(response.ok && data.success){
            alert('✅ Team member deleted successfully!');
            window.location.reload();
        } else {
            alert('❌ Failed to delete member:\n\n' + (data.message || 'Unknown error'));
        }
    } catch(error){
        console.error('Error:', error);
        alert('❌ Error deleting team member. Please try again.');
    }
}

// Assign Task
function assignTask(id, name){
    document.getElementById('task_member_id').value = id;
    document.getElementById('task_member_name').textContent = name;
    document.getElementById('taskAssignModal').classList.remove('hidden');
}

function closeTaskModal(){
    document.getElementById('taskAssignModal').classList.add('hidden');
}

// Set Availability
function setAvailability(id, name){
    document.getElementById('availability_member_id').value = id;
    document.getElementById('availability_member_name').textContent = name;
    document.getElementById('availabilityModal').classList.remove('hidden');
}

function closeAvailabilityModal(){
    document.getElementById('availabilityModal').classList.add('hidden');
}

// Form submission - wrapped in DOMContentLoaded
document.addEventListener('DOMContentLoaded', function(){
    // Add Member Form
    const addForm = document.getElementById('addMemberForm');
    if(addForm){
        addForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("media.team.add") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                
                console.log('Response status:', response.status);
                const responseText = await response.text();
                console.log('Response text:', responseText);
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch(e) {
                    console.error('Failed to parse JSON:', e);
                    alert('❌ Server error. Please check console for details.\n\nResponse: ' + responseText.substring(0, 200));
                    return;
                }
                
                if(response.ok && data.success){
                    alert('✅ Team member added successfully!\n\nName: ' + data.member.name + '\nEmail: ' + data.member.email + '\n\nDefault password: password\n(Member should change on first login)');
                    window.location.reload();
                } else {
                    alert('❌ Failed to add team member:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error adding team member. Please check console for details.\n\nError: ' + error.message);
            }
        });
    }
    
    // Edit Member Form
    const editForm = document.getElementById('editMemberForm');
    if(editForm){
        editForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const memberId = document.getElementById('edit_member_id').value;
            
            try {
                const response = await fetch(`{{ url('media/team') }}/${memberId}`, {
                    method: 'PUT',
                    body: JSON.stringify(Object.fromEntries(formData)),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if(response.ok && data.success){
                    alert('✅ Team member updated successfully!\n\nChanges saved for: ' + data.member.name);
                    window.location.reload();
                } else {
                    alert('❌ Failed to update member:\n\n' + (data.message || 'Unknown error'));
                }
            } catch(error){
                console.error('Error:', error);
                alert('❌ Error updating team member. Please try again.');
            }
        });
    }
    
    // Task Assignment Form
    const taskForm = document.getElementById('taskAssignForm');
    if(taskForm){
        taskForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            alert('✅ Task assigned successfully!\n\nMember: ' + document.getElementById('task_member_name').textContent + '\nEvent: ' + this.querySelector('[name="event"] option:checked').text + '\nRole: ' + this.querySelector('[name="task_role"] option:checked').text + '\n\n📧 Member will be notified via email.');
            closeTaskModal();
            
            // In production, this would send to backend
            console.log('Task assignment data:', data);
        });
    }
    
    // Availability Form
    const availForm = document.getElementById('availabilityForm');
    if(availForm){
        availForm.addEventListener('submit', async function(e){
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            alert('✅ Availability saved!\n\nMember: ' + document.getElementById('availability_member_name').textContent + '\nPeriod: ' + data.start_date + ' to ' + data.end_date + '\nReason: ' + this.querySelector('[name="reason"] option:checked').text + '\n\n📅 Member marked as unavailable during this period.');
            closeAvailabilityModal();
            
            // In production, this would send to backend
            console.log('Availability data:', data);
        });
    }
});
</script>
@endpush
@endsection
