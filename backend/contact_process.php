<?php
// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validation
    if (empty($nom) || empty($email) || empty($sujet) || empty($message)) {
        header("Location: ../frontend/contact.html?error=empty");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../frontend/contact.html?error=email");
        exit();
    }
    
    // Email de destination
    $to = "contact@gymmaster.be";
    $subject = "Nouveau message de contact: " . $sujet;
    
    // Corps de l'email
    $email_body = "Nouveau message de contact depuis le site Gym Master\n\n";
    $email_body .= "Nom: " . $nom . "\n";
    $email_body .= "Email: " . $email . "\n";
    $email_body .= "Téléphone: " . $telephone . "\n";
    $email_body .= "Sujet: " . $sujet . "\n\n";
    $email_body .= "Message:\n" . $message . "\n";
    
    // Headers
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Envoyer l'email
    if (mail($to, $subject, $email_body, $headers)) {
        header("Location: ../frontend/contact.html?success=1");
    } else {
        header("Location: ../frontend/contact.html?error=send");
    }
    
    exit();
    
} else {
    header("Location: ../frontend/contact.html");
    exit();
}
?>