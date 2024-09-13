<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandoMedia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
        $videoExts = ['mp4', 'webm'];
        $path = 'medias';
        $files = array_diff(scandir($path), array('.', '..'));
        
        $selectedMedia = $files[array_rand($files)];
        $ext = pathinfo($selectedMedia, PATHINFO_EXTENSION);

        if (in_array($ext, $videoExts)) {   
            echo '<video controls>';
            echo '<source src="'.$path.'/'.$selectedMedia.'" type="video/mp4">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
        } else {
            echo '<img src="'.$path.'/'.$selectedMedia.'" alt="Random image">';
        }
    ?>
    <footer>
        <a href="new.php">Upload new random media</a>
    </footer>
</body>
</html>