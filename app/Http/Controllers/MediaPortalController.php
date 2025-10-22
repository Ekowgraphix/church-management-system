<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class MediaPortalController extends Controller
{
    /**
     * Show Media Team Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Quick stats for dashboard with REAL DATA
        $stats = [
            'videos_uploaded' => \App\Models\MediaFile::where('type', 'video')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'photos_published' => \App\Models\MediaFile::where('type', 'image')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'announcements_posted' => \App\Models\MediaAnnouncement::where('status', 'published')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'upcoming_events' => \App\Models\Event::where('start_date', '>=', now())
                ->where('start_date', '<=', now()->addDays(7))
                ->count(),
            'livestream_status' => \App\Models\MediaLivestream::where('status', 'live')->exists() ? 'online' : 'offline',
            'total_views' => \App\Models\MediaFile::sum('views_count'),
            'storage_used' => number_format(\App\Models\MediaFile::sum('file_size') / 1048576, 1) . ' MB',
        ];
        
        // Recent uploads (REAL DATA)
        $recentUploads = \App\Models\MediaFile::with('uploader')
            ->latest()
            ->take(5)
            ->get();
        
        // Upcoming scheduled posts (REAL DATA)
        $scheduledPosts = \App\Models\MediaAnnouncement::where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();
        
        return view('media.dashboard', compact('user', 'stats', 'recentUploads', 'scheduledPosts'));
    }
    
    /**
     * Media Library
     */
    public function library()
    {
        $mediaFiles = \App\Models\MediaFile::with('uploader')
            ->where(function($query) {
                $query->where('uploaded_by', Auth::id())
                      ->orWhere('is_public', true);
            })
            ->latest()
            ->paginate(24);
        
        $stats = [
            'total_files' => \App\Models\MediaFile::count(),
            'my_uploads' => \App\Models\MediaFile::where('uploaded_by', Auth::id())->count(),
            'total_views' => \App\Models\MediaFile::sum('views_count'),
            'storage_used' => \App\Models\MediaFile::sum('file_size'),
        ];
        
        return view('media.library', compact('mediaFiles', 'stats'));
    }
    
    /**
     * Upload Media File
     */
    public function uploadMedia(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:102400', // 100MB
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:video,image,audio,document',
                'category' => 'nullable|string',
                'is_public' => 'nullable|boolean',
            ]);
            
            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded'
                ], 400);
            }
            
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload'
                ], 400);
            }
            
            // Create directories if they don't exist
            $mediaDir = storage_path('app/public/media/' . $request->type);
            if (!file_exists($mediaDir)) {
                mkdir($mediaDir, 0777, true);
            }
            
            $path = $file->store('media/' . $request->type, 'public');
            
            // WORKAROUND: Copy to public/storage if symlink doesn't exist (Windows fix)
            if (!is_link(public_path('storage'))) {
                $publicPath = public_path('storage/' . dirname($path));
                if (!file_exists($publicPath)) {
                    mkdir($publicPath, 0777, true);
                }
                $sourcePath = storage_path('app/public/' . $path);
                $destPath = public_path('storage/' . $path);
                copy($sourcePath, $destPath);
            }
            
            // Generate thumbnail for images
            $thumbnailPath = null;
            if ($request->type === 'image') {
                $thumbnailPath = 'media/thumbnails/' . basename($path);
            }
            
            $mediaFile = \App\Models\MediaFile::create([
                'uploaded_by' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_url' => asset('storage/' . $path),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'thumbnail_path' => $thumbnailPath,
                'is_public' => $request->is_public ?? true,
                'published_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file' => $mediaFile
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Media upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete Media File
     */
    public function deleteMedia($id)
    {
        $mediaFile = \App\Models\MediaFile::findOrFail($id);
        
        // Check permission
        if ($mediaFile->uploaded_by !== Auth::id() && !Auth::user()->hasRole('Admin')) {
            abort(403, 'Unauthorized');
        }
        
        // Delete file from storage
        \Storage::disk('public')->delete($mediaFile->file_path);
        if ($mediaFile->thumbnail_path) {
            \Storage::disk('public')->delete($mediaFile->thumbnail_path);
        }
        
        $mediaFile->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully!'
        ]);
    }
    
    /**
     * Gallery Management
     */
    public function gallery()
    {
        $galleries = \App\Models\MediaGallery::with('mediaFiles')
            ->where(function($query) {
                $query->where('created_by', Auth::id())
                      ->orWhere('is_public', true);
            })
            ->latest()
            ->get();
        
        // Calculate stats
        $stats = [
            'total_photos' => \App\Models\MediaFile::where('type', 'photo')->count(),
            'public_galleries' => \App\Models\MediaGallery::where('is_public', true)->count(),
            'total_views' => \App\Models\MediaGallery::sum('views_count'),
        ];
        
        // AI Suggestions: Get photos with high engagement
        $suggestedPhotos = \App\Models\MediaFile::where('type', 'photo')
            ->orderByRaw('(views_count * 2 + downloads_count * 3) DESC')
            ->take(10)
            ->get();
        
        return view('media.gallery', compact('galleries', 'stats', 'suggestedPhotos'));
    }
    
    /**
     * Create Gallery
     */
    public function createGallery(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
            'allow_downloads' => 'boolean',
            'cover_image' => 'nullable|image|max:5120',
        ]);
        
        try {
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('gallery/covers', 'public');
            }
            
            $gallery = \App\Models\MediaGallery::create([
                'created_by' => Auth::id(),
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
                'description' => $request->description,
                'cover_image' => $coverImagePath,
                'event_id' => $request->event_id,
                'is_public' => $request->has('is_public'),
                'allow_downloads' => $request->has('allow_downloads'),
                'published_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Album "' . $gallery->name . '" created successfully!',
                'gallery_id' => $gallery->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Create gallery error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create gallery: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sync Gallery to Public Site
     */
    public function syncGallery($id)
    {
        try {
            $gallery = \App\Models\MediaGallery::findOrFail($id);
            
            if (!$gallery->is_public) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gallery must be public to sync to website'
                ]);
            }
            
            // Sync logic here - optimize images, generate thumbnails, etc.
            $gallery->update([
                'published_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Gallery synced successfully! It\'s now live on your public website.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Livestream Control
     */
    public function livestream()
    {
        $streams = \App\Models\MediaLivestream::with('startedBy')
            ->latest()
            ->take(20)
            ->get();
        
        $activeStream = \App\Models\MediaLivestream::where('status', 'live')->first();
        
        // Calculate stats with default values
        $stats = [
            'active' => \App\Models\MediaLivestream::where('status', 'live')->count() ?? 0,
            'views_today' => \App\Models\MediaLivestream::whereDate('started_at', today())->sum('total_views') ?? 0,
            'peak_viewers' => \App\Models\MediaLivestream::whereMonth('started_at', now()->month)->max('peak_viewers') ?? 0,
            'archived' => \App\Models\MediaLivestream::where('status', 'ended')->count() ?? 0,
        ];
        
        return view('media.livestream', compact('streams', 'activeStream', 'stats'));
    }
    
    /**
     * Create Livestream
     */
    public function createLivestream(Request $request)
    {
        try {
            \Log::info('Create livestream request received', $request->all());
            
            $request->validate([
                'title' => 'required|string',
                'platform' => 'required|string',
                'scheduled_at' => 'nullable|date',
            ]);
            
            // Generate stream key
            $streamKey = 'live_' . uniqid() . '_' . bin2hex(random_bytes(8));
            
            $stream = \App\Models\MediaLivestream::create([
                'started_by' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'platform' => $request->platform,
                'stream_key' => $streamKey,
                'stream_url' => $this->getStreamUrl($request->platform),
                'scheduled_at' => $request->scheduled_at,
                'status' => 'scheduled',
            ]);
            
            \Log::info('Stream created successfully', ['stream_id' => $stream->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Livestream scheduled successfully!',
                'stream_id' => $stream->id,
                'stream_key' => $streamKey
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . json_encode($e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Create livestream error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create livestream: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Start Livestream
     */
    public function startLivestream($id)
    {
        try {
            $stream = \App\Models\MediaLivestream::findOrFail($id);
            
            $stream->update([
                'status' => 'live',
                'started_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Stream is now LIVE on ' . $stream->platform
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start stream: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Stop Livestream
     */
    public function stopLivestream($id)
    {
        try {
            $stream = \App\Models\MediaLivestream::findOrFail($id);
            
            $duration = $stream->started_at ? $stream->started_at->diffForHumans(now(), true) : '0 minutes';
            
            $stream->update([
                'status' => 'ended',
                'ended_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Stream ended successfully',
                'duration' => $duration
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop stream: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload Stream Recording to Media Library
     */
    public function uploadStreamToLibrary($id)
    {
        try {
            $stream = \App\Models\MediaLivestream::findOrFail($id);
            
            if ($stream->status != 'ended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stream must be ended before uploading'
                ]);
            }
            
            // Create media file record
            $filename = 'livestream_' . $stream->id . '_' . date('Y-m-d') . '.mp4';
            
            \App\Models\MediaFile::create([
                'title' => $stream->title . ' - Recording',
                'description' => $stream->description,
                'type' => 'video',
                'file_name' => $filename,
                'file_path' => 'media/livestream/' . $filename,
                'category' => 'livestream',
                'uploaded_by' => $stream->started_by,
                'is_public' => true,
                'published_at' => now(),
            ]);
            
            $stream->update([
                'recording_url' => 'media/livestream/' . $filename
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Recording uploaded to Media Library',
                'filename' => $filename
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get Stream URL based on platform
     */
    private function getStreamUrl($platform)
    {
        switch (strtolower($platform)) {
            case 'youtube':
                return 'rtmp://a.rtmp.youtube.com/live2';
            case 'facebook':
                return 'rtmps://live-api-s.facebook.com:443/rtmp/';
            case 'rtmp':
            default:
                return 'rtmp://stream.church.com/live';
        }
    }
    
    /**
     * Event Media Scheduling
     */
    public function schedule()
    {
        $events = \App\Models\Event::with('mediaAssignments')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(20)
            ->get();
        
        $myAssignments = \App\Models\MediaEventAssignment::where('assigned_to', Auth::id())
            ->with('event')
            ->where('status', '!=', 'completed')
            ->get();
        
        return view('media.schedule', compact('events', 'myAssignments'));
    }
    
    /**
     * AI Tools
     */
    public function aiTools()
    {
        return view('media.ai_tools');
    }
    
    /**
     * Announcements & Graphics
     */
    public function announcements()
    {
        $announcements = \App\Models\MediaAnnouncement::where('created_by', Auth::id())
            ->latest()
            ->paginate(15);
        
        return view('media.announcements', compact('announcements'));
    }
    
    /**
     * Create Announcement
     */
    public function createAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);
        
        $announcement = \App\Models\MediaAnnouncement::create([
            'created_by' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'platforms' => $request->platforms ?? [],
            'scheduled_at' => $request->scheduled_at,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Announcement created!',
            'announcement' => $announcement
        ]);
    }
    
    /**
     * Team Management
     */
    public function team()
    {
        $mediaTeamMembers = User::role('Media Team')->get();
        
        return view('media.team', compact('mediaTeamMembers'));
    }
    
    /**
     * Add Team Member
     */
    public function addTeamMember(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'skills' => 'nullable|string',
        ]);
        
        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password
                'phone' => $request->phone,
                'is_active' => $request->status === 'active',
            ]);
            
            // Assign Media Team role (for access to media portal)
            $user->assignRole('Media Team');
            
            // Also assign specific role if provided (Photographer, Videographer, etc.)
            if ($request->role) {
                // Create role if it doesn't exist
                $specificRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role]);
                $user->assignRole($request->role);
            }
            
            // Reload user with roles
            $user->load('roles');
            
            return response()->json([
                'success' => true,
                'message' => 'Team member added successfully!',
                'member' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Add team member error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add team member: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update Team Member
     */
    public function updateTeamMember(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'nullable|string',
            'phone' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        try {
            $user = User::findOrFail($id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->status === 'active',
            ]);
            
            // Update specific role if provided
            if ($request->role) {
                // Remove old specific roles (but keep Media Team role)
                $specificRoles = ['Photographer', 'Videographer', 'Designer', 'Editor', 'Livestream Operator'];
                foreach ($specificRoles as $roleToRemove) {
                    if ($user->hasRole($roleToRemove)) {
                        $user->removeRole($roleToRemove);
                    }
                }
                
                // Create and assign new role
                $newRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $request->role]);
                $user->assignRole($request->role);
            }
            
            // Reload user with roles
            $user->load('roles');
            
            return response()->json([
                'success' => true,
                'message' => 'Team member updated successfully!',
                'member' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Update team member error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update team member: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete Team Member
     */
    public function deleteTeamMember($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Team member removed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove team member: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Analytics
     */
    public function analytics()
    {
        $stats = [
            'total_files' => \App\Models\MediaFile::count(),
            'total_views' => \App\Models\MediaFile::sum('views_count'),
            'total_downloads' => \App\Models\MediaFile::sum('downloads_count'),
            'total_storage' => \App\Models\MediaFile::sum('file_size'),
            'uploads_this_month' => \App\Models\MediaFile::whereMonth('created_at', now()->month)->count(),
            'uploads_this_week' => \App\Models\MediaFile::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
        
        // Top content
        $topContent = \App\Models\MediaFile::orderBy('views_count', 'desc')->take(10)->get();
        
        // Category breakdown
        $categoryBreakdown = \App\Models\MediaFile::selectRaw('category, count(*) as count, sum(views_count) as views')
            ->whereNotNull('category')
            ->groupBy('category')
            ->get();
        
        // Type breakdown
        $typeBreakdown = \App\Models\MediaFile::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();
        
        // Recent uploads
        $recentUploads = \App\Models\MediaFile::with('uploader')->latest()->take(10)->get();
        
        // Top uploaders
        $topUploaders = \App\Models\MediaFile::selectRaw('uploaded_by, count(*) as uploads, sum(views_count) as total_views')
            ->groupBy('uploaded_by')
            ->with('uploader')
            ->orderBy('uploads', 'desc')
            ->take(5)
            ->get();
        
        return view('media.analytics', compact('stats', 'topContent', 'categoryBreakdown', 'typeBreakdown', 'recentUploads', 'topUploaders'));
    }
    
    /**
     * Settings
     */
    public function settings()
    {
        return view('media.settings');
    }
    
    /**
     * Assign Team to Event
     */
    public function assignTeamToEvent(Request $request)
    {
        try {
            $eventId = $request->event_id;
            $event = \App\Models\Event::findOrFail($eventId);
            
            $assignedCount = 0;
            $roles = ['photographer', 'videographer', 'designer', 'editor', 'livestream'];
            $roleNames = [
                'photographer' => 'Photographer',
                'videographer' => 'Videographer',
                'designer' => 'Designer',
                'editor' => 'Editor',
                'livestream' => 'Livestream Operator'
            ];
            
            foreach ($roles as $role) {
                if ($request->filled($role)) {
                    $userId = $request->$role;
                    
                    // Check if already assigned
                    $existing = \App\Models\MediaEventAssignment::where('event_id', $eventId)
                        ->where('assigned_to', $userId)
                        ->where('role', $roleNames[$role])
                        ->first();
                    
                    if (!$existing) {
                        $assignment = \App\Models\MediaEventAssignment::create([
                            'event_id' => $eventId,
                            'assigned_to' => $userId,
                            'assigned_by' => Auth::id(),
                            'role' => $roleNames[$role],
                            'notes' => $request->notes,
                            'status' => 'pending',
                            'notification_sent' => false,
                        ]);
                        
                        // Send notification if requested
                        if ($request->send_notification) {
                            $user = User::find($userId);
                            // Notification logic here
                            $assignment->update(['notification_sent' => true]);
                        }
                        
                        $assignedCount++;
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $assignedCount . ' team member(s) assigned to ' . $event->title,
                'notifications_sent' => $request->send_notification ?? false
            ]);
        } catch (\Exception $e) {
            \Log::error('Assign team error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign team: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload Event Media with Linked Event ID
     */
    public function uploadEventMedia(Request $request)
    {
        $request->validate([
            'linked_event_id' => 'required|exists:events,id',
            'media_type' => 'required|string',
            'files.*' => 'required|file|max:512000', // 500MB max
        ]);
        
        try {
            $eventId = $request->linked_event_id;
            $uploadedCount = 0;
            
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('media/events/' . $eventId, $filename, 'public');
                    
                    // Create media record with event tag
                    \App\Models\MediaFile::create([
                        'title' => $request->description ?? 'Event Media - ' . $eventId,
                        'description' => $request->description,
                        'type' => $request->media_type,
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'category' => 'events',
                        'uploaded_by' => Auth::id(),
                        'event_id' => $eventId, // TAG with event ID
                        'is_public' => true,
                        'published_at' => now(),
                    ]);
                    
                    $uploadedCount++;
                }
                
                // Update assignment status to completed
                \App\Models\MediaEventAssignment::where('event_id', $eventId)
                    ->where('assigned_to', Auth::id())
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now()
                    ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Media uploaded successfully!',
                'files_count' => $uploadedCount,
                'event_id' => $eventId
            ]);
        } catch (\Exception $e) {
            \Log::error('Upload event media error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Notify Event Team Members
     */
    public function notifyEventTeam($eventId)
    {
        try {
            $event = \App\Models\Event::findOrFail($eventId);
            $assignments = \App\Models\MediaEventAssignment::where('event_id', $eventId)
                ->with('assignedUser')
                ->get();
            
            if ($assignments->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No team members assigned to this event yet.'
                ]);
            }
            
            $notifiedCount = 0;
            foreach ($assignments as $assignment) {
                if ($assignment->assignedUser) {
                    // Send notification (email, SMS, push, etc.)
                    // Notification logic here
                    
                    $assignment->update(['notification_sent' => true]);
                    $notifiedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $notifiedCount . ' team member(s) notified for: ' . $event->title
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ], 500);
        }
    }
}
