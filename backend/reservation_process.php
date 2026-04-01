/* <?php /*
session_start();

// Connexion à la base de données
require_once 'config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer les données
    $cours = htmlspecialchars(trim($_POST['cours']));
    $date = htmlspecialchars(trim($_POST['date']));
    $horaire = htmlspecialchars(trim($_POST['horaire']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $niveau = isset($_POST['niveau']) ? htmlspecialchars(trim($_POST['niveau'])) : 'debutant';
    $commentaire = isset($_POST['commentaire']) ? htmlspecialchars(trim($_POST['commentaire'])) : '';
    
    // Validation
    if (empty($cours) || empty($date) || empty($horaire) || empty($nom) || empty($email) || empty($telephone)) {
        header("Location: reservation.html?error=empty");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: reservation.html?error=email");
        exit();
    }
    
    // Créer la table si elle n'existe pas
    $sql = "CREATE TABLE IF NOT EXISTS reservations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cours VARCHAR(100) NOT NULL,
        date_cours DATE NOT NULL,
        horaire VARCHAR(100) NOT NULL,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL,
        telephone VARCHAR(20) NOT NULL,
        niveau VARCHAR(50),
        commentaire TEXT,
        date_reservation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);
    
    // Insérer la réservation
    $sql = "INSERT INTO reservations (cours, date_cours, horaire, nom, email, telephone, niveau, commentaire) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $cours, $date, $horaire, $nom, $email, $telephone, $niveau, $commentaire);
    
    if ($stmt->execute()) {
        // Envoyer un email de confirmation (optionnel)
        $to = $email;
        $subject = "Confirmation de réservation - Gym Master";
        $message = "Bonjour $nom,\n\nVotre réservation a été confirmée :\n\nCours : $cours\nDate : $date\nHoraire : $horaire\n\nÀ bientôt chez Gym Master !\n\nL'équipe Gym Master";
        $headers = "From: contact@gymmaster.be";
        @mail($to, $subject, $message, $headers);
        
        // CORRECTION ICI : Rediriger vers confirmation.html
        header("Location: confirmation.html");
        exit();
    } else {
        header("Location: reservation.html?error=db");
        exit();
    }
    
    $stmt->close();
    $conn->close();
    
} else {
    header("Location: reservation.html");
    exit();
}
?>
*/

<?php
session_start();

// TEST DE REDIRECTION DIRECTE
echo "Script PHP exécuté !<br>";
echo "Redirection dans 2 secondes...<br>";
sleep(2);
header("Location: ../frontend/confirmation.html");
exit();
?>