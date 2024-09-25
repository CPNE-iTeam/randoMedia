<?php
// api/recive.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mediaDir = __DIR__ . '/../medias/';
$files = array_diff(scandir($mediaDir), ['.', '..']);

if (empty($files)) {
    echo json_encode(['error' => 'No files.']);
    exit;
}

$randomFile = $files[array_rand($files)];
$fileName = $randomFile;

echo json_encode(['success' => true, 'file' => $fileName]);
?>
