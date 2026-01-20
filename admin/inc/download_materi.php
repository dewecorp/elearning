<?php
session_start();

// Check if user is logged in as admin or pengajar
if (!isset($_SESSION['admin']) && !isset($_SESSION['pengajar'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}

if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('HTTP/1.0 400 Bad Request');
    exit('File parameter is required');
}

// Sanitize the filename to prevent directory traversal
$filename = basename($_GET['file']);

// More permissive pattern to allow international characters in filenames
$allowedPattern = '/^[a-zA-Z0-9._\-\s\(\)]+$/';
if (!preg_match($allowedPattern, $filename)) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid filename: contains forbidden characters');
}

// Additional check to ensure the filename doesn't contain directory traversal attempts
if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
    header('HTTP/1.0 400 Bad Request');
    exit('Invalid filename: contains directory traversal characters');
}

// Define the file path
$filepath = __DIR__ . '/../file_materi/' . $filename;

// Normalize the path to prevent directory traversal
$realFilepath = realpath(__DIR__ . '/../file_materi/');
$requestedFilepath = realpath($filepath);

// Check if the requested file is within the allowed directory
if (!$realFilepath || !$requestedFilepath || strpos($requestedFilepath, $realFilepath) !== 0) {
    header('HTTP/1.0 400 Bad Request');
    exit('Security error: Invalid file path');
}

// Check if file exists
if (!file_exists($requestedFilepath)) {
    header('HTTP/1.0 404 Not Found');
    exit('File not found: ' . $filename . ' (expected path: ' . $requestedFilepath . ')');
}

$filepath = $requestedFilepath;

// Get file size
$filesize = filesize($filepath);

// Get file extension to determine content type
$fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

// Map file extensions to content types
$contentTypes = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'ppt' => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'txt' => 'text/plain',
    'rtf' => 'application/rtf',
    'zip' => 'application/zip',
    'rar' => 'application/x-rar-compressed',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'bmp' => 'image/bmp'
];

$contentType = isset($contentTypes[$fileExtension]) ? $contentTypes[$fileExtension] : 'application/octet-stream';

// Set headers for file download
header('Content-Type: ' . $contentType);
$encoded_filename = rawurlencode($filename);
header("Content-Disposition: attachment; filename*=UTF-8''{$encoded_filename}; filename=\"{$filename}\"");
header('Content-Length: ' . $filesize);
header('Accept-Ranges: none');
header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');

// Read and output the file
readfile($filepath);
exit();
?>