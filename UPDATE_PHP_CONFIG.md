# How to Fix PHP Upload Limits for Video Uploads

## Problem
Your video file (54.5 MB) exceeds PHP's current limits:
- `post_max_size = 8M` (needs to be at least 55M, recommended 100M)
- `upload_max_filesize = 2M` (needs to be at least 55M, recommended 100M)

## Solution 1: Update php.ini (Recommended)

Your PHP configuration file is located at: **`C:\php8.4\php.ini`**

### Manual Update Steps:

1. **Open php.ini** in a text editor (as Administrator)
   - Location: `C:\php8.4\php.ini`
   - Right-click → "Edit" (may need to open as Administrator)

2. **Find and update these settings:**
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   memory_limit = 512M
   max_execution_time = 300
   ```

3. **Save the file**

4. **Restart WAMP Server:**
   - Click WAMP icon in system tray
   - Click "Restart All Services"

### Quick Update (PowerShell Script):

1. Open PowerShell as Administrator
2. Navigate to your project directory
3. Run:
   ```powershell
   .\update_php_ini.ps1
   ```
4. Restart WAMP Server

## Solution 2: Verify Changes

After updating php.ini and restarting WAMP:

1. Create `public/info.php`:
   ```php
   <?php phpinfo(); ?>
   ```

2. Visit: `http://127.0.0.1:8000/info.php`

3. Search for:
   - `upload_max_filesize` - should show `100M`
   - `post_max_size` - should show `100M`
   - `memory_limit` - should show `512M`

4. **Delete `info.php` after checking** (security)

## Solution 3: .htaccess (Already Added)

I've already added PHP directives to `public/.htaccess`. These will work if PHP is running as an Apache module.

## Important Notes

- `upload_max_filesize` and `post_max_size` **CANNOT** be changed with `ini_set()` at runtime
- They **MUST** be set in php.ini before the request starts
- `post_max_size` must be **larger** than `upload_max_filesize`
- After changing php.ini, **always restart your web server**

## Current vs Required Settings

| Setting | Current | Required | Location |
|---------|---------|----------|----------|
| `upload_max_filesize` | 2M | 100M | php.ini |
| `post_max_size` | 8M | 100M | php.ini |
| `memory_limit` | 128M | 512M | php.ini (or runtime) |
| `max_execution_time` | 30 | 300 | php.ini (or runtime) |

## Video File Size Note

Your video file is 54.5 MB for an 8-second video, which is quite large. Consider:
- Compressing the video using HandBrake or FFmpeg
- Using a more efficient codec (H.264)
- Reducing resolution if not needed
