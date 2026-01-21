# Fix for "PostTooLargeException" Error

## Problem
You're encountering a `PostTooLargeException` when trying to upload files larger than the default PHP limits (8MB for POST data, 2MB for file uploads).

## Solution Applied

1. **Custom Middleware**: Created `CustomValidatePostSize` middleware that allows uploads up to 100MB
2. **Updated Bootstrap**: Configured Laravel to use the custom middleware instead of the default one
3. **Updated .htaccess**: Added PHP configuration for Apache servers
4. **Updated index.php**: Added runtime PHP ini settings (may not work for all PHP configurations)

## Additional Steps Required

Since `post_max_size` is typically set to `PHP_INI_PERDIR` mode, you may need to update your PHP configuration directly:

### Option 1: Update php.ini (Recommended for Production)

1. Find your `php.ini` file location:
   ```bash
   php --ini
   ```

2. Edit `php.ini` and update these values:
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   max_execution_time = 300
   max_input_time = 300
   ```

3. Restart your web server or PHP-FPM:
   - For Laravel's built-in server: Stop and restart `php artisan serve`
   - For Apache: `sudo service apache2 restart` (Linux) or restart Apache service (Windows)
   - For Nginx with PHP-FPM: `sudo service php-fpm restart`

### Option 2: Create a user-specific php.ini (Development)

If you can't edit the main `php.ini`, create a `php.ini` file in your project root or in the `public` directory (for some configurations).

### Option 3: Update Laravel Validation

The controller validation already allows up to 50MB (`max:51200` = 51200KB = 50MB). If you want to increase this further, update the validation rule in `app/Http/Controllers/AdminController.php`:

```php
'content_file' => 'nullable|file|mimes:pdf,mp4,mov,avi|max:102400', // 100MB
```

## Verification

After making changes, verify the settings:

```bash
php -i | findstr "post_max_size upload_max_filesize"
```

You should see values of 100M or higher.

## Notes

- The `.htaccess` file only works with Apache. For Laravel's built-in server, you need to update `php.ini`
- Some hosting providers may have additional limits at the web server level (nginx, Apache) that need to be configured separately
- For very large files (100MB+), consider implementing chunked uploads or using cloud storage solutions
