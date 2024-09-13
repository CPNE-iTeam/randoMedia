<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$target_dir = "medias/";
$fileType = strtolower(pathinfo($target_dir . $_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
$target_file = $target_dir . basename(round(microtime(true)) . "." . $fileType);
$uploadOk = 1;
$mime = mime_content_type($_FILES["fileToUpload"]["tmp_name"]);


$maxFileSize = 14000000; // 14MB

$allowedFilesTypes = array("jpg", "jpeg", "png", "gif", "mp4", "webm");
$allowedMimesTypes = array("image/jpeg", "image/png", "image/gif", "video/mp4", "video/webm");


if(isset($_POST["submit"])) {
    if(!in_array($fileType, $allowedFilesTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, MP4 & WEBM files are allowed. (0)";
        $uploadOk = 0;
    }

    if(!in_array($mime, $allowedMimesTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, MP4 & WEBM files are allowed. (1)";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > $maxFileSize) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    } 

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            header("Location: index.php");
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}else {
    echo "Sorry, there was an error uploading your file.";
}

?>