# 📱 QR SCANNER TROUBLESHOOTING GUIDE

## 🎯 SCANNER NOT WORKING? HERE'S WHY

The QR scanner camera may not work due to **browser security requirements**. But don't worry - there are TWO ways to check in!

---

## 🔍 WHY ISN'T THE CAMERA WORKING?

### Common Issues:

1. **Camera Permission Denied**
   - Browser asks for camera access
   - You must click "Allow" to use scanner

2. **HTTP vs HTTPS**
   - Most browsers block camera on HTTP
   - Works on: HTTPS, localhost, 127.0.0.1, 192.168.x.x

3. **Browser Doesn't Support Camera**
   - Older browsers may not support camera API
   - Update your browser to latest version

4. **Camera In Use**
   - Another app is using the camera
   - Close other apps using camera

---

## ✅ SOLUTION: TWO WAYS TO CHECK IN

### Method 1: Camera Scanner (If Available) 📹

```
1. Grant camera permission when browser asks
2. Point camera at QR code
3. Automatic check-in!
```

**If camera doesn't work, use Method 2!**

---

### Method 2: Manual Token Entry (ALWAYS WORKS) ⌨️

This method ALWAYS works - no camera needed!

```
1. Look at the weekly services list on the page
2. Find today's service
3. Copy the service token (e.g., SVC-ABC123...)
4. Paste in "Manual Token Entry" field
5. Click "Check In"
6. Done!
```

---

## 📱 HOW TO USE MANUAL TOKEN ENTRY

### Step-by-Step:

**1. Find Your Service**
```
On the QR Check-In page, scroll down to see:

🟣 SUNDAY
• Sunday Worship Service
  9:00 AM - 11:30 AM
  Token: SVC-67159d8c9e4f8  ← Copy this!

🔵 MONDAY  
• Monday Prayer Meeting
  6:00 PM - 8:00 PM
  Token: SVC-89abc123def45  ← Or this!
```

**2. Copy the Token**
```
Long press on the token → Copy
Or select and Ctrl+C (desktop)
```

**3. Paste and Submit**
```
Scroll to "Manual Token Entry" section
Paste token in the input field
Click "Check In" button
```

**4. Success!**
```
✅ Check-in successful!
Your attendance is marked!
```

---

## 🔧 WHAT I FIXED

### Added Better Error Handling:

1. **✅ Camera Permission Check**
   - Shows message if camera blocked
   - Guides user to manual entry

2. **✅ HTTPS Security Check**
   - Detects if page is secure
   - Shows appropriate message

3. **✅ Error Messages**
   - Clear instructions when camera fails
   - Guides to alternative method

4. **✅ Fallback Option**
   - Manual token entry ALWAYS available
   - No camera needed!

---

## 📱 ENABLING CAMERA PERMISSION

### On Android Chrome:

1. When page loads, tap "Allow" on camera popup
2. Or go to: Browser → Menu (⋮) → Settings
3. Site settings → Camera
4. Find your site → Allow

### On iPhone Safari:

1. Settings → Safari
2. Camera → Ask
3. Reload page and tap "Allow"

### On Desktop Chrome:

1. Click lock icon (🔒) in address bar
2. Site settings
3. Camera → Allow
4. Reload page

---

## ✅ WHAT YOU'LL SEE NOW

### If Camera Works:
```
┌──────────────────────────┐
│ 📹 [Live Camera View]    │
│                          │
│ Scanner ready! Point     │
│ camera at QR code        │
└──────────────────────────┘
```

### If Camera Doesn't Work:
```
┌──────────────────────────┐
│ ⚠️ Camera Not Available  │
│                          │
│ Please use manual token  │
│ entry below              │
└──────────────────────────┘

↓

Enter token manually:
[________________] [Check In]
```

---

## 🎯 QUICK SOLUTION

**Camera Not Working?**

👉 **Just use Manual Token Entry!**

```
1. Scroll down on QR Check-In page
2. Find your service in the list
3. Copy the service token
4. Paste in manual entry field
5. Click "Check In"
```

**That's it! No camera needed!**

---

## 📊 SERVICE TOKENS

### Where to Find Them:

Every service shows its token on the QR Check-In page:

```
📅 Weekly Services Schedule

🟣 SUNDAY
• Sunday Worship Service
  9:00 AM - 11:30 AM
  Token: SVC-67159d8c9e4f8  ← HERE

• Sunday Evening Service  
  5:00 PM - 7:00 PM
  Token: SVC-abc123def456  ← HERE
```

**Just copy and paste!**

---

## 💡 PRO TIPS

### Tip 1: Save Your Service Token
- Copy token to notes app
- Use it every week
- No need to find it again

### Tip 2: Screenshot the QR Code
- Take photo of church QR code
- Scan from your photo later
- (Some scanners support this)

### Tip 3: Bookmark the Page
- Save QR Check-In page
- Quick access every service
- One tap to mark attendance

### Tip 4: Manual Entry is Fast!
- Copy token once
- Paste every week
- 2 seconds total!

---

## 🚀 TRY IT NOW

### Test Manual Entry:

1. **Go to QR Check-In page**
   ```
   http://127.0.0.1:8000/qr-checkin/service/scanner
   ```

2. **Scroll down to any service**

3. **Copy its token** (starts with SVC-)

4. **Paste in Manual Token Entry field**

5. **Click "Check In"**

6. **Should work!** ✅

---

## ✅ BOTH METHODS WORK!

### Camera Scanner:
- ✅ Fast and convenient
- ✅ Just point and scan
- ⚠️ Needs camera permission
- ⚠️ May not work on some devices

### Manual Token Entry:
- ✅ **ALWAYS WORKS!**
- ✅ No camera needed
- ✅ Works on ANY device
- ✅ Works on ANY browser
- ✅ Copy and paste - done!

---

## 🎯 RECOMMENDATION

**Use Manual Token Entry!**

Why? Because:
- ✅ It ALWAYS works
- ✅ No permission issues
- ✅ No camera needed
- ✅ Fast and simple
- ✅ Works everywhere

**Just copy the token and paste it. That's it!**

---

## 📱 MOBILE USERS

### If Camera Doesn't Work:

Don't worry! The manual token entry is actually **faster**:

1. Scroll down → see services
2. Tap to select token
3. Copy
4. Scroll up
5. Paste
6. Check in

**Takes 5 seconds!**

---

## ✅ SUMMARY

**Scanner Not Working?**

👉 **Use Manual Token Entry Instead!**

**Where?**
- On the same QR Check-In page
- Under the scanner
- "Enter token manually" field

**How?**
1. Find your service
2. Copy token
3. Paste
4. Check in

**Done!** ✅

---

## 🚀 REFRESH AND TRY

1. **Hard refresh:** Ctrl + F5 (or Cmd + Shift + R)
2. **Go to:** QR Check-In page
3. **If camera works:** Great! Use it!
4. **If camera doesn't work:** Use manual entry!

---

**Both methods mark your attendance the same way!** 

**Choose whichever is easier for you!** 📱✨

---

_QR Scanner Troubleshooting: October 20, 2025_  
_Two Methods: Camera Scanner + Manual Token Entry_  
_Both work perfectly!_
