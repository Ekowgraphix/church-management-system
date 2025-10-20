# Fix File Upload Size Limit - Action Required ⚠️

## Error
```
POST Content-Length of 52620939 bytes exceeds the limit of 41943040 bytes
```

**Your file size:** 52.6 MB  
**Current PHP limit:** 40 MB  
**Result:** Upload blocked ❌

## Solution: Increase PHP Upload Limits

You need to edit your **php.ini** file to increase the upload limits.

### PHP.ini Location
**Path:** `F:\xampp\php\php.ini`

### Steps to Fix

#### 1. Open php.ini File
- Navigate to: `F:\xampp\php\`
- Open `php.ini` in a text editor (Notepad, VS Code, etc.)

#### 2. Find and Update These Settings

Search for these lines and change them:

```ini
; Current (too small):
upload_max_filesize = 40M
post_max_size = 40M
max_execution_time = 30
memory_limit = 128M

; Change to (larger):
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 256M
```

**Explanation:**
- `upload_max_filesize = 100M` - Max file size for uploads
- `post_max_size = 100M` - Max POST data size (must be ≥ upload_max_filesize)
- `max_execution_time = 300` - Script timeout (5 minutes for large uploads)
- `memory_limit = 256M` - PHP memory limit

#### 3. Save the File

Save `php.ini` after making the changes.

#### 4. Restart Apache

You MUST restart Apache for changes to take effect:

**Option A: XAMPP Control Panel**
1. Open XAMPP Control Panel
2. Click "Stop" next to Apache
3. Wait a few seconds
4. Click "Start" next to Apache

**Option B: Command Line**
```bash
# Stop Apache
net stop Apache2.4

# Start Apache
net start Apache2.4
```

#### 5. Verify Changes

After restarting Apache, verify the new limits:

Create a test file: `F:\xampp\htdocs\churchmang\public\phpinfo.php`

```php
<?php
phpinfo();
```

Visit: `http://127.0.0.1:8000/phpinfo.php`

Search for:
- ✅ `upload_max_filesize` should show **100M**
- ✅ `post_max_size` should show **100M**
- ✅ `memory_limit` should show **256M**

**Delete the phpinfo.php file after checking!** (Security risk)

### Alternative: Quick Fix (Smaller File)

If you don't want to change PHP settings, you can:

1. **Compress your video** to under 40MB
2. **Use a different format** (MP3 audio instead of MP4 video)
3. **Lower video quality/resolution**

### Recommended Upload Limits

For sermon videos/audio, I recommend:

```ini
upload_max_filesize = 100M   ; Allows videos up to 100MB
post_max_size = 100M         ; Same as upload limit
max_execution_time = 300     ; 5 minutes for upload
memory_limit = 256M          ; Enough memory for processing
```

For even larger files (HD videos):

```ini
upload_max_filesize = 500M   ; Allows videos up to 500MB
post_max_size = 500M
max_execution_time = 600     ; 10 minutes
memory_limit = 512M
```

### Controller Validation

The controller already allows 50MB:
```php
'audio_file' => 'nullable|file|mimes:mp3,mp4,wav,m4a|max:51200'
```

But PHP's limit was lower (40MB), so increase PHP settings to at least 100MB.

## After Fixing

1. ✅ Edit php.ini
2. ✅ Change upload limits to 100M
3. ✅ Save file
4. ✅ Restart Apache
5. ✅ Try uploading your sermon again

The 52.6MB file should upload successfully! 🎉

## Troubleshooting

### Changes Not Taking Effect?
- Make sure you edited the correct php.ini file (check path with `php --ini`)
- Restart Apache completely (stop, wait, start)
- Clear browser cache
- Check phpinfo.php to confirm new values

### Still Getting Errors?
- Check Apache error log: `F:\xampp\apache\logs\error.log`
- Try a smaller file first to test
- Make sure post_max_size ≥ upload_max_filesize

### File Upload Times Out?
- Increase `max_execution_time` to 600 (10 minutes)
- Increase `max_input_time` to 600 as well

## Security Note

After making changes:
- ✅ Only increase limits to what you need
- ✅ Don't set limits too high (e.g., 1GB) - security risk
- ✅ Keep validation in Laravel controller
- ✅ Delete phpinfo.php test file after checking

## Status: ⚠️ Action Required

**You need to manually edit php.ini and restart Apache.**

Once done, your sermon upload will work! 🎉
