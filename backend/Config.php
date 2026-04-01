<?php
// Paramètres de connexion à la base de données
$host = 'localhost';
$dbname = 'gym_master';     // ← Changé ici
$username = 'root';
$password = '';

// Créer la connexion
$conn = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Définir l'encodage
$conn->set_charset("utf8mb4");
?>