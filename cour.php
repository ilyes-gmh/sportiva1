<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'];

// 1. Get user's sport
$stmt = $conn->prepare("SELECT sport FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_sport = $stmt->fetchColumn();

// 2. Fetch sessions for that sport and get coach name from users table
$sql = "
    SELECT s.sport_type, s.time, s.location, u.nom, u.prenom
    FROM sessions s
    JOIN users u ON s.coach_id = u.id
    WHERE s.sport_type = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_sport]);
$sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>séance</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="cour.css">

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

    <section class="hero">
        <div class="hero-content">
            <h1>L'actualité sportive en temps réel</h1>
            <p>Suivez les dernières nouvelles et résultats sportifs</p>
        </div>
    </section>

    <!-- Cartes de cours -->

    <main class="main-content">
        <h2>Vos séances pour le sport : <?= htmlspecialchars($user_sport) ?></h2>

        <?php if (count($sessions) === 0): ?>
            <p>Aucune séance disponible pour votre sport actuellement.</p>
        <?php else: ?>
            <table class="session-table">
                <thead>
                    <tr>
                        <th>Sport</th>
                        <th>Coach</th>
                        <th>Heure</th>
                        <th>Lieu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td><?= htmlspecialchars($session['sport_type']) ?></td>
                            <td><?= htmlspecialchars($session['prenom'] . ' ' . $session['nom']) ?></td>
                            <td><?= htmlspecialchars($session['time']) ?></td>
                            <td><?= htmlspecialchars($session['location']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>


    <!-- Modale des cours -->


    <!-- Section articles -->
    <main class="main-content">
        <h2>Derniers articles</h2>
        <div class="articles-grid">
            <article class="article-card">
                <div class="article-image" style="background-image: url('https://example.com/sport1.jpg')"></div>
                <div class="article-content">
                    <span class="article-meta">Football • 15 mars 2024</span>
                    <h3>Le championnat en direct</h3>
                    <p>Suivez en direct les dernières performances...</p>
                </div>
            </article>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div>
                <h4>SPORTIVA</h4>
                <p>Le sport sans limites</p>
            </div>
            <div>
                <h4>Contact</h4>
                <p>contact@sportiva.com</p>
            </div>
        </div>
    </footer>

    <!-- Scripts JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userIcon = document.getElementById('userIcon');
            const popupOverlay = document.getElementById('popup-overlay');
            const closePopup = document.getElementById('close-popup');

            userIcon.addEventListener('click', function(e) {
                e.preventDefault();
                popupOverlay.style.display = 'flex';
            });

            closePopup.addEventListener('click', function() {
                popupOverlay.style.display = 'none';
            });

            popupOverlay.addEventListener('click', function(e) {
                if (e.target === popupOverlay) {
                    popupOverlay.style.display = 'none';
                }
            });

            document.querySelectorAll('.course-card').forEach(card => {
                card.addEventListener('click', () => {
                    const modal = document.getElementById('course-modal');
                    const dataset = card.dataset;

                    document.getElementById('modal-title').textContent = dataset.title;
                    document.querySelector('.modal-instructor').textContent = `par ${dataset.instructor}`;
                    document.getElementById('modal-duration').textContent = dataset.duration;
                    document.getElementById('modal-stars').innerHTML = dataset.stars;
                    document.getElementById('modal-avis').textContent = dataset.rating;
                    document.getElementById('modal-full-description').textContent = dataset.description;

                    if (dataset.video) {
                        document.getElementById('modal-video-iframe').src = dataset.video;
                        document.querySelector('.modal-video').style.display = 'block';
                    } else {
                        document.querySelector('.modal-video').style.display = 'none';
                    }

                    modal.style.display = 'flex';
                });
            });

            document.querySelector('.close-modal').addEventListener('click', () => {
                document.getElementById('course-modal').style.display = 'none';
            });

            document.getElementById('course-modal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('course-modal')) {
                    document.getElementById('course-modal').style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>