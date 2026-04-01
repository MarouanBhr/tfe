<?php
session_start();

// Connexion à la base de données
require_once 'config.php';

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validation basique
    if (empty($email) || empty($password)) {
        header("Location: login.html?error=empty");
        exit();
    }
    
    // Vérifier si l'email existe dans la base de données
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Si l'utilisateur existe
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Vérifier le mot de passe
        if (password_verify($password, $user['password_hash'])) {
            // Mot de passe correct - créer la session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['logged_in'] = true;
            
            // Gérer "Se souvenir de moi"
            if (isset($_POST['remember']) && $_POST['remember'] == 'on') {
                // Cookie valable 30 jours
                setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), "/");
            }
            
            // Rediriger vers le dashboard
            header("Location: ../backend/dashboard.php");
            exit();
            
        } else {
            // Mot de passe incorrect
            header("Location: ../frontend/login.html?error=invalid");
            exit();
        }
        
    } else {
        // Email n'existe pas
        header("Location: ../frontend/login.html?error=notfound");
        exit();
    }
    
    $stmt->close();
    $conn->close();
    
} else {
    // Si quelqu'un accède directement à login.php sans POST
    header("Location: ../frontend/login.html");
    exit();
}
?>