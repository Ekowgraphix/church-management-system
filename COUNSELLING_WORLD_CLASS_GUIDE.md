# 🧠 World-Class Counselling Management System

## 📋 Overview
A comprehensive, secure, and AI-powered counselling management system for churches. Handles session booking, confidential record-keeping, counsellor assignment, follow-up tracking, and AI-driven insights.

---

## ✨ Core Features

### 1. **Session Management**
- Schedule and track counselling sessions
- Multiple session types (In-Person, Phone, Video Call, Group)
- Session duration tracking
- Real-time status updates (Pending, Completed, Follow-up, Cancelled)

### 2. **Counsellor Assignment**
- Assign specialized counsellors per category
- Track counsellor availability and workload
- Rating and performance tracking
- Specialization matching (Marriage, Family, Personal, Youth, Career, Grief, Spiritual)

### 3. **Secure Notes System**
- End-to-end encrypted session notes
- Access level control (Normal, Private, Strict Confidential)
- Private access roles - only authorized counsellors can view
- Secure storage with timestamp tracking

### 4. **Follow-up Tracker**
- Automated follow-up reminders
- Priority-based tracking (Low, Medium, High, Urgent)
- Email and SMS notifications
- Overdue follow-up alerts

### 5. **AI-Powered Features** 🤖
- **AI Note Summarizer**: Automatically summarize lengthy session notes
- **Key Points Extraction**: Identify critical discussion points
- **Action Items Generator**: Extract actionable follow-up tasks
- **Insights Dashboard**: Track patterns and trends

### 6. **Calendar View**
- Visual monthly/weekly calendar
- Color-coded by category
- Drag-and-drop rescheduling
- Counsellor availability view

### 7. **Confidentiality & Security** 🔒
- Three-tier access levels
- Role-based permissions
- Encrypted PDF exports
- Audit trail for sensitive data access

---

## 📊 Database Structure

### Tables Created

#### 1. `counselling_sessions`
```sql
- id (Primary Key)
- member_id (Foreign Key → members)
- counsellor_id (Foreign Key → counsellors)
- category_id (Foreign Key → counselling_categories)
- member_name
- member_contact
- counsellor_name
- topic
- session_date
- session_time
- session_type (In-Person/Phone/Video Call/Group)
- duration (minutes)
- notes (encrypted)
- outcome
- rating (1-5)
- follow_up_date
- requires_followup
- status (pending/completed/follow_up/cancelled)
- is_confidential
- access_level
- timestamps
```

#### 2. `counsellors`
```sql
- id (Primary Key)
- name
- email
- phone
- specialization (Marriage/Family/Personal/Youth/Career/Grief/Spiritual/General)
- status (Active/Inactive/On Leave)
- bio
- photo
- total_sessions
- rating (1-5)
- timestamps
```

#### 3. `counselling_categories`
```sql
- id (Primary Key)
- name
- icon
- color
- description
- requires_specialist (boolean)
- session_count
- timestamps
```

#### 4. `counselling_followups`
```sql
- id (Primary Key)
- session_id (Foreign Key → counselling_sessions)
- counsellor_id (Foreign Key → counsellors)
- follow_up_date
- priority (Low/Medium/High/Urgent)
- status (Pending/Completed/Missed/Rescheduled)
- notes
- reminder_sent (boolean)
- completed_at
- timestamps
```

#### 5. `counselling_notes`
```sql
- id (Primary Key)
- session_id (Foreign Key → counselling_sessions)
- original_notes (encrypted)
- ai_summary
- key_points (JSON)
- action_items (JSON)
- is_encrypted (boolean)
- summarized_at
- timestamps
```

---

## 🚀 Installation & Setup

### Step 1: Run Migrations
```bash
php artisan migrate
```

This will create all necessary tables:
- `counsellors`
- `counselling_categories`
- `counselling_followups`
- `counselling_notes`
- Enhanced `counselling_sessions` with new columns

### Step 2: Seed Initial Data (Optional)
```bash
php artisan db:seed --class=CounsellingSeeder
```

This creates:
- 5 sample counsellors
- 6 standard categories (Marriage, Family, Personal, Career, Grief, Spiritual)
- Sample sessions for testing

### Step 3: Add Routes
Add to `routes/web.php`:
```php
Route::middleware(['auth'])->group(function () {
    // Counselling routes
    Route::resource('counselling', CounsellingController::class);
    Route::get('counselling-calendar', [CounsellingController::class, 'calendar'])->name('counselling.calendar');
    
    // AI Features
    Route::post('counselling/{session}/summarize', [CounsellingController::class, 'summarizeNotes']);
    Route::post('counselling/{session}/followup', [CounsellingController::class, 'createFollowup']);
    Route::get('counselling/{session}/followups', [CounsellingController::class, 'followups']);
    Route::get('counselling/{session}/export-pdf', [CounsellingController::class, 'exportPDF']);
    
    // Counsellor management
    Route::get('counsellor-availability', [CounsellingController::class, 'counsellorAvailability']);
});
```

### Step 4: Include JavaScript
Add to your main view (`index-upgraded.blade.php`):
```html
<script src="{{ asset('js/counselling-ai.js') }}"></script>
```

---

## 🎨 UI Components

### Statistics Dashboard
Displays key metrics:
- Total sessions (all time)
- This month's sessions
- Upcoming sessions
- Active counsellors
- Pending follow-ups
- Success rate %

### Session Categories
Six pre-defined categories with icons:
- 💍 Marriage (Pink)
- 🏠 Family (Blue)
- 👤 Personal (Purple)
- 💼 Career (Orange)
- 💔 Grief (Red)
- 🙏 Spiritual (Green)

### Session Cards
Each session displays:
- Counsellor name and photo
- Counselee information (masked if confidential)
- Session type and duration
- Date and time
- Status badge
- Confidentiality level indicator
- Quick action buttons

### Sidebar Widgets
- **Today's Schedule**: Shows upcoming sessions
- **Counsellor Status**: Live availability indicators
- **Resources**: Quick access to guidelines and forms
- **Quick Stats**: Average duration, follow-up rate, resolution rate

---

## 🤖 AI Features Guide

### 1. AI Note Summarization
**How to use:**
```javascript
// From session details page
CounsellingAI.summarizeNotes(sessionId, notesText);
```

**What it does:**
- Analyzes session notes
- Generates concise summary
- Extracts key discussion points
- Identifies action items
- Presents in clean modal interface

**Example Output:**
```
SUMMARY:
Session focused on marital communication challenges. Discussed active listening techniques and conflict resolution strategies.

KEY POINTS:
• Communication breakdown identified as primary issue
• Both parties willing to work on relationship
• Past trauma affecting current dynamics
• Support system identified and engaged

ACTION ITEMS:
☐ Schedule follow-up in 2 weeks
☐ Implement daily check-in routine
☐ Connect with marriage support group
☐ Complete communication exercises
```

### 2. Follow-up Management
**Creating follow-ups:**
```javascript
CounsellingAI.showFollowupForm(sessionId);
```

**Features:**
- Date picker for scheduling
- Priority levels (Low/Medium/High/Urgent)
- Optional notes
- Automatic reminder scheduling
- Email & SMS notifications

### 3. Encrypted PDF Export
**Exporting sessions:**
```javascript
CounsellingAI.exportPDF(sessionId);
```

**Export includes:**
- Session details
- Counsellor and counselee info
- Session notes (encrypted)
- Follow-up schedule
- 256-bit encryption
- Password protection

---

## 🔒 Security & Privacy

### Access Levels

#### Level 1: Normal
- Visible to all authorized counsellors
- Basic session information
- No special restrictions

#### Level 2: Private
- Visible only to assigned counsellor
- Supervisor access only
- Masked member details in lists

#### Level 3: Strict Confidential
- Assigned counsellor only
- No supervisor access
- Full encryption
- Audit logging
- Requires special permission to view

### Security Features
1. **Encryption at Rest**: All sensitive notes encrypted in database
2. **Access Control**: Role-based permissions (Admin, Counsellor, Supervisor)
3. **Audit Trail**: Log all access to confidential sessions
4. **Session Timeout**: Auto-logout after inactivity
5. **Two-Factor Authentication**: Optional for counsellors
6. **Password-Protected Exports**: All PDFs encrypted

---

## 📅 Calendar Features

### View Modes
- **Month View**: Overview of all sessions
- **Week View**: Detailed weekly schedule
- **Day View**: Hour-by-hour breakdown
- **Counsellor View**: Filter by specific counsellor

### Calendar Functions
```javascript
// Color-coded by category
- Marriage: Pink
- Family: Blue
- Personal: Purple
- Career: Orange
- Grief: Red
- Spiritual: Green
```

### Interactive Features
- Click to view session details
- Drag to reschedule (if permitted)
- Double-click to create new session
- Hover for quick preview
- Filter by counsellor, category, or status

---

## 📱 Notifications & Reminders

### Automated Notifications
1. **Session Reminders**
   - 24 hours before session
   - 1 hour before session
   - Via SMS and Email

2. **Follow-up Reminders**
   - When follow-up is due
   - Weekly digest of pending follow-ups
   - Overdue follow-up alerts

3. **Counsellor Notifications**
   - New session assignment
   - Session cancellation
   - Follow-up due alerts
   - Feedback requests

### Notification Settings
Configure in `config/counselling.php`:
```php
'notifications' => [
    'session_reminder_hours' => 24,
    'followup_reminder_days' => 2,
    'send_sms' => true,
    'send_email' => true,
],
```

---

## 🎯 Usage Examples

### Scheduling a New Session
```php
// From create form
POST /counselling
{
    "member_id": 1,
    "counsellor_id": 2,
    "category_id": 1,
    "topic": "Marriage Communication",
    "session_date": "2025-10-20",
    "session_time": "14:00",
    "session_type": "In-Person",
    "duration": 60,
    "is_confidential": true,
    "access_level": "private"
}
```

### Completing a Session with Notes
```php
PATCH /counselling/{id}
{
    "status": "completed",
    "notes": "Session went well. Key issues discussed...",
    "outcome": "Positive progress made",
    "rating": 5,
    "requires_followup": true,
    "follow_up_date": "2025-11-03"
}
```

### Using AI Summary
```javascript
// After session completion
const notes = document.getElementById('session-notes').value;
await CounsellingAI.summarizeNotes(sessionId, notes);
```

---

## 📈 Reports & Analytics

### Available Reports
1. **Session Statistics**
   - Total sessions by period
   - Average duration
   - Completion rate
   - Rating distribution

2. **Counsellor Performance**
   - Sessions per counsellor
   - Average ratings
   - Follow-up completion rate
   - Specialization effectiveness

3. **Category Analysis**
   - Most common issues
   - Success rates by category
   - Average sessions needed
   - Trend analysis

4. **Follow-up Tracking**
   - Pending follow-ups
   - Overdue count
   - Completion rate
   - Average response time

### Generating Reports
```php
// In controller
public function reports(Request $request)
{
    $startDate = $request->start_date;
    $endDate = $request->end_date;
    
    $report = [
        'total_sessions' => CounsellingSession::whereBetween('session_date', [$startDate, $endDate])->count(),
        'by_category' => CounsellingSession::with('category')
            ->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get(),
        'completion_rate' => CounsellingSession::where('status', 'completed')->count() / CounsellingSession::count() * 100,
    ];
    
    return view('counselling.reports', compact('report'));
}
```

---

## 🛠️ Customization

### Adding New Categories
```php
CounsellingCategory::create([
    'name' => 'Financial',
    'icon' => 'dollar-sign',
    'color' => 'yellow',
    'description' => 'Financial counselling and budgeting',
    'requires_specialist' => false,
]);
```

### Custom Session Types
Edit migration to add new types:
```php
$table->enum('session_type', [
    'In-Person', 
    'Phone', 
    'Video Call', 
    'Group',
    'Emergency',  // New
    'Walk-in'     // New
])->default('In-Person');
```

### Email Templates
Customize in `resources/views/emails/counselling/`:
- `session-reminder.blade.php`
- `followup-reminder.blade.php`
- `session-confirmation.blade.php`

---

## 🔧 Troubleshooting

### Common Issues

**Issue 1: AI Summary not working**
- Check CSRF token is present
- Verify route is defined
- Check browser console for errors
- Ensure `counselling-ai.js` is loaded

**Issue 2: Follow-ups not sending**
- Verify Laravel Scheduler is running: `php artisan schedule:work`
- Check mail configuration in `.env`
- Review `FollowupNotificationJob` logs

**Issue 3: Calendar not displaying**
- Check FullCalendar library is included
- Verify sessions have valid dates
- Check console for JavaScript errors

**Issue 4: Access denied to confidential sessions**
- Verify user role permissions
- Check `access_level` in session
- Review middleware in `CounsellingController`

---

## 🎓 Best Practices

### For Counsellors
1. ✅ Always set appropriate confidentiality level
2. ✅ Use AI summarization for detailed notes
3. ✅ Schedule follow-ups immediately after sessions
4. ✅ Update session status promptly
5. ✅ Rate sessions for quality tracking

### For Administrators
1. ✅ Regular database backups
2. ✅ Audit confidential access logs weekly
3. ✅ Monitor overdue follow-ups
4. ✅ Review counsellor workload distribution
5. ✅ Export encrypted backups monthly

### For Developers
1. ✅ Never log sensitive session data
2. ✅ Always use prepared statements
3. ✅ Encrypt data at rest
4. ✅ Implement rate limiting on AI endpoints
5. ✅ Regular security audits

---

## 🚀 Future Enhancements

### Planned Features
- [ ] Video call integration (Zoom/Teams)
- [ ] Real-time chat with counsellors
- [ ] Mobile app for counselees
- [ ] Advanced AI sentiment analysis
- [ ] Automated crisis detection
- [ ] Multi-language support
- [ ] Voice-to-text note taking
- [ ] Integration with church management system
- [ ] Anonymous counselling option
- [ ] Group therapy session management

### Roadmap
- **Q1 2026**: Video integration, Mobile app
- **Q2 2026**: Advanced AI features
- **Q3 2026**: Multi-language support
- **Q4 2026**: Full system integration

---

## 📞 Support & Resources

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [FullCalendar Docs](https://fullcalendar.io)

### Contact
- Technical Support: support@churchmanagement.com
- Feature Requests: github.com/church-management/issues
- Community Forum: community.churchmanagement.com

---

## 📝 License & Credits

Built with ❤️ for church communities worldwide.

**Technologies Used:**
- Laravel 10.x
- PHP 8.1+
- Tailwind CSS
- Alpine.js
- FullCalendar
- MySQL/PostgreSQL

**Author:** Church Management Development Team
**Version:** 2.0.0
**Last Updated:** October 2025

---

## 🎉 Conclusion

This world-class counselling system provides:
- ✅ Comprehensive session management
- ✅ AI-powered insights and automation
- ✅ Bank-level security and encryption
- ✅ Intuitive modern UI
- ✅ Flexible and customizable
- ✅ Scalable for churches of any size

**Start making a difference in people's lives with professional, secure, and intelligent counselling management!** 🙏
