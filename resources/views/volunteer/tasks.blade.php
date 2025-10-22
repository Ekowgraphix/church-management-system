@extends('layouts.volunteer')

@section('content')
<div>
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">✅ Task Manager</h1>
                <p class="text-gray-600">View and complete your assigned tasks</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Completion Rate</p>
                <p class="text-4xl font-bold text-green-600">80%</p>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="space-y-4">
        @foreach($tasks as $task)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-800">{{ $task->title }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $task->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                        <p class="text-gray-600 mb-3">{{ $task->instructions }}</p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="fas fa-calendar mr-1"></i>Due: {{ $task->deadline->format('M d, Y') }}</span>
                            <span><i class="fas fa-clock mr-1"></i>{{ $task->deadline->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex items-center space-x-3">
                        <button onclick="markComplete({{ $task->id }}, '{{ $task->title }}')" class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition-all">
                            <i class="fas fa-check mr-2"></i>Mark Complete
                        </button>
                        <button onclick="uploadProof({{ $task->id }}, '{{ $task->title }}')" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                            <i class="fas fa-upload mr-2"></i>Upload Proof
                        </button>
                        <button onclick="getAIHelp({{ $task->id }}, '{{ $task->title }}')" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition-all">
                            <i class="fas fa-robot mr-2"></i>AI Help
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Badges Section -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white mt-6">
        <h3 class="text-xl font-bold mb-4">🎖️ Your Badges</h3>
        <div class="flex space-x-4">
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-medal text-3xl mb-2"></i>
                <p class="text-sm font-semibold">5 Tasks</p>
            </div>
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-star text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Punctual</p>
            </div>
            <div class="bg-white/20 rounded-lg p-4 text-center">
                <i class="fas fa-trophy text-3xl mb-2"></i>
                <p class="text-sm font-semibold">Top Volunteer</p>
            </div>
        </div>
    </div>
</div>

<script>
// Mark Task Complete
function markComplete(taskId, taskTitle) {
    if(confirm(`✅ Mark "${taskTitle}" as complete?`)) {
        // Show loading state
        event.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
        event.target.disabled = true;
        
        // Send request to backend
        fetch(`/volunteer/tasks/${taskId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ task_id: taskId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('🎉 Task marked as complete!\n\n+10 Points earned!');
                window.location.reload();
            } else {
                alert('❌ Error: ' + data.message);
                event.target.disabled = false;
                event.target.innerHTML = '<i class="fas fa-check mr-2"></i>Mark Complete';
            }
        })
        .catch(error => {
            alert('⚠️ Could not connect to server. Please try again.');
            event.target.disabled = false;
            event.target.innerHTML = '<i class="fas fa-check mr-2"></i>Mark Complete';
        });
    }
}

// Upload Proof
function uploadProof(taskId, taskTitle) {
    // Create file input
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*,.pdf,.doc,.docx';
    
    input.onchange = function(e) {
        const file = e.target.files[0];
        if(!file) return;
        
        // Validate file size (max 10MB)
        if(file.size > 10 * 1024 * 1024) {
            alert('⚠️ File too large! Maximum size is 10MB.');
            return;
        }
        
        // Show confirmation
        if(confirm(`📤 Upload "${file.name}" as proof for "${taskTitle}"?`)) {
            // Create FormData
            const formData = new FormData();
            formData.append('proof', file);
            formData.append('task_id', taskId);
            
            // Upload
            alert('⏳ Uploading...\n\nPlease wait while we upload your proof.');
            
            fetch(`/volunteer/tasks/${taskId}/upload-proof`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('✅ Proof uploaded successfully!\n\nTask updated.');
                    window.location.reload();
                } else {
                    alert('❌ Upload failed: ' + data.message);
                }
            })
            .catch(error => {
                alert('⚠️ Upload failed. Please try again.');
            });
        }
    };
    
    input.click();
}

// Get AI Help
function getAIHelp(taskId, taskTitle) {
    alert(`🤖 AI Assistant for: "${taskTitle}"\n\n` +
          `Here are some helpful tips:\n\n` +
          `1. Break the task into smaller steps\n` +
          `2. Gather all necessary materials first\n` +
          `3. Set a timer to stay focused\n` +
          `4. Ask your team leader if you need clarification\n` +
          `5. Document your progress\n\n` +
          `💡 Pro tip: Start with the hardest part first!`);
    
    // TODO: Replace with actual AI integration
    // fetch('/api/ai/task-help', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     },
    //     body: JSON.stringify({ task_id: taskId })
    // })
}

// Filter Tasks (optional feature)
function filterTasks(status) {
    const tasks = document.querySelectorAll('.bg-white.rounded-2xl.shadow-lg');
    tasks.forEach(task => {
        if(status === 'all') {
            task.style.display = 'block';
        } else {
            const statusBadge = task.querySelector('.rounded-full');
            if(statusBadge && statusBadge.textContent.toLowerCase().includes(status)) {
                task.style.display = 'block';
            } else {
                task.style.display = 'none';
            }
        }
    });
}
</script>
@endsection
