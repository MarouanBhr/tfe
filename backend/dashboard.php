<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// Récupérer les infos de l'utilisateur
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Utilisateur';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

// Connexion à la base de données pour les statistiques
require_once 'config.php';

// Récupérer les informations complètes de l'utilisateur
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FitZone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <h1>💪 FitZone</h1>
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.html">Accueil</a></li>
                <li><a href="cours.html">Cours</a></li>
                <li><a href="tarifs.html">Tarifs</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="logout.php" class="btn-logout">Déconnexion</a></li>
            </ul>
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <!-- Dashboard Header -->
    <section class="dashboard-header">
        <div class="container">
            <h1>Bienvenue, <?php echo htmlspecialchars($user_name); ?> ! 👋</h1>
            <p>Voici votre espace personnel FitZone</p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <section class="dashboard-content">
        <div class="container">
            
            <!-- Informations Utilisateur -->
            <div class="dashboard-section">
                <h2>📋 Mes Informations</h2>
                <div class="info-card">
                    <div class="info-item">
                        <span class="info-label">👤 Nom d'utilisateur:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📧 Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Membre depuis:</span>
                        <span class="info-value"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></span>
                    </div>
                    <button class="btn-edit">✏️ Modifier mes informations</button>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="dashboard-section">
                <h2>📊 Mes Statistiques</h2>
                <div class="stats-dashboard-grid">
                    <div class="stat-dashboard-card">
                        <div class="stat-icon">🏋️</div>
                        <h3>12</h3>
                        <p>Séances ce mois</p>
                    </div>
                    <div class="stat-dashboard-card">
                        <div class="stat-icon">🔥</div>
                        <h3>3,450</h3>
                        <p>Calories brûlées</p>
                    </div>
                    <div class="stat-dashboard-card">
                        <div class="stat-icon">⏱️</div>
                        <h3>8h 30min</h3>
                        <p>Temps d'entraînement</p>
                    </div>
                    <div class="stat-dashboard-card">
                        <div class="stat-icon">🎯</div>
                        <h3>75%</h3>
                        <p>Objectifs atteints</p>
                    </div>
                </div>
            </div>

            <!-- Mes Cours -->
            <div class="dashboard-section">
                <h2>📅 Mes Cours Réservés</h2>
                <div class="cours-list">
                    <div class="cours-item">
                        <div class="cours-item-icon">🧘</div>
                        <div class="cours-item-info">
                            <h3>Yoga</h3>
                            <p>Lundi 18h00 - 19h00</p>
                            <span class="badge-success">Confirmé</span>
                        </div>
                        <button class="btn-cancel">Annuler</button>
                    </div>
                    
                    <div class="cours-item">
                        <div class="cours-item-icon">🏋️</div>
                        <div class="cours-item-info">
                            <h3>Musculation</h3>
                            <p>Mardi 17h00 - 18h30</p>
                            <span class="badge-success">Confirmé</span>
                        </div>
                        <button class="btn-cancel">Annuler</button>
                    </div>
                    
                    <div class="cours-item">
                        <div class="cours-item-icon">🥊</div>
                        <div class="cours-item-info">
                            <h3>Boxing</h3>
                            <p>Mercredi 20h00 - 21h00</p>
                            <span class="badge-warning">En attente</span>
                        </div>
                        <button class="btn-cancel">Annuler</button>
                    </div>
                </div>
                <div class="center-btn">
                    <a href="cours.html" class="btn-secondary">Réserver un nouveau cours</a>
                </div>
            </div>

            <!-- Mon Abonnement -->
            <div class="dashboard-section">
                <h2>💳 Mon Abonnement</h2>
                <div class="subscription-card">
                    <div class="subscription-header">
                        <h3>Formule Mensuelle</h3>
                        <span class="badge-active">Actif</span>
                    </div>
                    <div class="subscription-details">
                        <p><strong>💰 Prix:</strong> 50€ / mois</p>
                        <p><strong>📅 Prochaine facturation:</strong> 15 mars 2026</p>
                        <p><strong>✓ Avantages:</strong></p>
                        <ul>
                            <li>Accès illimité à la salle</li>
                            <li>Tous les cours collectifs</li>
                            <li>1 séance coaching offerte/mois</li>
                        </ul>
                    </div>
                    <div class="subscription-actions">
                        <button class="btn-upgrade">⬆️ Améliorer mon abonnement</button>
                        <button class="btn-manage">⚙️ Gérer mon abonnement</button>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="dashboard-section">
                <h2>⚡ Actions Rapides</h2>
                <div class="quick-actions">
                    <a href="cours.html" class="action-card">
                        <div class="action-icon">📅</div>
                        <h3>Réserver un cours</h3>
                    </a>
                    <a href="tarifs.html" class="action-card">
                        <div class="action-icon">💳</div>
                        <h3>Changer d'abonnement</h3>
                    </a>
                    <a href="contact.html" class="action-card">
                        <div class="action-icon">💬</div>
                        <h3>Contacter un coach</h3>
                    </a>
                    <a href="#" class="action-card">
                        <div class="action-icon">📊</div>
                        <h3>Voir mes progrès</h3>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>FitZone</h3>
                    <p>Votre partenaire fitness depuis 2026</p>
                </div>
                
                <div class="footer-section">
                    <h3>Navigation</h3>
                    <ul class="footer-links">
                        <li><a href="index.html">Accueil</a></li>
                        <li><a href="cours.html">Cours</a></li>
                        <li><a href="tarifs.html">Tarifs</a></li>
                        <li><a href="contact.html">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>📧 contact@fitzone.be</p>
                    <p>📞 +32 2 123 45 67</p>
                    <p>📍 Avenue Louise 123, 1050 Bruxelles</p>
                </div>
                
                <div class="footer-section">
                    <h3>Horaires</h3>
                    <p>Lun - Ven: 6h - 23h</p>
                    <p>Sam - Dim: 8h - 21h</p>
                    <p>Jours fériés: 9h - 18h</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 FitZone - Tous droits réservés | TFE Projet</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>