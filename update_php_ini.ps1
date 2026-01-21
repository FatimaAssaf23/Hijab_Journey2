# PowerShell script to update php.ini for WAMP
# Run this script as Administrator

# Find all php.ini files in WAMP
$phpIniPaths = @()

# Check common WAMP locations
$possiblePaths = @(
    "C:\php8.4\php.ini",
    "C:\wamp64\bin\php\php8.4\php.ini",
    "C:\wamp64\bin\php\php8.4\phpForApache.ini"
)

# Find all php.ini files in WAMP directory
$wampPhpInis = Get-ChildItem -Path "C:\wamp64" -Recurse -Filter "php.ini" -ErrorAction SilentlyContinue | Where-Object { $_.FullName -notlike "*vendor*" -and $_.FullName -notlike "*Sail*" }
foreach ($ini in $wampPhpInis) {
    if ($possiblePaths -notcontains $ini.FullName) {
        $possiblePaths += $ini.FullName
    }
}

# Add the CLI php.ini if it exists
if (Test-Path "C:\php8.4\php.ini") {
    $possiblePaths += "C:\php8.4\php.ini"
}

# Collect unique paths that exist
$phpIniPaths = $possiblePaths | Where-Object { Test-Path $_ } | Select-Object -Unique

Write-Host "Found PHP.ini files:" -ForegroundColor Green
foreach ($path in $phpIniPaths) {
    Write-Host "  - $path" -ForegroundColor Yellow
}

Write-Host "`nUpdating PHP configuration for file uploads..." -ForegroundColor Green

foreach ($phpIniPath in $phpIniPaths) {
    Write-Host "`nProcessing: $phpIniPath" -ForegroundColor Cyan
    
    if (Test-Path $phpIniPath) {
    # Backup the original file
    $backupPath = "$phpIniPath.backup.$(Get-Date -Format 'yyyyMMdd-HHmmss')"
    Copy-Item $phpIniPath $backupPath
    Write-Host "Backup created: $backupPath" -ForegroundColor Green
    
    # Read the file
    $content = Get-Content $phpIniPath -Raw
    
    # Update settings
    $content = $content -replace '(?m)^(;?\s*upload_max_filesize\s*=).*$', 'upload_max_filesize = 100M'
    $content = $content -replace '(?m)^(;?\s*post_max_size\s*=).*$', 'post_max_size = 100M'
    $content = $content -replace '(?m)^(;?\s*memory_limit\s*=).*$', 'memory_limit = 512M'
    $content = $content -replace '(?m)^(;?\s*max_execution_time\s*=).*$', 'max_execution_time = 300'
    
    # Write the file
    Set-Content -Path $phpIniPath -Value $content -NoNewline
    
        Write-Host "  ✓ Updated successfully" -ForegroundColor Green
    } else {
        Write-Host "  ✗ File not found" -ForegroundColor Red
    }
}

Write-Host "`n" -NoNewline
Write-Host "PHP configuration updated successfully!" -ForegroundColor Green
Write-Host "`nUpdated settings in all php.ini files:" -ForegroundColor Cyan
Write-Host "  - upload_max_filesize = 100M"
Write-Host "  - post_max_size = 100M"
Write-Host "  - memory_limit = 512M"
Write-Host "  - max_execution_time = 300"
Write-Host "`n⚠️  IMPORTANT: Restart WAMP server completely for changes to take effect!" -ForegroundColor Yellow
Write-Host "   1. Right-click WAMP icon in system tray" -ForegroundColor White
Write-Host "   2. Click 'Stop All Services'" -ForegroundColor White
Write-Host "   3. Wait 5 seconds" -ForegroundColor White
Write-Host "   4. Click 'Start All Services'" -ForegroundColor White
Write-Host "   5. Visit: http://127.0.0.1:8000/check_php_config.php to verify" -ForegroundColor White
