<?php
session_start();
require_once 'db.php'; // This should define your $conn PDO connection
$stmt = $conn->prepare("SELECT * FROM competitions WHERE date >= CURDATE() ORDER BY date ASC");
$stmt->execute();
$competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compétition</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="compétition.css">

</head>


<body>

    <div class="overlay" id="overlay"></div>

    <!-- Formulaire de connexion -->



    <header>
        <a href="home.php" class="logo">SPORTIVA</a>
        <nav class="navigation">
            <div class="dropdown">
                <a href="#">Mes Espaces ▾</a>
                <div class="dropdown-content">
                    <a href="client.php">Espace Personnel</a>
                   
                </div>
            </div>

            <div class="dropdown">
                <a href="#">Sports ▾</a>
                <div class="dropdown-content">
                    <a href="box.php">BOXE</a>
                    <a href="tennis.php">TENNIS</a>
                    <a href="musculation.php">MUSCULATION</a>
                    <a href="judo.php">judo</a>
                    <a href="#">Natation</a>
                    <a href="judo.php"> Athlétisme</a>
                </div>
            </div>


            <div class="popup-overlay" id="popup-overlay">
                <div class="popup">
                    <button class="close-btn" id="close-popup">&times;</button>
                    <h1>Login</h1>
                    <form id="login-form" action="login.php" method="POST">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required />

                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required />

                        <button class="login-btn" type="submit" name="login">Login</button>
                    </form>



                    <a href="s'inscrire.php" id="create-account" class="create-account">Create an Account</a>
                    <div id="g_id_onload"
                        data-client_id="238113643899-su9pt6ia846pge9itlv5gn16778nbj0a.apps.googleusercontent.com"
                        data-callback="handleCredentialResponse" data-auto_prompt="false">
                    </div>

                    <div class="g_id_signin" data-type="standard" data-size="large" data-theme="outline"
                        data-text="sign_in_with" data-shape="rectangular">
                    </div>

                </div>
            </div>




            <?php
            if (isset($_SESSION['notification'])) {
                $notif = $_SESSION['notification'];
                $type = htmlspecialchars($notif['type']);
                $message = htmlspecialchars($notif['message']);
                echo "<div class='notification $type' id='notification'>$message</div>";
                unset($_SESSION['notification']);
            }
            ?>


            <style>
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 25px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
                    font-family: Arial, sans-serif;
                    font-size: 16px;
                    z-index: 1000;
                    transition: opacity 1s ease;
                    opacity: 1;
                    margin-right: 40%;
                }

                /* Type-specific styles */
                .notification.success {
                    background-color: #28a745;
                    color: white;
                }

                .notification.error {
                    background-color: #dc3545;
                    color: white;
                }

                .notification.warning {
                    background-color: #ffc107;
                    color: black;
                }

                .notification.info {
                    background-color: #17a2b8;
                    color: white;
                }

                .notification.fade-out {
                    opacity: 0;
                }
            </style>

            <script>
                setTimeout(() => {
                    const notif = document.getElementById('notification');
                    if (notif) {
                        notif.classList.add('fade-out');
                        setTimeout(() => notif.remove(), 1000); // Remove after fade-out
                    }
                }, 5000);
            </script>


            <a class="cc" href="compétition.php">Compétition</a>
            <a class="cc" href="cour.php">Séance</a>
            <a href="#" class="user-profile" id="userIcon"><i class="fas fa-user-circle"></i></a>
        </nav>
    </header>
    <!-- Bannière principale -->
    <section class="banner">
        <div class="banner-content">
            <h2>Compétitions Sportives 2024</h2>
            <p>Découvrez les prochains événements et inscrivez-vous !</p>
        </div>
    </section>

    <!-- Section Prochaines Compétitions -->

    <section class="upcoming-events">
        <h2>Prochaines Compétitions</h2>
        <div class="event-cards">
            <?php if ($competitions): ?>
                <?php foreach ($competitions as $comp): ?>
                    <div class="event-card">
                        <h3><?= htmlspecialchars($comp['name']) ?></h3>
                        <p><i class="fas fa-calendar-alt"></i> <?= date('d F Y', strtotime($comp['date'])) ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($comp['location']) ?></p>
                        <p><i class="fas fa-futbol"></i> <?= htmlspecialchars($comp['sport_type']) ?></p>
                        <a href="registercom.php?id=<?= $comp['id'] ?>" class="btn">S'inscrire</a>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune compétition à venir.</p>
            <?php endif; ?>
        </div>
    </section>




    <!-- Pied de page -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact</h3>
                <p><i class="fas fa-envelope"></i> contact@sportiva.com</p>
                <p><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
            </div>
            <div class="footer-section">
                <h3>Réseaux Sociaux</h3>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="footer-section">
                <h3>Newsletter</h3>
                <form>
                    <input type="email" placeholder="Votre email">
                    <button type="submit">S'abonner</button>
                </form>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 Sportiva. Tous droits réservés.</p>
        </div>
    </footer>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userIcon = document.getElementById('userIcon');
        const popupOverlay = document.getElementById('popup-overlay');
        const closePopup = document.getElementById('close-popup');

        // Ouvrir le popup
        userIcon.addEventListener('click', function(e) {
            e.preventDefault();
            popupOverlay.style.display = 'flex';
        });

        // Fermer le popup
        closePopup.addEventListener('click', function() {
            popupOverlay.style.display = 'none';
        });

        // Fermer en cliquant à l'extérieur
        popupOverlay.addEventListener('click', function(e) {
            if (e.target === popupOverlay) {
                popupOverlay.style.display = 'none';
            }
        });
    });
</script>



</html>