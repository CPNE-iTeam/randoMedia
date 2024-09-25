<?php
// api/utils/logger.php

/*
Get secrets with supersecret api
*/
function getSecrets() {
    $ch = curl_init("https://douxxu.lain.ch/prod/rando/secrets/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $secrets = json_decode($response, true);
    if (isset($secrets['error'])) {
        die("Erreur lors de la récupération des secrets : " . $secrets['error']);
    }
    
    return $secrets['secrets'];
}

/*
"you are into the dbs" 🤓
*/
function dbConnect($secrets) {
    $dbName = $secrets[0]['secret']; // name
    $dbUser = $secrets[1]['secret']; // username
    $dbPassword = $secrets[2]['secret']; // pwd
    $dbHost = parse_url($secrets[3]['secret'], PHP_URL_HOST); // url
    $dbPort = parse_url($secrets[3]['secret'], PHP_URL_PORT); // port

    // U need mysqli installed
    $conn = new mysqli($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);

    // Checking co bcs we don't want errors
    if ($conn->connect_error) {
        die("Conn died : " . $conn->connect_error);
    }
    
    return $conn;
}

/*
Uploading logs bcs we need it
*/
function logMediaUpload($fileName, $clientIp) {
    $secrets = getSecrets();
    $conn = dbConnect($secrets);

    // got forced to hash for privacy
    $hashIp = hash('sha256', $clientIp);

    // creation veri creative
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fileName VARCHAR(255) NOT NULL,
            hashIp VARCHAR(64) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    if (!$conn->query($createTableQuery)) {
        die("Error while creating table : " . $conn->error);
    }

    $insertLogQuery = $conn->prepare("INSERT INTO logs (fileName, hashIp) VALUES (?, ?)");
    if ($insertLogQuery === false) {
        die("Error with something : " . $conn->error);
    }

    $insertLogQuery->bind_param("ss", $fileName, $hashIp);

    if (!$insertLogQuery->execute()) {
        die("Error with something : " . $insertLogQuery->error);
    }

    $insertLogQuery->close();
    $conn->close();
}

?>
