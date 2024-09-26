<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mediaDir = __DIR__ . '/../medias/';
$files = array_diff(scandir($mediaDir), ['.', '..']);
shuffle($files);
echo json_encode(['success' => true, 'files' => array_values(array_slice($files, 0, 70))]);
?>
