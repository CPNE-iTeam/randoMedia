<?php
session_start();
require_once __DIR__ . '/../api/utils/logger.php';

/*
auth=0: authpage
*/
if (!isset($_SESSION['authenticated'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);
        
        $username = $secrets['secrets'][4]['secret'];
        $password = $secrets['secrets'][5]['secret'];
        
        if ($_POST['username'] === $username && $_POST['password'] === $password) {
            $_SESSION['authenticated'] = true;
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}

/*
auth
*/
if (!isset($_SESSION['authenticated'])) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Modération</title>
        <link rel="stylesheet" href="../styles/panel.css">
    </head>
    <body>
        <div class="container">
            <h1>Connexion</h1>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="post" action="">
                <label for="username">Username :</label>
                <input type="text" name="username" required>
                <br>
                <label for="password">Password :</label>
                <input type="password" name="password" required>
                <br>
                <input type="submit" value="Se connecter">
            </form>
        </div>
    </body>
    </html>
    <?php
    exit; // No auth = GETOUT
}

/*
Got infos from super secret api
*/
$secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);
$dbname = $secrets['secrets'][0]['secret']; // name
$user = $secrets['secrets'][1]['secret']; // usernema
$pass = $secrets['secrets'][2]['secret']; // pwd
$dsn = $secrets['secrets'][3]['secret']; // ip
$host = parse_url($dsn)['host'];
$db = ltrim(parse_url($dsn)['path'], '/');

try {
    // Connect to DBBBBBB
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get datas
    $sql = "SELECT * FROM logs";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<div class='container'>";
        echo "<h1>ModPanel</h1>";
        echo "<link rel='stylesheet' href='../styles/panel.css'>";
        echo "<table><tr><th>ID</th><th>File Name</th><th>Hash IP</th><th>Timestamp</th><th>Action</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            $fileName = $row['fileName'];
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$fileName}</td>
                    <td>{$row['hashIp']}</td>
                    <td>{$row['timestamp']}</td>
                    <td>
                        <form action='../api/panel/delete.php' method='post'>
                            <input type='hidden' name='id' value='{$row['id']}'>
                            <input type='hidden' name='fileName' value='$fileName'>
                            <input type='submit' value='Delete'>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
        
        echo "<form action='../api/panel/logout.php' method='post'>
                <input type='hidden' name='logout' value='true'>
                <button type='submit'>Logout</button>
              </form>";

        echo "</div>";
    } else {
        echo "Aucune entrée trouvée.";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Conn err: " . $e->getMessage();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
