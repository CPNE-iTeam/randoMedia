<?php
// api/upload.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(dirname(__FILE__) . '/utils/logger.php');

$maxUploadSize = 14 * 1024 * 1024; // 14 MB because we are rats

if (isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];

    if ($file['size'] > $maxUploadSize) {
        echo json_encode(['error' => 'TOO BIG.']);
        exit;
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm'];
    $allowedMimesTypes = ["image/jpeg", "image/png", "image/gif", "video/mp4", "video/webm"];
    $mime = mime_content_type($_FILES['fileToUpload']['tmp_name']);
    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
        echo json_encode(['error' => 'Unauthorized filetype.']);
        exit;
    }

    if (!in_array($mime, $allowedMimesTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, MP4 & WEBM files are allowed.";
        exit;
    }

    $mediaDir = __DIR__ . '/../medias/';
    do {
        $randomName = uniqid('', true) . '.' . $fileExtension;
    } while (file_exists($mediaDir . $randomName));

    if (move_uploaded_file($file['tmp_name'], $mediaDir . $randomName)) {
        $clientIp = $_SERVER['REMOTE_ADDR'];
        logMediaUpload($randomName, $clientIp);

        echo json_encode(['success' => true, 'file' => '/medias/' . $randomName]);
    } else {
        echo json_encode(['error' => 'Error while uploading.']);
    }
} else {
    echo json_encode(['error' => 'Error while uploading.']);
}
?>
