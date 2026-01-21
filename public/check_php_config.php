<?php
/**
 * Temporary PHP Configuration Checker
 * 
 * IMPORTANT: Delete this file after checking for security reasons!
 * 
 * This file shows the current PHP configuration that the web server is using.
 * It helps identify which php.ini file is being used and what the current limits are.
 */

// Get the php.ini file path
$phpIniPath = php_ini_loaded_file();
$additionalIniFiles = php_ini_scanned_files();

echo "<h1>PHP Configuration Check</h1>";
echo "<p><strong>Loaded php.ini file:</strong> " . ($phpIniPath ?: 'None (using defaults)') . "</p>";

if ($additionalIniFiles) {
    echo "<p><strong>Additional ini files scanned:</strong> " . $additionalIniFiles . "</p>";
}

echo "<hr>";
echo "<h2>Upload Settings</h2>";

$settings = [
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'max_input_time' => ini_get('max_input_time'),
    'file_uploads' => ini_get('file_uploads') ? 'On' : 'Off',
];

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
echo "<tr><th>Setting</th><th>Current Value</th><th>Required Value</th><th>Status</th></tr>";

$required = [
    'upload_max_filesize' => '100M',
    'post_max_size' => '100M',
    'memory_limit' => '512M',
    'max_execution_time' => '300',
];

foreach ($settings as $key => $value) {
    $requiredValue = $required[$key] ?? 'N/A';
    $status = 'OK';
    $color = 'green';
    
    if (isset($required[$key])) {
        // Simple comparison (for demo purposes)
        $currentBytes = parseSize($value);
        $requiredBytes = parseSize($requiredValue);
        
        if ($currentBytes < $requiredBytes) {
            $status = 'NEEDS UPDATE';
            $color = 'red';
        }
    } else {
        $status = 'N/A';
        $color = 'gray';
    }
    
    echo "<tr>";
    echo "<td><strong>$key</strong></td>";
    echo "<td>$value</td>";
    echo "<td>$requiredValue</td>";
    echo "<td style='color: $color; font-weight: bold;'>$status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h2>How to Fix</h2>";
echo "<ol>";
echo "<li>Update the php.ini file shown above</li>";
echo "<li>Set these values:<br>";
echo "<pre>";
echo "upload_max_filesize = 100M\n";
echo "post_max_size = 100M\n";
echo "memory_limit = 512M\n";
echo "max_execution_time = 300\n";
echo "</pre>";
echo "</li>";
echo "<li>Restart WAMP Server completely</li>";
echo "<li>Refresh this page to verify changes</li>";
echo "<li><strong>Delete this file (check_php_config.php) after checking!</strong></li>";
echo "</ol>";

function parseSize($size) {
    $size = trim($size);
    $last = strtolower($size[strlen($size)-1]);
    $size = (int) $size;
    
    switch($last) {
        case 'g': $size *= 1024;
        case 'm': $size *= 1024;
        case 'k': $size *= 1024;
    }
    
    return $size;
}
?>
