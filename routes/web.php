<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\QRCheckInController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SmallGroupController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\EmailCampaignController;
use App\Http\Controllers\PrayerRequestController;
use App\Http\Controllers\ChildrenMinistryController;
use App\Http\Controllers\WelfareController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\MediaTeamController;
use App\Http\Controllers\CounsellingController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\MembershipLifecycleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\TitheController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DevotionalController;
use App\Http\Controllers\PaymentController;

// Root route - always redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signupStore'])->name('signup.store');
});

// Email verification routes (outside guest middleware)
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ============================================
// MEMBER PORTAL ROUTES (Church Members Only)
// ============================================
Route::middleware(['auth', 'member.only'])->group(function () {
    // Member Portal
    Route::get('portal', [MemberPortalController::class, 'index'])->name('portal.index');
    Route::get('portal/profile', [MemberPortalController::class, 'profile'])->name('portal.profile');
    Route::put('portal/profile', [MemberPortalController::class, 'updateProfile'])->name('portal.profile.update');
    Route::get('portal/giving', [MemberPortalController::class, 'giving'])->name('portal.giving');
    Route::get('portal/attendance', [MemberPortalController::class, 'attendance'])->name('portal.attendance');
    
    // Member Chat (Real-time messaging with Pusher)
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/messages/{userId}', [ChatController::class, 'fetchMessages'])->name('fetch');
        Route::post('/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread');
        Route::post('/mark-read/{userId}', [ChatController::class, 'markAsRead'])->name('mark-read');
        Route::get('/search', [ChatController::class, 'searchUsers'])->name('search');
    });
    
    // Daily Devotional
    Route::prefix('devotional')->name('devotional.')->group(function () {
        Route::get('/', [DevotionalController::class, 'index'])->name('index');
        Route::get('/archive', [DevotionalController::class, 'archive'])->name('archive');
        Route::get('/{date}', [DevotionalController::class, 'show'])->name('show');
    });
    
    // Payment Integration (Paystack/Mobile Money)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/initialize', [PaymentController::class, 'initialize'])->name('initialize');
        Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
        Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
        Route::get('/verify/{reference}', [PaymentController::class, 'verify'])->name('verify');
    });
    
    // AI Chat (for members)
    Route::get('ai-chat', [AiChatController::class, 'index'])->name('ai.chat');
    Route::post('ai-chat', [AiChatController::class, 'send'])->name('ai.chat.send');
    Route::get('ai-chat/history', [AiChatController::class, 'history'])->name('ai.chat.history');
    Route::delete('ai-chat/clear', [AiChatController::class, 'clear'])->name('ai.chat.clear');
    
    // Events (member view only)
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');
    
    // Small Groups (member view)
    Route::get('small-groups', [SmallGroupController::class, 'index'])->name('small-groups.index');
    Route::post('small-groups/{group}/join', [SmallGroupController::class, 'join'])->name('small-groups.join');
    Route::post('small-groups/{group}/leave', [SmallGroupController::class, 'leave'])->name('small-groups.leave');
    
    // Prayer Requests (member)
    Route::get('prayer-requests', [PrayerRequestController::class, 'index'])->name('prayer-requests.index');
    Route::get('prayer-requests/create', [PrayerRequestController::class, 'create'])->name('prayer-requests.create');
    Route::post('prayer-requests', [PrayerRequestController::class, 'store'])->name('prayer-requests.store');
    Route::get('prayer-requests/{prayerRequest}', [PrayerRequestController::class, 'show'])->name('prayer-requests.show');
    
    // Notifications (member)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    
    // QR Code for member
    Route::get('qr-checkin/member/{member}', [QRCheckInController::class, 'showMemberQR'])->name('qr.member.show');
});

// ============================================
// PASTOR PORTAL ROUTES (Pastors Only)
// ============================================
Route::middleware(['auth', 'pastor.only'])->prefix('pastor')->name('pastor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\PastorPortalController::class, 'dashboard'])->name('dashboard');
    
    // Sermons / Messages
    Route::get('/sermons', [\App\Http\Controllers\PastorPortalController::class, 'sermons'])->name('sermons');
    Route::post('/sermons/store', [\App\Http\Controllers\PastorPortalController::class, 'storeSermon'])->name('sermons.store');
    Route::post('/sermons/{id}/update', [\App\Http\Controllers\PastorPortalController::class, 'updateSermon'])->name('sermons.update');
    Route::delete('/sermons/{id}', [\App\Http\Controllers\PastorPortalController::class, 'deleteSermon'])->name('sermons.delete');
    
    // Prayer Requests
    Route::get('/prayer-requests', [\App\Http\Controllers\PastorPortalController::class, 'prayerRequests'])->name('prayer-requests');
    
    // Members View (Pastor can view members)
    Route::get('/members', [\App\Http\Controllers\PastorPortalController::class, 'members'])->name('members');
    
    // Programs & Events
    Route::get('/events', [\App\Http\Controllers\PastorPortalController::class, 'events'])->name('events');
    
    // Counselling Sessions
    Route::get('/counselling', [\App\Http\Controllers\PastorPortalController::class, 'counselling'])->name('counselling');
    
    // Finance Overview
    Route::get('/finance', [\App\Http\Controllers\PastorPortalController::class, 'finance'])->name('finance');
    
    // Announcements / Broadcast
    Route::get('/broadcast', [\App\Http\Controllers\PastorPortalController::class, 'broadcast'])->name('broadcast');
    
    // AI Ministry Assistant
    Route::get('/ai-assistant', [\App\Http\Controllers\PastorPortalController::class, 'aiAssistant'])->name('ai-assistant');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\PastorPortalController::class, 'settings'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\PastorPortalController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/photo', [\App\Http\Controllers\PastorPortalController::class, 'uploadPhoto'])->name('settings.photo');
    Route::post('/settings/password', [\App\Http\Controllers\PastorPortalController::class, 'changePassword'])->name('settings.password');
    
    // Broadcast
    Route::post('/broadcast/send', [\App\Http\Controllers\PastorPortalController::class, 'sendBroadcast'])->name('broadcast.send');
    
    // Counselling
    Route::post('/counselling/schedule', [\App\Http\Controllers\PastorPortalController::class, 'scheduleCounselling'])->name('counselling.schedule');
});

// ============================================
// ORGANIZATION PORTAL ROUTES (Organization Only)
// ============================================
Route::middleware(['auth', 'organization.only'])->prefix('organization')->name('organization.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\OrganizationPortalController::class, 'dashboard'])->name('dashboard');
    
    // Branch Management
    Route::get('/branches', [\App\Http\Controllers\OrganizationPortalController::class, 'branches'])->name('branches');
    
    // Staff & Role Management
    Route::get('/staff', [\App\Http\Controllers\OrganizationPortalController::class, 'staff'])->name('staff');
    
    // Finance Overview
    Route::get('/finance', [\App\Http\Controllers\OrganizationPortalController::class, 'finance'])->name('finance');
    
    // Reports & Analytics
    Route::get('/reports', [\App\Http\Controllers\OrganizationPortalController::class, 'reports'])->name('reports');
    
    // Events & Campaigns
    Route::get('/events', [\App\Http\Controllers\OrganizationPortalController::class, 'events'])->name('events');
    
    // AI Insights
    Route::get('/ai-insights', [\App\Http\Controllers\OrganizationPortalController::class, 'aiInsights'])->name('ai-insights');
    
    // Communication Center
    Route::get('/communication', [\App\Http\Controllers\OrganizationPortalController::class, 'communication'])->name('communication');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\OrganizationPortalController::class, 'settings'])->name('settings');
});

// ============================================
// VOLUNTEER PORTAL ROUTES (Volunteers Only)
// ============================================
Route::middleware(['auth', 'volunteer.only'])->prefix('volunteer')->name('volunteer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\VolunteerPortalController::class, 'dashboard'])->name('dashboard');
    
    // Assigned Events
    Route::get('/events', [\App\Http\Controllers\VolunteerPortalController::class, 'events'])->name('events');
    
    // Task Manager
    Route::get('/tasks', [\App\Http\Controllers\VolunteerPortalController::class, 'tasks'])->name('tasks');
    
    // My Team / Group
    Route::get('/team', [\App\Http\Controllers\VolunteerPortalController::class, 'team'])->name('team');
    
    // Prayer Requests
    Route::get('/prayer', [\App\Http\Controllers\VolunteerPortalController::class, 'prayer'])->name('prayer');
    
    // AI Helper
    Route::get('/ai-helper', [\App\Http\Controllers\VolunteerPortalController::class, 'aiHelper'])->name('ai-helper');
    
    // Communication
    Route::get('/communication', [\App\Http\Controllers\VolunteerPortalController::class, 'communication'])->name('communication');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\VolunteerPortalController::class, 'settings'])->name('settings');
});

// ============================================
// MINISTRY LEADER PORTAL ROUTES (Ministry Leaders Only)
// ============================================
Route::middleware(['auth', 'ministry.leader.only'])->prefix('ministry-leader')->name('ministry-leader.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'dashboard'])->name('dashboard');
    
    // Members Management
    Route::get('/members', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'members'])->name('members');
    
    // Small Groups Management
    Route::get('/groups', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'groups'])->name('groups');
    
    // Events Management
    Route::get('/events', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'events'])->name('events');
    
    // Prayer Requests
    Route::get('/prayer-requests', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'prayerRequests'])->name('prayer-requests');
    
    // Ministry Reports
    Route::get('/reports', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'reports'])->name('reports');
    
    // Communication Center
    Route::get('/communication', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'communication'])->name('communication');
    
    // AI Ministry Assistant
    Route::get('/ai-assistant', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'aiAssistant'])->name('ai-assistant');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/password', [\App\Http\Controllers\MinistryLeaderPortalController::class, 'updatePassword'])->name('settings.password');
});

// ============================================
// STAFF ROUTES (Admin, Pastor, Ministry Leader, Organization, Volunteer)
// ============================================
Route::middleware(['auth', 'staff.only'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Members
    Route::resource('members', MemberController::class);
    Route::post('members/{member}/emergency-contacts', [MemberController::class, 'addEmergencyContact'])->name('members.emergency-contacts.add');
    Route::put('members/{member}/emergency-contacts/{contactId}', [MemberController::class, 'updateEmergencyContact'])->name('members.emergency-contacts.update');
    Route::delete('members/{member}/emergency-contacts/{contactId}', [MemberController::class, 'deleteEmergencyContact'])->name('members.emergency-contacts.delete');
    Route::post('members/{member}/documents', [MemberController::class, 'uploadDocument'])->name('members.documents.upload');
    Route::delete('members/{member}/documents/{documentId}', [MemberController::class, 'deleteDocument'])->name('members.documents.delete');
    Route::get('members/{member}/documents/{documentId}/download', [MemberController::class, 'downloadDocument'])->name('members.documents.download');
    
    // Visitors
    Route::get('visitors/dashboard', [VisitorController::class, 'dashboard'])->name('visitors.dashboard');
    Route::resource('visitors', VisitorController::class);
    Route::post('visitors/{visitor}/convert', [VisitorController::class, 'convertToMember'])->name('visitors.convert');
    Route::post('visitors/{visitor}/followup', [VisitorController::class, 'addFollowup'])->name('visitors.followup');
    
    // Attendance
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('attendance/check-in-qr', [AttendanceController::class, 'checkInByQr'])->name('attendance.checkin.qr');
    Route::get('attendance/qr-code/{member}', [AttendanceController::class, 'generateQrCode'])->name('attendance.qrcode');
    Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('attendance/member/{member}', [AttendanceController::class, 'memberHistory'])->name('attendance.member-history');
    Route::get('attendance/mobile-checkin', [AttendanceController::class, 'mobileCheckin'])->name('attendance.mobile-checkin');
    Route::get('attendance/qr-scanner', [AttendanceController::class, 'qrScanner'])->name('attendance.qr-scanner');
    Route::get('attendance/absences', [AttendanceController::class, 'checkAbsences'])->name('attendance.absences');
    Route::get('attendance/analytics', [AttendanceController::class, 'analytics'])->name('attendance.analytics');
    
    // QR Check-In System
    Route::get('qr-checkin', [QRCheckInController::class, 'showScanner'])->name('qr.checkin.scanner');
    Route::post('qr-checkin/process', [QRCheckInController::class, 'processQRCheckIn'])->name('qr.checkin.process');
    Route::get('qr-checkin/member/{member}', [QRCheckInController::class, 'showMemberQR'])->name('qr.member.show');
    Route::get('qr-checkin/member/{member}/generate', [QRCheckInController::class, 'generateMemberQR'])->name('qr.member.generate');
    Route::get('qr-checkin/bulk-generate', [QRCheckInController::class, 'showBulkGenerate'])->name('qr.bulk.generate.page');
    Route::post('qr-checkin/bulk-generate', [QRCheckInController::class, 'bulkGenerateQR'])->name('qr.bulk.generate');
    
    // Debug route
    Route::get('/debug-photos', function () {
        $members = \App\Models\Member::all();
        
        echo "<h1>Photo Debug Information</h1>";
        echo "<style>body { font-family: Arial; padding: 20px; } .member { border: 2px solid #ccc; padding: 15px; margin: 10px 0; } img { width: 100px; height: 100px; border-radius: 50%; border: 3px solid green; } .error { color: red; } .success { color: green; }</style>";
        
        echo "<h2>Storage Link:</h2>";
        echo "<p>public/storage exists: " . (file_exists(public_path('storage')) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</p>";
        echo "<p>Is link: " . (is_link(public_path('storage')) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</p>";
        
        echo "<h2>Members (" . $members->count() . "):</h2>";
        foreach ($members as $member) {
            echo "<div class='member'>";
            echo "<h3>{$member->full_name}</h3>";
            echo "<p>profile_photo: " . ($member->profile_photo ?? 'NULL') . "</p>";
            echo "<p>photo: " . ($member->photo ?? 'NULL') . "</p>";
            if ($member->profile_photo || $member->photo) {
                $photoPath = $member->profile_photo ?? $member->photo;
                $fullPath = storage_path('app/public/' . $photoPath);
                $webPath = asset('storage/' . $photoPath);
                echo "<p>File exists: " . (file_exists($fullPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</p>";
                echo "<p>Web path: $webPath</p>";
                echo "<img src='$webPath'>";
            }
            echo "</div>";
        }
    });
    
    // Analytics Dashboard
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/data', [AnalyticsController::class, 'getData'])->name('analytics.data');
    
    // Events Management (staff can create/edit)
    Route::post('events', [EventController::class, 'store'])->name('events.store');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create');
    Route::get('events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::get('events/{event}/attendees', [EventController::class, 'attendees'])->name('events.attendees');
    
    // Small Groups Management (staff can manage)
    Route::post('small-groups', [SmallGroupController::class, 'store'])->name('small-groups.store');
    Route::put('small-groups/{group}', [SmallGroupController::class, 'update'])->name('small-groups.update');
    Route::delete('small-groups/{group}', [SmallGroupController::class, 'destroy'])->name('small-groups.destroy');
    Route::get('small-groups/create', [SmallGroupController::class, 'create'])->name('small-groups.create');
    Route::get('small-groups/{group}/edit', [SmallGroupController::class, 'edit'])->name('small-groups.edit');
    Route::get('small-groups/{group}/attendance', [SmallGroupController::class, 'attendance'])->name('small-groups.attendance');
    
    // Payment Integration (Paystack/Mobile Money)
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::post('/initialize', [PaymentController::class, 'initialize'])->name('initialize');
        Route::get('/callback', [PaymentController::class, 'callback'])->name('callback');
        Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook');
        Route::get('/verify/{reference}', [PaymentController::class, 'verify'])->name('verify');
    });
    
    // Volunteers
    Route::get('volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::get('volunteers/create', [VolunteerController::class, 'create'])->name('volunteers.create');
    Route::post('volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');
    Route::get('volunteers/assignments', [VolunteerController::class, 'assignments'])->name('volunteers.assignments');
    Route::post('volunteers/schedule', [VolunteerController::class, 'schedule'])->name('volunteers.schedule');
    Route::put('volunteers/assignments/{assignment}/status', [VolunteerController::class, 'updateStatus'])->name('volunteers.assignments.status');
    
    // Families
    Route::resource('families', FamilyController::class);
    Route::post('families/{family}/add-member', [FamilyController::class, 'addMember'])->name('families.add-member');
    Route::post('families/{family}/remove-member', [FamilyController::class, 'removeMember'])->name('families.remove-member');
    
    // Email Campaigns
    Route::resource('email-campaigns', EmailCampaignController::class);
    Route::post('email-campaigns/{emailCampaign}/send', [EmailCampaignController::class, 'send'])->name('email-campaigns.send');
    
    // Prayer Requests Management (Staff can manage responses)
    Route::post('prayer-requests/{prayerRequest}/response', [PrayerRequestController::class, 'addResponse'])->name('prayer-requests.response');
    Route::put('prayer-requests/{prayerRequest}', [PrayerRequestController::class, 'update'])->name('prayer-requests.update');
    Route::delete('prayer-requests/{prayerRequest}', [PrayerRequestController::class, 'destroy'])->name('prayer-requests.destroy');
    
    // Children Ministry
    Route::resource('children-ministry', ChildrenMinistryController::class);
    
    // Welfare
    Route::resource('welfare', WelfareController::class);
    
    // Partnerships
    Route::resource('partnerships', PartnershipController::class);
    
    // Media Teams
    Route::resource('media-teams', MediaTeamController::class);
    
    // Counselling
    Route::resource('counselling', CounsellingController::class);
    
    // Birthdays
    Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    Route::post('birthdays/send-wish', [BirthdayController::class, 'sendWish'])->name('birthdays.send-wish');
    
    // Finance Dashboard
    Route::get('finance/dashboard', [ExpenseController::class, 'dashboard'])->name('finance.dashboard');
    Route::post('finance/store', [ExpenseController::class, 'storeFinance'])->name('finance.store');
    
    // Finance - Donations
    Route::resource('donations', DonationController::class);
    Route::get('donations/{donation}/receipt', [DonationController::class, 'receipt'])->name('donations.receipt');
    
    // Finance - Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    
    // SMS
    Route::get('sms', [SmsController::class, 'index'])->name('sms.index');
    Route::get('sms/create', [SmsController::class, 'create'])->name('sms.create');
    Route::post('sms', [SmsController::class, 'store'])->name('sms.store');
    Route::get('sms/{campaign}', [SmsController::class, 'show'])->name('sms.show');
    Route::post('sms/{campaign}/send', [SmsController::class, 'send'])->name('sms.send');
    Route::get('sms-templates', [SmsController::class, 'templates'])->name('sms.templates');
    
    // Equipment
    Route::resource('equipment', EquipmentController::class);
    Route::get('equipment-analytics', [EquipmentController::class, 'analytics'])->name('equipment.analytics');
    Route::get('equipment-maintenance', [EquipmentController::class, 'maintenance'])->name('equipment.maintenance');
    Route::get('equipment-export', [EquipmentController::class, 'export'])->name('equipment.export');
    Route::get('equipment/{equipment}/qr-code', [EquipmentController::class, 'generateQr'])->name('equipment.qr');
    Route::get('equipment-qr-bulk', [EquipmentController::class, 'bulkGenerateQr'])->name('equipment.qr.bulk');
    Route::post('equipment/{equipment}/assign', [EquipmentController::class, 'assign'])->name('equipment.assign');
    Route::post('equipment/{equipment}/maintenance', [EquipmentController::class, 'addMaintenance'])->name('equipment.maintenance.add');
    Route::post('equipment-assignments/{assignment}/return', [EquipmentController::class, 'returnEquipment'])->name('equipment.return');
    
    // Media Management
    Route::resource('media', MediaController::class);
    Route::get('media-team', [MediaController::class, 'team'])->name('media.team');
    Route::post('media-team', [MediaController::class, 'storeTeam'])->name('media.team.store');
    Route::delete('media-team/{id}', [MediaController::class, 'destroyTeam'])->name('media.team.destroy');
    
    // Welfare
    Route::resource('welfare', WelfareController::class);
    Route::get('welfare-dashboard', [WelfareController::class, 'dashboard'])->name('welfare.dashboard');
    Route::get('welfare-requests', [WelfareController::class, 'requests'])->name('welfare.requests');
    Route::post('welfare-requests/{id}/approve', [WelfareController::class, 'approveRequest'])->name('welfare.requests.approve');
    Route::post('welfare-requests/{id}/decline', [WelfareController::class, 'declineRequest'])->name('welfare.requests.decline');
    
    // Partnerships
    Route::resource('partnerships', PartnershipController::class);
    Route::get('partnerships-report', [PartnershipController::class, 'report'])->name('partnerships.report');
    Route::post('partnerships/{partnership}/payment', [PartnershipController::class, 'addPayment'])->name('partnerships.payment');
    
    // Counselling
    Route::resource('counselling', CounsellingController::class);
    Route::get('counselling-calendar', [CounsellingController::class, 'calendar'])->name('counselling.calendar');
    Route::post('counselling/{counselling}/followup', [CounsellingController::class, 'addFollowup'])->name('counselling.followup');
    
    // Counselling - AI Features
    Route::post('counselling/{session}/summarize', [CounsellingController::class, 'summarizeNotes'])->name('counselling.summarize');
    Route::post('counselling/{session}/create-followup', [CounsellingController::class, 'createFollowup'])->name('counselling.create-followup');
    Route::get('counselling/{session}/followups', [CounsellingController::class, 'followups'])->name('counselling.followups');
    Route::get('counselling/{session}/export-pdf', [CounsellingController::class, 'exportPDF'])->name('counselling.export-pdf');
    Route::get('counsellor-availability', [CounsellingController::class, 'counsellorAvailability'])->name('counsellor.availability');
    
    // Birthdays
    Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    Route::get('birthdays-calendar', [BirthdayController::class, 'calendar'])->name('birthdays.calendar');
    Route::get('birthdays-today', [BirthdayController::class, 'today'])->name('birthdays.today');
    Route::get('birthdays-month', [BirthdayController::class, 'thisMonth'])->name('birthdays.month');
    
    // Children Ministry
    Route::resource('children', ChildrenController::class);
    Route::get('children-attendance', [ChildrenController::class, 'attendance'])->name('children.attendance');
    Route::post('children-attendance', [ChildrenController::class, 'markAttendance'])->name('children.attendance.mark');
    Route::get('children-report', [ChildrenController::class, 'report'])->name('children.report');
    
    
    // Follow-ups
    Route::resource('followups', FollowupController::class);
    Route::post('followups/{followup}/complete', [FollowupController::class, 'complete'])->name('followups.complete');
    Route::post('followups/{followup}/activity', [FollowupController::class, 'addActivity'])->name('followups.activity');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/membership', [ReportController::class, 'membership'])->name('reports.membership');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    
    // Comprehensive Financial Reports
    Route::get('reports/financial/monthly', [ReportController::class, 'monthlyStatement'])->name('reports.financial.monthly');
    Route::get('reports/financial/annual', [ReportController::class, 'annualReport'])->name('reports.financial.annual');
    Route::get('reports/financial/custom', [ReportController::class, 'customPeriodReport'])->name('reports.financial.custom');
    Route::get('reports/financial/departments', [ReportController::class, 'departmentAnalysis'])->name('reports.financial.departments');
    Route::get('reports/financial/trends', [ReportController::class, 'trendAnalysis'])->name('reports.financial.trends');
    Route::get('reports/financial/budget-vs-actual', [ReportController::class, 'budgetVsActual'])->name('reports.financial.budget-vs-actual');
    Route::get('reports/export/monthly', [ReportController::class, 'exportMonthly'])->name('reports.export.monthly');
    Route::get('reports/export/annual', [ReportController::class, 'exportAnnual'])->name('reports.export.annual');
    
    // Membership Lifecycle
    Route::get('membership/onboarding', [MembershipLifecycleController::class, 'onboarding'])->name('membership.onboarding');
    Route::post('membership/onboarding/{member}/update-stage', [MembershipLifecycleController::class, 'updateStage'])->name('membership.onboarding.update-stage');
    Route::get('membership/classes', [MembershipLifecycleController::class, 'classes'])->name('membership.classes');
    Route::get('membership/classes/create', [MembershipLifecycleController::class, 'createClass'])->name('membership.classes.create');
    Route::post('membership/classes', [MembershipLifecycleController::class, 'storeClass'])->name('membership.classes.store');
    Route::get('membership/classes/{class}', [MembershipLifecycleController::class, 'showClass'])->name('membership.classes.show');
    Route::post('membership/classes/{class}/enroll', [MembershipLifecycleController::class, 'enrollMember'])->name('membership.classes.enroll');
    Route::put('membership/classes/{class}/member/{member}', [MembershipLifecycleController::class, 'updateEnrollment'])->name('membership.classes.update-enrollment');
    Route::get('membership/transfers', [MembershipLifecycleController::class, 'transfers'])->name('membership.transfers');
    Route::get('membership/transfers/create', [MembershipLifecycleController::class, 'createTransfer'])->name('membership.transfers.create');
    Route::post('membership/transfers', [MembershipLifecycleController::class, 'storeTransfer'])->name('membership.transfers.store');
    Route::post('membership/transfers/{transfer}/approve', [MembershipLifecycleController::class, 'approveTransfer'])->name('membership.transfers.approve');
    Route::post('membership/transfers/{transfer}/complete', [MembershipLifecycleController::class, 'completeTransfer'])->name('membership.transfers.complete');
    Route::get('membership/status-history/{member}', [MembershipLifecycleController::class, 'statusHistory'])->name('membership.status-history');
    Route::post('membership/status-history/{member}/change', [MembershipLifecycleController::class, 'changeStatus'])->name('membership.change-status');
    
    // Pledge System
    Route::get('pledge-campaigns', [PledgeController::class, 'campaigns'])->name('pledge-campaigns.index');
    Route::get('pledge-campaigns/create', [PledgeController::class, 'createCampaign'])->name('pledge-campaigns.create');
    Route::post('pledge-campaigns', [PledgeController::class, 'storeCampaign'])->name('pledge-campaigns.store');
    Route::get('pledge-campaigns/{campaign}', [PledgeController::class, 'showCampaign'])->name('pledge-campaigns.show');
    Route::get('pledge-campaigns/{campaign}/report', [PledgeController::class, 'campaignReport'])->name('pledge-campaigns.report');
    Route::resource('pledges', PledgeController::class);
    Route::post('pledges/{pledge}/payment', [PledgeController::class, 'recordPayment'])->name('pledges.payment');
    
    // Notifications Management (Staff only has destroy)
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('api/notifications/unread', [NotificationController::class, 'getUnread'])->name('api.notifications.unread');
    
    // Offerings & Tithes
    Route::resource('offerings', OfferingController::class);
    Route::get('offerings/export/excel', [OfferingController::class, 'exportExcel'])->name('offerings.export.excel');
    Route::get('offerings/export/pdf', [OfferingController::class, 'exportPdf'])->name('offerings.export.pdf');
    Route::get('offerings/analytics', [OfferingController::class, 'analytics'])->name('offerings.analytics');
    Route::get('offerings/ai-summary', [OfferingController::class, 'aiSummary'])->name('offerings.ai-summary');
    Route::get('offerings/{offering}/receipt', [OfferingController::class, 'receipt'])->name('offerings.receipt');
    
    // Tithes - special routes before resource routes
    Route::get('tithes/export', [TitheController::class, 'exportExcel'])->name('tithes.export');
    Route::get('tithes/ai-insights', [TitheController::class, 'aiInsights'])->name('tithes.ai-insights');
    Route::get('tithes/member-history/{id}', [TitheController::class, 'memberHistory'])->name('tithes.member-history');
    Route::get('tithes/receipt/{id}', [TitheController::class, 'generateReceipt'])->name('tithes.receipt');
    Route::resource('tithes', TitheController::class);
    
    // Settings
    Route::get('/settings/dashboard', [SettingsController::class, 'dashboard'])->name('settings.dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // AI Chat Assistant (Staff Admin for AI conversations)
    Route::prefix('ai-chat-admin')->name('ai-chat-admin.')->group(function () {
        Route::get('/', [AIChatController::class, 'index'])->name('index');
        Route::get('/divine', [AIChatController::class, 'divineHub'])->name('divine');
        Route::get('/new', [AIChatController::class, 'newConversation'])->name('new');
        Route::get('/{id}', [AIChatController::class, 'show'])->name('show');
        Route::get('/{id}/export', [AIChatController::class, 'exportConversation'])->name('export');
        Route::post('/{id}/send', [AIChatController::class, 'sendMessage'])->name('send');
        Route::post('/{id}/clear', [AIChatController::class, 'clearConversation'])->name('clear');
        Route::delete('/{id}', [AIChatController::class, 'deleteConversation'])->name('delete');
    });
});
