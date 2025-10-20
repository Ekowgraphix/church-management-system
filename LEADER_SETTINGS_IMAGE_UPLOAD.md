# Ministry Leader Settings - Image Upload Feature ✅

## What Was Added

Added profile photo upload functionality to the Ministry Leader Settings page with live preview.

## Features

### 1. ✅ Profile Photo Upload
- **Location:** Ministry Leader Portal → Settings
- **Upload Area:** Click camera icon on profile photo
- **Formats Supported:** JPG, PNG, GIF, JPEG
- **Max File Size:** 2MB
- **Live Preview:** See image before saving

### 2. ✅ Form Validation
- Client-side validation (file size & type)
- Server-side validation
- Image format checking
- Size limit enforcement

### 3. ✅ Image Management
- Auto-creates upload directory
- Unique filename generation
- Deletes old photo when uploading new one
- Fallback to default avatar if no photo

## Files Modified

### 1. **View:** `resources/views/ministry-leader/settings.blade.php`
- Added profile photo upload section with preview
- Added camera icon button for photo selection
- Added JavaScript for live image preview
- Added success/error message display
- Updated all forms with proper POST actions and CSRF tokens

### 2. **Controller:** `app/Http/Controllers/MinistryLeaderPortalController.php`
- Added `updateSettings()` method for profile updates
- Added `updatePassword()` method for password changes
- Added Hash facade import
- Handles file upload, validation, and storage

### 3. **Routes:** `routes/web.php`
- Added `POST /ministry-leader/settings/update` route
- Added `POST /ministry-leader/settings/password` route

### 4. **Assets:** `public/images/default-avatar.svg`
- Created default purple avatar SVG
- Used when user has no profile photo

## How It Works

### Upload Flow:
1. User clicks camera icon on profile photo
2. Selects image file (max 2MB)
3. JavaScript validates file size and type
4. Live preview shows selected image
5. User clicks "Update Profile"
6. Server validates and saves image
7. Old photo deleted (if exists)
8. New photo saved with unique filename
9. Success message displayed

### File Storage:
- **Directory:** `public/uploads/profiles/`
- **Filename Format:** `{timestamp}_{user_id}.{extension}`
- **Example:** `1760909234_5.jpg`

## Validation Rules

### Profile Update:
```php
'name' => 'required|string|max:255'
'email' => 'required|email|max:255|unique (except current user)'
'phone' => 'nullable|string|max:20'
'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
```

### Password Change:
```php
'current_password' => 'required'
'password' => 'required|min:8|confirmed'
'password_confirmation' => 'required'
```

## Features In Detail

### Client-Side Validation:
```javascript
- Check file size < 2MB
- Check file type is image/*
- Show alert if validation fails
- Clear input if invalid
- Show live preview if valid
```

### Server-Side Processing:
```php
- Validate all inputs
- Check file is valid image
- Create uploads directory if needed
- Delete old photo (if exists)
- Generate unique filename
- Move file to uploads/profiles/
- Save filename to database
- Return success message
```

## UI Features

### Profile Photo Section:
- **Circular avatar** with purple border
- **Camera icon button** overlay (bottom-right)
- **Hover effect** on camera button
- **Responsive design**
- **File size info** displayed

### Form Layout:
- **Grid layout** - 2 columns on desktop
- **Responsive** - stacks on mobile
- **Purple theme** matching ministry leader branding
- **Clear labels** and placeholders
- **Success/Error messages** at top

## How to Use

### As a Ministry Leader:

1. **Navigate to Settings:**
   - Click your profile icon
   - Select "Settings" from menu
   - Or go to: `/ministry-leader/settings`

2. **Upload Photo:**
   - Click the camera icon on your profile picture
   - Select an image from your computer
   - Preview appears instantly
   - Update other fields if needed
   - Click "Update Profile"

3. **Change Password:**
   - Scroll to "Change Password" section
   - Enter current password
   - Enter new password (min 8 characters)
   - Confirm new password
   - Click "Change Password"

## Technical Details

### Default Avatar:
- **SVG format** - scales perfectly
- **Purple circle** background
- **White silhouette** icon
- **Matches** ministry leader theme

### Security:
- ✅ CSRF protection on all forms
- ✅ File type validation
- ✅ File size limit enforced
- ✅ Unique filenames prevent overwriting
- ✅ Current password required for changes
- ✅ Password confirmation required

### Error Handling:
- Invalid file type → Alert + clear input
- File too large → Alert + clear input
- Validation errors → Display at top
- Wrong password → Error message
- Upload failure → Error message

## Storage Structure

```
public/
├── uploads/
│   └── profiles/
│       ├── 1760909234_5.jpg
│       ├── 1760909567_12.png
│       └── ...
└── images/
    └── default-avatar.svg
```

## Routes

```php
GET  /ministry-leader/settings           → Show settings page
POST /ministry-leader/settings/update    → Update profile & photo
POST /ministry-leader/settings/password  → Change password
```

## Database

Uses existing `users` table with `profile_photo` column:
- Stores filename only (not full path)
- Example: `1760909234_5.jpg`
- NULL if no photo uploaded

## Success Messages

- **Profile Updated:** "Profile updated successfully!"
- **Password Changed:** "Password changed successfully!"

## Error Messages

- **File Too Large:** "File size must be less than 2MB"
- **Wrong Type:** "Please select an image file"
- **Wrong Password:** "Current password is incorrect"
- **Validation Errors:** Specific field errors displayed

## Testing Checklist

✅ Upload new profile photo  
✅ Preview shows before saving  
✅ File size validation works  
✅ File type validation works  
✅ Old photo deleted on new upload  
✅ Update name, email, phone  
✅ Change password with correct current password  
✅ Reject password change with wrong current password  
✅ Password confirmation must match  
✅ Success messages display  
✅ Error messages display  
✅ Form retains data on validation error  
✅ Default avatar shows for new users  

## Browser Compatibility

- ✅ Chrome/Edge (Chromium)
- ✅ Firefox
- ✅ Safari
- ✅ Mobile browsers

## Future Enhancements (Optional)

- [ ] Image cropping tool
- [ ] Drag-and-drop upload
- [ ] Multiple photo sizes (thumbnail, medium, large)
- [ ] Profile photo gallery/history
- [ ] Remove photo option
- [ ] Image compression on upload
- [ ] Progress bar for large uploads

## Status: ✅ Complete & Ready to Use!

The image upload feature is fully functional and ready for ministry leaders to update their profiles!

---

**Try it now:** Go to `/ministry-leader/settings` and upload your profile photo! 📸
