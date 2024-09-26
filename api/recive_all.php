<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mediaDir = __DIR__ . '/../medias/';
$files = array_diff(scandir($mediaDir), ['.', '..']);

echo json_encode(['success' => true, 'files' => array_values($files)]);
?>
