<?php
session_start();

// Connexion à la base de données
require_once 'config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Tableau pour stocker les erreurs
    $errors = [];
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires";
    }
    
    if (strlen($username) < 3) {
        $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }
    
    // Vérifier si l'email existe déjà
    $sql = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Cet email est déjà utilisé";
    }
    $stmt->close();
    
    // Vérifier si le nom d'utilisateur existe déjà
    $sql = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = "Ce nom d'utilisateur est déjà pris";
    }
    $stmt->close();
    
    // Si pas d'erreurs, créer le compte
    if (empty($errors)) {
        
        // Hasher le mot de passe
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insérer dans la base de données
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $password_hash);
        
        if ($stmt->execute()) {
            // Inscription réussie
            $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            header("Location: ../frontend/login.html");
            exit();
        } else {
            $errors[] = "Erreur lors de l'inscription : " . $stmt->error;
        }
        
        $stmt->close();
    }
    
    // Si erreurs, rediriger avec message
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        header("Location: ../frontend/registrer.html?error=" . urlencode(implode(", ", $errors)));
        exit();
    }
    
    $conn->close();
    
} else {
    // Si quelqu'un accède directement à register.php sans POST
    header("Location: ../frontend/registrer.html");
    exit();
}
?>