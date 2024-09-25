<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(dirname(__FILE__) . "/secrets.php");

$adminPassword = $_POST['adminPassword'];
$mediaPath = $_POST['mediaPath'];

if($adminPassword !== ADMIN_PASSWORD){
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if(unlink($mediaPath)){

}else{
    echo json_encode(['error' => 'Error while removing media.']);
    exit();
}
?>