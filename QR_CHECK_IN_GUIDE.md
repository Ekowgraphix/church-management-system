# 📱 QR CHECK-IN SYSTEM - COMPLETE GUIDE

## 🎯 TWO DIFFERENT PAGES!

### 1. **QR Check-In** (Mark Attendance)
- **Purpose:** SCAN QR codes to mark attendance
- **What you do:** Check in to church services
- **Link:** `http://127.0.0.1:8000/qr-checkin/service/scanner`

### 2. **My Attendance** (View History)
- **Purpose:** VIEW your attendance history
- **What you see:** Past check-ins and stats
- **Link:** `http://127.0.0.1:8000/portal/attendance`

---

## 📋 WHERE TO FIND THEM

### In Sidebar Menu:

```
📊 Dashboard
💰 My Giving
📅 My Attendance      ← VIEW history
📱 QR Check-In        ← MARK attendance (NEW!)
💬 Chat
```

---

## 📱 HOW TO MARK ATTENDANCE

### Step 1: Go to QR Check-In Page

**From Sidebar:**
```
Click: QR Check-In
```

**Or Direct Link:**
```
http://127.0.0.1:8000/qr-checkin/service/scanner
```

### Step 2: See All Services

You'll see:
```
┌─────────────────────────────┐
│ 📱 QR Code Scanner          │
├─────────────────────────────┤
│ [Camera view for scanning]  │
│                             │
│ OR                          │
│                             │
│ Enter token manually:       │
│ [_________________] [Submit]│
├─────────────────────────────┤
│ WEEKLY SERVICES SCHEDULE    │
│                             │
│ 🟣 SUNDAY                   │
│ • Sunday Worship Service    │
│   9:00 AM - 11:30 AM       │
│   Token: SVC-ABC123...      │
│                             │
│ 🔵 MONDAY                   │
│ • Monday Prayer Meeting     │
│   6:00 PM - 8:00 PM        │
│   Token: SVC-XYZ789...      │
│                             │
│ ... (All 7 days)            │
└─────────────────────────────┘
```

### Step 3: Mark Attendance (2 Ways)

#### **Option A: Scan QR Code**
1. Church displays QR code
2. Point camera at QR code
3. Automatic check-in!
4. See success message

#### **Option B: Copy/Paste Token**
1. Find your service in the list
2. Copy the service token (e.g., `SVC-ABC123...`)
3. Paste in "Enter token manually" field
4. Click "Check In"
5. Success!

---

## 📊 WHAT EACH PAGE SHOWS

### QR Check-In Page:

**Shows:**
- ✅ Camera scanner (live)
- ✅ Manual token input field
- ✅ ALL weekly services (Sunday-Saturday)
- ✅ Service names, times, descriptions
- ✅ Service tokens (for manual entry)
- ✅ Color-coded by day

**Purpose:**
- Mark your attendance
- Check in to services
- Scan QR codes
- Enter tokens manually

---

### My Attendance Page:

**Shows:**
- ✅ Total check-ins (all time)
- ✅ Check-ins this year
- ✅ Check-ins this month
- ✅ QR check-ins count
- ✅ Complete attendance history
- ✅ Dates, times, services attended

**Purpose:**
- View your attendance records
- Track your progress
- See statistics
- Verify past check-ins

---

## 🎯 WHEN TO USE EACH

### Use **QR Check-In** When:
- ✅ You're at church
- ✅ You want to mark attendance
- ✅ You need to scan QR code
- ✅ You have service token to enter

### Use **My Attendance** When:
- ✅ You want to see your history
- ✅ You want to check stats
- ✅ You want to verify past attendance
- ✅ You want to track progress

---

## 📱 FULL PROCESS

### AT CHURCH:

1. **Open QR Check-In page**
   ```
   Sidebar → QR Check-In
   ```

2. **See all weekly services listed**
   ```
   Services grouped by day
   Each service shows:
   - Name
   - Time
   - Day of week
   - Service token
   ```

3. **Check in (choose one):**

   **Method 1: Scan QR**
   - Church displays QR code on screen
   - Point camera at QR code
   - Automatic check-in!

   **Method 2: Manual Token**
   - Find today's service in list
   - Copy service token
   - Paste in manual input field
   - Click "Check In"

4. **See success message**
   ```
   ✅ Check-in successful!
   Welcome to [Service Name]
   ```

### LATER - Check Your History:

1. **Open My Attendance page**
   ```
   Sidebar → My Attendance
   ```

2. **See your check-in**
   ```
   Latest check-in shows at top
   With date, time, service name
   ```

---

## 🎨 SERVICE TOKENS

Each service has a unique token like:
```
SVC-67159d8c9e4f8
```

**Where to find tokens:**
- On QR Check-In page (listed under each service)
- In QR code (when scanned)
- From church admin

**How to use:**
1. Copy the token
2. Go to QR Check-In page
3. Paste in "Enter token manually" field
4. Click "Check In"

---

## 🔗 QUICK ACCESS LINKS

### For Desktop/Laptop:

**QR Check-In (Mark Attendance):**
```
http://127.0.0.1:8000/qr-checkin/service/scanner
```

**My Attendance (View History):**
```
http://127.0.0.1:8000/portal/attendance
```

### For Mobile (on same network):

**QR Check-In:**
```
http://192.168.249.253:8000/qr-checkin/service/scanner
```

**My Attendance:**
```
http://192.168.249.253:8000/portal/attendance
```

---

## ✅ FEATURES

### QR Check-In Page Features:

✅ **Live camera scanner**
- Real-time QR code detection
- Automatic check-in
- No typing needed

✅ **Manual token input**
- For when QR code not available
- Copy/paste service token
- Quick check-in

✅ **Weekly services display**
- All 7 days shown
- Color-coded by day
- Service details visible
- Tokens always accessible

✅ **Service information**
- Service name
- Day of week
- Start/end time
- Description
- Unique token

---

## 📊 WEEKLY SERVICES AVAILABLE

The system shows these services:

### 🟣 SUNDAY
- Sunday Worship Service (9:00 AM - 11:30 AM)
- Sunday Evening Service (5:00 PM - 7:00 PM)

### 🔵 MONDAY
- Monday Prayer Meeting (6:00 PM - 8:00 PM)

### 🟢 TUESDAY
- Tuesday Bible Study (6:30 PM - 8:30 PM)

### 🟡 WEDNESDAY
- Wednesday Revival Service (6:00 PM - 8:00 PM)

### 🟠 THURSDAY
- Thursday Youth Service (7:00 PM - 9:00 PM)

### 🔴 FRIDAY
- Friday Night Service (7:00 PM - 9:30 PM)

### 💗 SATURDAY
- Saturday Early Morning Prayer (6:00 AM - 8:00 AM)

**All services have QR codes and tokens!**

---

## 🎯 SUMMARY

### To MARK attendance:
```
Go to: QR Check-In page
→ Scan QR code OR enter token
→ Check in to service
```

### To VIEW attendance:
```
Go to: My Attendance page
→ See your history and stats
```

---

## 🚀 TRY IT NOW!

1. **Open sidebar menu**
2. **Click "QR Check-In"**
3. **See all weekly services**
4. **Try scanning or entering a token!**

---

**Both pages are now in the sidebar for easy access!** 📱✨

---

_QR Check-In System Guide_  
_Updated: October 20, 2025_  
_Two Pages: QR Check-In (mark) + My Attendance (view)_
