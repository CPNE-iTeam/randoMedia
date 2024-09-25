<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../utils/logger.php'; // Chemin vers logger

// Vérifiez si l'utilisateur est authentifié
if (!isset($_SESSION['authenticated'])) {
    die("Non autorisé.");
}

// Récupérer les informations du formulaire
$id = $_POST['id'];
$fileName = $_POST['fileName'];

// Définir le chemin vers le fichier à supprimer
$filePath = __DIR__ . "/../../medias/" . $fileName; // Chemin vers le fichier à supprimer

// Récupérer les secrets pour la connexion à la base de données
$secrets = json_decode(file_get_contents('https://douxxu.lain.ch/prod/rando/secrets/'), true);
$dbname = $secrets['secrets'][0]['secret']; // Nom de la BDD
$user = $secrets['secrets'][1]['secret']; // Utilisateur
$pass = $secrets['secrets'][2]['secret']; // Mot de passe BDD
$dsn = $secrets['secrets'][3]['secret']; // URL de la BDD

// Extraire le nom de l'hôte et le nom de la base de données
$host = parse_url($dsn)['host'];

try {
    // Connexion à la base de données
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Suppression de l'entrée dans la base de données
    $sql = "DELETE FROM logs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Vérifier si l'entrée a été supprimée
    if ($stmt->affected_rows > 0) {
        // Supprimer le fichier
        if (file_exists($filePath)) {
            unlink($filePath); // Supprime le fichier
        } else {
            echo "Le fichier n'existe pas.";
        }
        echo "Fichier et entrée supprimés avec succès.";
    } else {
        echo "Aucune entrée trouvée avec cet ID.";
    }

    // Fermer la connexion à la base de données
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Erreur lors de la suppression : " . $e->getMessage();
}

// Rediriger ou afficher un message de succès
header("Location: ../../panel/"); // Redirige vers le panel de modération
exit;
