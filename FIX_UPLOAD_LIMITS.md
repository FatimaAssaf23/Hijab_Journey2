# Fix PHP Upload Limits - CRITICAL FIX NEEDED

## The Problem
Your video file (54.5 MB) exceeds PHP's `post_max_size` limit of 8 MB. Even though you updated `php.ini`, WAMP has **multiple php.ini files** and you need to update the one that Apache uses.

## Found php.ini Files in WAMP:

1. **`C:\wamp64\bin\apache\apache2.4.65\bin\php.ini`** ⚠️ **THIS IS THE ONE APACHE USES!**
2. `C:\wamp64\bin\php\php8.4.15\php.ini` (CLI version)
3. `C:\php8.4\php.ini` (Standalone PHP)

## Solution: Update the Apache php.ini File

### Step 1: Open the Correct php.ini File

**Open this file: `C:\wamp64\bin\apache\apache2.4.65\bin\php.ini`**

Right-click → Open with Notepad (as Administrator if needed)

### Step 2: Find and Update These Settings

Search for (Ctrl+F) and update these lines:

```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
max_execution_time = 300
```

**Important:** Remove the semicolon (`;`) at the beginning if the line is commented out!

### Step 3: Save the File

Save the file (Ctrl+S)

### Step 4: Restart WAMP COMPLETELY

1. Right-click the **WAMP icon** in your system tray (bottom right)
2. Click **"Stop All Services"**
3. Wait 10 seconds
4. Click **"Start All Services"**
5. Wait until the icon turns **green**

### Step 5: Verify the Changes

Visit: `http://127.0.0.1:8000/check_php_config.php`

Check that these values are updated:
- ✅ `upload_max_filesize` = 100M
- ✅ `post_max_size` = 100M
- ✅ `memory_limit` = 512M

**⚠️ IMPORTANT: Delete `check_php_config.php` after verifying!**

## Quick Fix Script (Alternative)

If you prefer using the PowerShell script:

1. Open PowerShell **as Administrator**
2. Navigate to your project: `cd C:\wamp64\www\CapHijabJourny`
3. Run: `.\update_php_ini.ps1`
4. This will update ALL php.ini files found in WAMP
5. **Restart WAMP completely** (Stop All Services → Start All Services)

## Why This Happens

WAMP uses separate php.ini files for:
- **Apache** (web server) - `C:\wamp64\bin\apache\apache2.4.65\bin\php.ini`
- **CLI** (command line) - `C:\wamp64\bin\php\php8.4.15\php.ini`

You need to update the **Apache** one for web uploads to work!

## After Fixing

Once you've updated the Apache php.ini and restarted WAMP, your video uploads should work!
