# 🔍 QR CHECK-IN TROUBLESHOOTING

## ❓ WHAT EXACTLY ISN'T WORKING?

Please tell me which one:

### A. Can't Access the Page at All?
- Getting 404 error?
- Page won't load?
- Shows error message?

### B. Page Loads But Camera Shows Nothing?
- Blank screen where camera should be?
- Black box?
- No camera view?

### C. Camera Shows But Doesn't Scan?
- Camera shows but doesn't detect QR?
- No beep or response when pointing at QR?

### D. Manual Token Entry Not Working?
- Paste token and click Check In but nothing happens?
- Error message shows?

### E. Something Else?
- Describe what you see

---

## 🚀 QUICK TESTS - DO THESE NOW

### Test 1: Can You Access the Page?

**On Desktop:**
```
http://127.0.0.1:8000/qr-checkin/service/scanner
```

**On Mobile (same Wi-Fi):**
```
http://192.168.249.253:8000/qr-checkin/service/scanner
```

**What do you see?**
- [ ] Page loads with scanner
- [ ] 404 error
- [ ] 403 forbidden
- [ ] Blank page
- [ ] Something else: ___________

---

### Test 2: Do You See Weekly Services?

**Scroll down on the page. Do you see:**
```
🟣 SUNDAY
• Sunday Worship Service
  Token: SVC-...

🔵 MONDAY
• Monday Prayer Meeting
  Token: SVC-...
```

- [ ] Yes, I see all services listed
- [ ] No, I don't see any services
- [ ] I see error message

---

### Test 3: Try Manual Token Entry

**Do this:**
1. Copy this test token: `test-token-123`
2. Paste in "Enter token manually" field
3. Click "Check In"

**What happens?**
- [ ] Shows "processing..."
- [ ] Shows error message
- [ ] Nothing happens
- [ ] Something else: ___________

---

## 🔧 COMMON FIXES

### Fix 1: Hard Refresh Browser
```
Desktop: Ctrl + F5 (or Cmd + Shift + R on Mac)
Mobile: Clear browser cache
```

### Fix 2: Check You're Logged In
```
1. Are you logged in as a member?
2. Try logging out and back in
```

### Fix 3: Check Server is Running
```
Look at your command prompt/terminal
Should see: "Server running on [http://..."
```

### Fix 4: Check Same Network
```
Phone and computer must be on SAME Wi-Fi
```

---

## 📱 SPECIFIC ISSUE SOLUTIONS

### Issue: "404 Not Found"

**Solution:**
```bash
# Run these commands:
cd f:\xampp\htdocs\churchmang
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

Then refresh browser.

---

### Issue: "Camera Not Loading"

**This is NORMAL!** Use Manual Token Entry instead:

1. Scroll down on page
2. Find your service (e.g., Monday Prayer Meeting)
3. Copy the token (starts with SVC-)
4. Paste in manual entry field
5. Click "Check In"

**This works even better than camera!**

---

### Issue: "No Services Showing"

**Check database:**
```bash
cd f:\xampp\htdocs\churchmang
php create_all_services.php
```

This creates services for all days.

---

### Issue: "Manual Entry Not Working"

**Try with a real service token:**

1. Go to: Staff Dashboard → Services
2. Look for any service
3. See its QR code
4. Find the token in the QR code
5. Copy that token
6. Try pasting in manual entry

---

## 🎯 TELL ME EXACTLY WHAT YOU SEE

### When you visit:
```
http://127.0.0.1:8000/qr-checkin/service/scanner
```

### Do you see:

**Option A:**
```
┌─────────────────────┐
│ QR Check-In Scanner │
│                     │
│ [Camera area]       │
│                     │
│ Manual entry field  │
│                     │
│ Weekly services ↓   │
└─────────────────────┘
```

**Option B:**
```
404 - Page Not Found
```

**Option C:**
```
403 - Forbidden
```

**Option D:**
```
Blank white page
```

**Option E:**
```
Something else (describe it)
```

---

## 🔍 DEBUG INFORMATION NEEDED

### Please check these:

1. **What URL are you using?**
   ```
   Current URL in browser: _______________
   ```

2. **Are you logged in?**
   ```
   Yes / No / Not sure
   ```

3. **What role/portal?**
   ```
   Member portal / Staff / Admin / Other
   ```

4. **What browser?**
   ```
   Chrome / Firefox / Safari / Edge / Other
   ```

5. **What device?**
   ```
   Desktop / Mobile (Android/iPhone) / Tablet
   ```

6. **Can you see the sidebar menu?**
   ```
   Yes / No
   ```

7. **Do you see "QR Check-In" option in sidebar?**
   ```
   Yes / No
   ```

8. **When you click "QR Check-In", what happens?**
   ```
   Page loads / Error shows / Nothing / Redirect
   ```

---

## 🚀 EMERGENCY WORKAROUND

If nothing works, you can still mark attendance via dashboard!

### Alternative Method:

1. Go to Staff Dashboard
2. Click "Attendance"
3. Click "Mark Attendance"
4. Select member
5. Select service
6. Mark present

**This always works!**

---

## 📞 NEXT STEPS

Please tell me:

1. **What URL you're trying to access**
2. **What you see on screen** (exact error or what displays)
3. **What you tried** (which test from above)
4. **What device** (phone/computer)

Then I can give you the exact fix!

---

## ✅ MOST LIKELY ISSUES

### Top 3 Common Problems:

**1. Cache Not Cleared**
- Solution: Hard refresh (Ctrl + F5)

**2. Not Logged In as Member**
- Solution: Login as member first

**3. Trying to Use Camera (doesn't always work)**
- Solution: Use manual token entry instead!

---

**Tell me what you see and I'll fix it!** 🔧
