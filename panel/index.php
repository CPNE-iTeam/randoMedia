<?php
session_start();
require_once __DIR__ . '/../api/utils/logger.php'; // Chemin vers logger

// Vérifier si l'utilisateur est déjà connecté
if (!isset($_SESSION['authenticated'])) {
    // Authentification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération des secrets
        $secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);
        
        // Récupérer les identifiants
        $username = $secrets['secrets'][4]['secret']; // ID 5 : username
        $password = $secrets['secrets'][5]['secret']; // ID 6 : password
        
        // Vérifier les identifiants
        if ($_POST['username'] === $username && $_POST['password'] === $password) {
            $_SESSION['authenticated'] = true; // Marquer comme authentifié
        } else {
            $error = "Identifiants incorrects.";
        }
    }
}

// Vérification de l'authentification
if (!isset($_SESSION['authenticated'])) {
    // Afficher le formulaire de connexion
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Modération</title>
    </head>
    <body>
        <h1>Connexion</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post" action="">
            <label for="username">Username :</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password :</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Se connecter">
        </form>
    </body>
    </html>
    <?php
    exit; // Arrêter le script ici si l'utilisateur n'est pas authentifié
}

// Si l'utilisateur est authentifié, afficher les données
$secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);

// Connexion à la base de données
$dbname = $secrets['secrets'][0]['secret']; // Nom de la BDD
$user = $secrets['secrets'][1]['secret']; // Utilisateur
$pass = $secrets['secrets'][2]['secret']; // Mot de passe BDD
$dsn = $secrets['secrets'][3]['secret']; // URL de la BDD

// Extraire le nom de l'hôte et le nom de la base de données
$host = parse_url($dsn)['host'];
$db = ltrim(parse_url($dsn)['path'], '/'); // Récupérer le nom de la base de données à partir du chemin

try {
    // Connexion à la base de données
    $conn = new mysqli($host, $user, $pass, $dbname); // Utiliser le nom de la base de données
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupération des entrées de la base de données
    $sql = "SELECT * FROM logs"; // Assurez-vous que la table s'appelle 'logs'
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<h1>Panel de Modération</h1>";
        echo "<table border='1'><tr><th>ID</th><th>File Name</th><th>Hash IP</th><th>Timestamp</th><th>Action</th></tr>";
        
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
                            <input type='hidden' name='fileName' value='$fileName'> <!-- Envoi uniquement du nom du fichier -->
                            <input type='submit' value='Delete'>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "Aucune entrée trouvée.";
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Erreur de connexion: " . $e->getMessage();
}
