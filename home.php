<?php
require 'db.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$user = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACCEUIL</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="home.css">


</head>


<body>

    <div class="overlay" id="overlay"></div>

    <!-- Formulaire de connexion -->


    <header>
        <a href="home.php" class="logo">SPORTIVA</a>
        <nav class="navigation">
            <div class="dropdown">
                <?php
                $role = $_SESSION['role'] ?? '';   // 'client' or 'coach'
                ?>

                <nav class="dropdown">
                    <a href="#">Mes Espaces ▾</a>
                    <div class="dropdown-content">
                        <?php if ($role === 'client'): ?>
                            <a href="client.php">Espace Personnel</a>
                        <?php elseif ($role === 'coach'): ?>
                            <a href="coach.php">Espace Coach</a>
                        <?php endif; ?>
                    </div>
                </nav>


            </div>
            </div>

            <div class="dropdown">
                <a href="#">Sports ▾</a>
                <div class="dropdown-content">
                    <a href="box.php">BOXE</a>
                    <a href="tennis.php">TENNIS</a>
                    <a href="musculation.php">MUSCULATION</a>
                    <a href="judo.php">judo</a>
                    <a href="natation.php">Natation</a>
                    <a href="Athlétisme.php"> Athlétisme</a>
                </div>
            </div>

            <?php if (!$user_id): ?>
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

            <?php endif; ?>


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



            <?php if ($user_id): ?>
                <a class="cc" href="compétition.php">Compétition</a>
                <a class="cc" href="cour.php">Séance</a>
            <?php endif; ?>
            <a href="#" class="user-profile" id="userIcon">
                <img class="aa" src="user.png" alt="">
            </a>



            <?php if ($user_id): ?>
                <div id="userDropdown" class="dropdown-menu">
                    <p><strong><?= htmlspecialchars($user['prenom']) ?></strong></p>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                    <?php
                    $id   = (int) $user['id'];
                    if ($role === 'client') {
                        echo "<a href=\"client.php?id=$id\">View Profile</a>";
                    } elseif ($role === 'coach') {
                        echo "<a href=\"coach.php?id=$id\">View Profile</a>";
                    }
                    ?>
                    <a href="logout.php" style="color: red;">Logout</a>
                </div>
            <?php endif; ?>





            <script>
                const IS_LOGGED_IN = <?= $user ? 'true' : 'false' ?>;
            </script>
        </nav>
    </header>



    <div class="video-container">
        <video autoplay muted loop class="video-background">
            <source src="vid1.mp4" type="video/mp4">
        </video>
    </div>

    <div class="sa">

        <div class="image-container" style="background-color:#b7def9;height: 700vh;">

            <div class="container" style="position: flex; width: 100%;">
                <!-- Texte 2 (corrigé pour être bien positionné à droite) -->
                <div class="text2">
                    <h1>🏅 Gagnez Votre Médaille avec Sportiva !</h1>
                    <h4>
                        <p>Franchissez les trois étapes d'entraînement et participez à la compétition officielle pour
                            décrocher votre médaille :</p>
                        <p>🥇 Or – Champion de votre catégorie</p>
                        <p>🥈 Argent – Performance remarquable</p>
                        <p>🥉 Bronze – Détermination récompensée</p>
                        <p>Entraînez-vous, dépassez-vous et brillez ! 🚀🔥</p>
                    </h4>
                </div>

                <img src="home3.png" alt="troisième image" class="home3">

                <div class="text1">
                    <h1>Avec Sportiva : Profitez de Nos Coaches Experts !</h1>
                    <h4>
                        <p>Nous croyons que le coaching ne se limite pas à l'entraînement physique. Nos coachs
                            enseignent également la discipline, la gestion du stress et l'esprit d'équipe, éléments clés
                            de la réussite sportive et personnelle. Grâce à des sessions de feedback, des analyses de
                            performances et un accompagnement individuel, chaque athlète bénéficie d'un programme
                            optimisé pour ses objectifs.</p>
                    </h4>
                </div>

                <img src="home2.png" alt="Deuxième image" class="image-display1">

            </div>
            <div class="container2" style="position: flex; width: 100%; height: 15%;">
                <section class="features-section">
                    <h2>🏆 POURQUOI CHOISIR SPORTIVA ?</h2>
                    <div class="features-container">
                        <div class="feature-item">
                            <img src="icone1.png" alt="Entrepreneuriat">
                            <h3>progression et l'amélioration des performances.</h3>
                            <p>Sportiva accompagne les athlètes dans leur développement et leur réussite sportive grâce à un suivi personnalisé.</p>
                        </div>

                        <div class="feature-item">
                            <img src="icone3.png" alt="Diplôme reconnu">
                            <h3>UNE FORMATION DIPLÔMANTE RECONNUE</h3>
                            <p>L'académie offre une formation de qualité pour les sportifs et entraîneurs, avec des certifications reconnues.</p>
                        </div>

                        <div class="feature-item">
                            <img src="icone2.png" alt="Contenu additionnel">
                            <h3>DU CONTENU ADDITIONNEL COMPRIS</h3>
                            <p>Sportiva valorise l'esprit d'équipe, l'entraide et un encadrement professionnel pour chaque athlète.</p>
                        </div>

                        <div class="feature-item">
                            <img src="icone4.png" alt="Environnement 5 étoiles">
                            <h3>UN ENVIRONNEMENT 5 ÉTOILES</h3>
                            <p>L'objectif est d'amener les sportifs à la réussite en compétition et à l'atteinte de leurs objectifs.</p>
                        </div>
                    </div>
                </section>
            </div>



            <div class="container3">
                <div class="card-container">
                    <div class="card">
                        <div class="overlay-text2">
                            <h4>"Chez Sportiva, nous offrons une diversité de disciplines sportives encadrées par des coachs qualifiés.
                                Inscrivez-vous et
                                progressez dans :</h4>
                            <p>Sportiva offre un suivi moderne et personnalisé pour les athlétes !</p>

                        </div>
                        <img src="home4.png" alt="Athletic Training">
                        <h2>JUDO : Maîtrisez l’art du combat avec précision !
                        </h2>
                        <p>Améliorez votre technique, votre force et votre stratégie grâce à un entraînement personnalisé en judo.</p>
                        <a href="judo.php" class="btn">Explore →</a>
                    </div>

                    <div class="card">
                        <img src="home5.png" alt="Structured Training">
                        <h2>Tennis: Devenez un champion sur le court !</h2>
                        <p>Perfectionnez votre jeu, affinez votre technique et boostez votre endurance au tennis.</p>
                        <a href="tennis.php" class="btn">Explore →</a>
                    </div>

                    <div class="card">
                        <img src="home6.png" alt="AI Performance">
                        <h2>Box: Forgez votre esprit de combattant !</h2>
                        <p>Développez votre force, votre stratégie et votre rapidité avec un coaching expert en boxe.</p>
                        <a href="box.php" class="btn">Explore →</a>
                    </div>

                    <div class="card">
                        <img src="home7.png" alt="Structured Training">
                        <h2>Musculation: Construisez votre puissance physique !</h2>
                        <p>Développez votre force, sculptez votre corps et dépassez vos limites avec un programme de musculation adapté.</p>
                        <a href="musculation.php" class="btn">Explore→</a>
                    </div>
                    <div class="card">
                        <img src="home8.png" alt="Structured Training">
                        <h2>Natation: Dominez l’eau avec puissance et technique !</h2>
                        <p>Améliorez votre endurance, votre vitesse et votre maîtrise des styles de nage avec des entraînements ciblés.</p>
                        <a href="natation.php" class="btn">Explore →</a>
                    </div>


                    <div class="card">
                        <img src="home9.png" alt="Structured Training">
                        <h2>Athlétisme: Repoussez vos limites avec l’athlétisme</h2>
                        <p>Que vous soyez sprinteur, coureur de fond ou sauteur, développez votre vitesse, votre endurance et votre
                            explosivité
                            grâce à nos entraînements spécialisés.</p>
                        <a href="Athlétisme.php" class="btn">Explore →</a>
                    </div>
                </div>
            </div>

        </div>


        <div class="overlay-text">
            <h2>"Sportiva : De l’entraînement à la compétition, dépassez-vous !"</h2>
            <p>Votre guide vers l'excellence sportive !</p>

        </div>
        <div class="coach" id="coach">
            <div class="overlay">
                <h1>👨‍🏫 DEVENEZ un athlète SPORTIF DANS SPORTIVA<br>AU CŒUR D’UN CLUB</h1>
                <ul>
                    <li>Votre classe dans une salle de 2000m²</li>
                    <li>Des formateurs passionnés</li>
                    <li>Les plus grandes enseignes partenaires</li>
                    <li>Depuis 2016, 75 heureux diplômés</li>
                    <li>Un accompagnement sur mesure <em>(financement, structure...)</em></li>
                </ul>
                <div class="contact-buttons">
                    <button class="contact">📞 ALGER : +213 7 66 76 58 76</button>
                    <button class="contact">📞 ANNABA : +213 7 87 46 25 97</button>
                </div>

            </div>
        </div>
        <div class="container8" style="position: flex; width: 100%;">
            <!-- Texte 2 (corrigé pour être bien positionné à droite) -->
            <div class="text8">
                <h1>🏅 Gagnez Votre Médaille avec Sportiva !</h1>
                <h4>
                    <p>Franchissez les trois étapes d'entraînement et participez à la compétition officielle pour
                        décrocher votre médaille :</p>
                    <p>🥇 Or – Champion de votre catégorie</p>
                    <p>🥈 Argent – Performance remarquable</p>
                    <p>🥉 Bronze – Détermination récompensée</p>
                    <p>Entraînez-vous, dépassez-vous et brillez ! 🚀🔥</p>
                </h4>
            </div>

            <img src="home10.png" alt="troisième image" class="home10">
            <img src="home11.png" alt="troisième image" class="home11">
            <div class="parent-container">
                <div class="overlay-text3">
                    <h4>"N'hésitez pas à vous entraîner dans les meilleurs stades, adaptés à différents niveaux et disciplines
                        sportives !"</h4>
                </div>
            </div>



        </div>

        <!-- Votre contenu principal ici -->

        <footer>
            <div class="footer-container">
                <div class="footer-col">
                    <h3>📞 Contact</h3>
                    <ul>
                        <li><strong>Téléphone :</strong> +213 770 30 75 15</li>
                        <li><strong>Adresse :</strong> Annaba, à côté de l'hôtel Majestic,<br>
                            la première intersection à gauche, Annaba 23000</li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>🔗 Liens rapides</h3>
                    <ul>
                        <li><a href="#">Inscription</a></li>
                        <li><a href="#">Nous contacter</a></li>
                        <li><a href="#">Qui sommes-nous ?</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>ℹ️ Informations</h3>
                    <ul>
                        <li><a href="#">Nos partenaires</a></li>
                        <li><a href="#">Nos sièges régionaux</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2024 SPORTIVA - Tous droits réservés</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="mailto:contact@sportiva.com"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </footer>


        <script>
            document.querySelectorAll('.home10, .home11').forEach(img => {
                img.addEventListener('click', () => {
                    img.classList.toggle('rotated');
                });
            });



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









        <script src="home.js"></script>


</body>

</html>