<?php
session_start();

// Check login status
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Access denied: Please log in.');
}

// Sanitize and validate requested file name
if (empty($_GET['file'])) {
    http_response_code(400);
    exit('Missing file parameter.');
}

$filename = basename($_GET['file']); // Prevent directory traversal
$filepath = __DIR__ . "/invoices/" . $filename;

// Check if file exists
if (!file_exists($filepath)) {
    http_response_code(404);
    exit('Invoice not found.');
}

// Set headers to display/download the file
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filepath));

// Output the file
readfile($filepath);
exit;
?>
