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
    <title>Athlétisme</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="Athlétisme.css">

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
                <i class="fas fa-user-circle"></i>
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

    <body>
        <div class="video-container">
            <video autoplay muted loop class="video-background">
                <source src="home15.mp4" type="video/mp4">
            </video>
        </div>


        <section class="presentation">
            <h2>Présentation de l’athlétisme</h2>
            <p>
            <h3>L’athlétisme, c’est l’essence même du sport : courir, sauter, lancer… une discipline complète qui allie force, vitesse,
                agilité et endurance ! 🏃‍♂️🏅 Qu’il s’agisse de sprinter sur la piste, franchir des haies ou battre son record au lancer du poids,
                chaque épreuve est un défi personnel. Accessible à tous, l’athlétisme développe le corps et l’esprit à travers la rigueur, la
                persévérance et la précision. En piste pour dépasser vos limites et faire rayonner vos performances ! 💪🔥</h3>
            </p>
        </section>

        <img src="athlétisme1.png" alt="Skieur en pleine descente" class="presentation-image">

        </section>

        <div class="container3">
            <div class="card-container">
                <div class="card">
                    <div class="overlay-text2">
                        <h4>"Profitez de NOS COACHES Disponibles :</h4>
                    </div>
                    <img src="athl1.png" alt="Athletic Training">
                    <h1> AMINE</h1>
                    <p>Optimisez votre vitesse et votre posture avec un plan personnalisé en course à pied et sprint.</p>
                </div>

                <div class="card">
                    <img src="athl2.png" alt="Structured Training">
                    <h1> LINA</h1>
                    <p>Maîtrisez vos sauts et lancers grâce à un coaching expert en athlétisme polyvalent.</p>
                </div>

                <div class="card">
                    <img src="athl3.png" alt="AI Performance">
                    <h1> YASSINE</h1>
                    <p>Améliorez votre endurance et technique avec des entraînements spécifiques à chaque discipline d’athlétisme.</p>
                </div>
            </div>
        </div>





        <section class="actualites">
            <h2>Dans le même thème :</h2>
            <div class="actualites-container">
                <div class="actualite">
                    <img src="athle4.png" alt="Records récents">
                    <p>Jeux Africains 2025 : L’athlète algérienne Sara Boudiaf décroche l’or au 800m avec un nouveau record personnel, illustrant
                        la montée en puissance de l’athlétisme féminin en Afrique du Nord.</p>
                </div>
                <div class="actualite">
                    <img src="athle5.png" alt="Initiatives locales">
                    <p>Promotion de l’athlétisme scolaire : Le ministère de la jeunesse et des sports lance un programme national visant à
                        introduire l’athlétisme dans les écoles primaires à travers le pays, avec des kits sportifs adaptés et des coachs spécialisés.</p>
                </div>
                <div class="actualite">
                    <img src="athle6.png" alt="Événements internationaux">
                    <p>Meeting international d’Alger 2025 : Cet événement accueillera des athlètes de plus de 20 pays, avec un focus sur les jeunes
                        talents U18 et U20 pour préparer les Jeux Olympiques de la Jeunesse.</p>
                </div>
                <div class="actualite">
                    <img src="athle7.png" alt="Entraînement de haut niveau">
                    <p>Centre d’excellence d’athlétisme à Oran : Une nouvelle infrastructure dédiée au développement des athlètes élite
                        verra le jour en 2025, équipée de pistes, salles de musculation et technologies d’analyse biomécanique.</p>
                </div>
            </div>
        </section>


        <section id="resultats">
            <h2>Résultats Récents</h2>
            <table>
                <tr>
                    <th>Épreuve</th>
                    <th>Lieu</th>
                    <th>Vainqueur</th>
                </tr>
                <tr>
                    <td>Descente Hommes</td>
                    <td>Kitzbühel</td>
                    <td>Dominik Paris</td>
                </tr>
                <tr>
                    <td>Slalom Femmes</td>
                    <td>Schladming</td>
                    <td>Mikaela Shiffrin</td>
                </tr>
            </table>
        </section>

        <section id="calendrier">
            <h2>Prochaines Épreuves</h2>
            <ul class="events-list">
                <li>
                    <span class="date">15 Janv. 2024</span>
                    <span class="event">Descente - Wengen</span>
                </li>
                <li>
                    <span class="date">20 Janv. 2024</span>
                    <span class="event">Slalom Géant - Adelboden</span>
                </li>
            </ul>
        </section>
        <footer>
            <div class="footer-container">
                <div class="footer-col">
                    <h3>📞 Contact</h3>
                    <ul>
                        <li><strong>Téléphone :</strong> +213 770 30 75 15</li>
                        <li><strong>Adresse :</strong> Annaba, à côté de l'hôtel Majestic,<br>la première intersection à
                            gauche,
                            Annaba 23000</li>
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
                    <a href="https://www.facebook.com/" target="_blank" aria-label="Facebook"><i
                            class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/" target="_blank" aria-label="Instagram"><i
                            class="fab fa-instagram"></i></a>
                    <a href="mailto:contact@sportiva.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </footer>
        <script src="home.js"></script>

    </body>

</html>