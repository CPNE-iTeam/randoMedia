<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once(dirname(__FILE__) . "/secrets.php");

if (!isset($_POST['adminPassword'], $_POST['mediaPath'])) {
    echo json_encode(['error' => 'Missing required parameters.']);
    exit();
}

$adminPassword = $_POST['adminPassword'];
$mediaPath = dirname(__FILE__) . "/../" . $_POST['mediaPath'];

if ($adminPassword !== ADMIN_PASSWORD) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if (!file_exists($mediaPath)) {
        echo json_encode(['error' => 'Media file does not exist.']);
    exit();
}

if (unlink($mediaPath)) {
    echo json_encode(['success' => 'Media removed successfully.']);
} else {
    echo json_encode(['error' => 'Error while removing media.']);
    exit();
}
?>
