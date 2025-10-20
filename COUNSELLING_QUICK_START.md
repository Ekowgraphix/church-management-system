# 🚀 Counselling System - Quick Start Guide

## ⚡ 5-Minute Setup

### Step 1: Run Migrations
```bash
php artisan migrate
```

### Step 2: Seed Initial Data
```bash
php artisan db:seed --class=CounsellingSeeder
```
This creates:
- ✅ 5 Sample Counsellors
- ✅ 7 Categories (Marriage, Family, Personal, Youth, Career, Grief, Spiritual)
- ✅ 10 Sample Sessions

### Step 3: Access the System
Navigate to: **`/counselling`**

---

## 📋 What You Get

### ✨ Core Features
1. **Session Booking** - Schedule and manage counselling sessions
2. **AI Note Summarizer** - Automatically summarize session notes
3. **Follow-up Tracker** - Never miss a follow-up
4. **Confidential Mode** - 3 levels of security
5. **Calendar View** - Visual session scheduling
6. **Encrypted PDF Export** - Secure session backups

### 🎨 Modern UI
- Clean, professional interface
- Real-time statistics dashboard
- Color-coded categories
- Responsive design
- Smooth animations

---

## 🎯 Quick Actions

### Create a New Session
1. Click **"New Session"** button
2. Select counsellor and category
3. Choose date/time and session type
4. Set confidentiality level
5. Save

### Use AI Summary
1. Complete a session with notes
2. Click **"AI Summarize"** button
3. Review AI-generated summary, key points, and action items
4. Copy or save

### Schedule Follow-up
1. Open completed session
2. Click **"Follow-up"** icon
3. Set date and priority
4. Add notes
5. Reminder will be sent automatically

### Export Session
1. Open session details
2. Click **"Export PDF"**
3. Download encrypted PDF

---

## 🔒 Security Levels

### Normal
- All authorized counsellors can view
- Suitable for general sessions

### Private
- Only assigned counsellor + supervisors
- For sensitive topics

### Strict Confidential
- Assigned counsellor ONLY
- Highest security
- Full encryption

---

## 🎨 Color Coding

- **Pink** 💍 = Marriage
- **Blue** 🏠 = Family
- **Purple** 👤 = Personal
- **Cyan** 👥 = Youth
- **Orange** 💼 = Career
- **Red** 💔 = Grief
- **Green** 🙏 = Spiritual

---

## 📊 Dashboard Metrics

### Statistics Shown:
1. **Total Sessions** - All time
2. **This Month** - Current month count
3. **Upcoming** - Scheduled sessions
4. **Active Counsellors** - Available counsellors
5. **Follow-ups Due** - This week
6. **Success Rate** - Resolution percentage

---

## 🤖 AI Features

### Note Summarization
```javascript
// Click "AI Summarize" button or:
CounsellingAI.summarizeNotes(sessionId, notesText);
```

**Returns:**
- Executive summary
- Key discussion points
- Action items checklist

### Follow-up Creation
```javascript
// Click "Schedule Follow-up" or:
CounsellingAI.showFollowupForm(sessionId);
```

**Features:**
- Date picker
- Priority levels
- Automatic reminders

### PDF Export
```javascript
// Click "Export" icon or:
CounsellingAI.exportPDF(sessionId);
```

**Includes:**
- Session details
- Encrypted notes
- Follow-up schedule

---

## 📱 Session Types

1. **In-Person** - Face-to-face meeting
2. **Phone** - Telephone consultation
3. **Video Call** - Online video session
4. **Group** - Group counselling

---

## 👥 Counsellor Management

### Add New Counsellor
```php
Counsellor::create([
    'name' => 'Dr. Jane Smith',
    'email' => 'jane@church.org',
    'phone' => '+1234567890',
    'specialization' => 'Marriage',
    'status' => 'Active',
    'bio' => 'Licensed marriage counsellor',
]);
```

### View Availability
Navigate to: **Counsellor Status** widget in sidebar

---

## 📅 Calendar Features

### Access Calendar
Click **"Calendar"** button or navigate to `/counselling-calendar`

### Calendar Controls
- **Month View** - Overview of all sessions
- **Color-coded** - By category
- **Click** - View session details
- **Filter** - By counsellor or category

---

## 🔔 Notifications

### Automatic Reminders
- **24 hours before** session
- **1 hour before** session
- **When follow-up is due**
- **Weekly** overdue digest

### Channels
- ✉️ Email
- 📱 SMS (if configured)
- 🔔 In-app notifications

---

## 📈 Reports Available

1. **Session Statistics**
   - Total by period
   - Category breakdown
   - Completion rates

2. **Counsellor Performance**
   - Sessions per counsellor
   - Average ratings
   - Specialization effectiveness

3. **Follow-up Tracking**
   - Pending count
   - Overdue alerts
   - Completion rates

---

## 🛠️ Configuration

### Set in `.env`:
```env
# Counselling Settings
COUNSELLING_SESSION_DURATION=60
COUNSELLING_REMINDER_HOURS=24
COUNSELLING_AUTO_FOLLOWUP=true
```

### Customize Categories
```php
CounsellingCategory::create([
    'name' => 'Financial',
    'icon' => 'dollar-sign',
    'color' => 'yellow',
    'description' => 'Financial counselling',
]);
```

---

## 🎓 Best Practices

### For Counsellors
✅ Set appropriate confidentiality level
✅ Use AI summarization for detailed notes
✅ Schedule follow-ups immediately
✅ Update session status promptly
✅ Rate sessions for tracking

### For Administrators
✅ Regular database backups
✅ Audit confidential access weekly
✅ Monitor overdue follow-ups
✅ Review counsellor workload
✅ Export encrypted backups monthly

---

## 🆘 Common Issues

### AI Summary Not Working?
- Check CSRF token exists
- Verify JavaScript file loaded
- Check browser console

### Follow-ups Not Sending?
- Verify scheduler running: `php artisan schedule:work`
- Check mail config in `.env`

### Calendar Not Displaying?
- Ensure sessions have valid dates
- Check JavaScript errors

---

## 📞 Quick Links

- **View All Sessions**: `/counselling`
- **Calendar View**: `/counselling-calendar`
- **Create Session**: `/counselling/create`
- **Counsellor Availability**: API endpoint

---

## 🎉 You're Ready!

The counselling system is now fully operational with:
- ✅ World-class UI
- ✅ AI-powered features
- ✅ Bank-level security
- ✅ Comprehensive tracking
- ✅ Automated workflows

**Start managing counselling sessions professionally! 🙏**

---

## 📚 Additional Resources

- Full Documentation: `COUNSELLING_WORLD_CLASS_GUIDE.md`
- API Reference: `/docs/api/counselling`
- Video Tutorials: Coming soon

---

**Need Help?**
- Check full guide: `COUNSELLING_WORLD_CLASS_GUIDE.md`
- Report issues: GitHub Issues
- Community: Support Forum

**Version:** 2.0.0  
**Last Updated:** October 2025
