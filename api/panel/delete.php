<?php
session_start();
require_once __DIR__ . '/../utils/logger.php';

/*
IS IT AUTH ????
*/
if (!isset($_SESSION['authenticated'])) {
    die("Non autorisé.");
}

$id = $_POST['id'];
$fileName = $_POST['fileName'];
$filePath = __DIR__ . "/../../medias/" . $fileName;

/*
Super secret api
*/
$secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);
$dbname = $secrets['secrets'][0]['secret']; // Name
$user = $secrets['secrets'][1]['secret']; // Username
$pass = $secrets['secrets'][2]['secret']; // Pwd
$dsn = $secrets['secrets'][3]['secret']; // Ip
$host = parse_url($dsn)['host'];

/*
Deleting file & db entry
*/
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // delete in database
    $sql = "DELETE FROM logs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        if (file_exists($filePath)) {
            unlink($filePath); //DELETEEEEEEEEE
        } else {
            echo "File not found.";
        }
        echo "Deleted successfully.";
    } else {
        echo "No entry found with this id, plaese retry..";
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Err while deleting : " . $e->getMessage();
}

header("Location: ../../panel/");
exit;
