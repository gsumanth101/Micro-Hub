<?php
$file = 'downloads/faculty-upload.xlsx';

// Check if the file exists
if (file_exists($file)) {
    // Get file permissions
    $permissions = fileperms($file);

    // Output permissions
    echo "File Permissions: " . decoct($permissions);
} else {
    echo "File not found!";
}
?>