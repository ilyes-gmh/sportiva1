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
    <title>BOX</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="box.css">

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
                    <a href="judo.php"> Athlétisme</a>
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
                <source src="home2.mp4" type="video/mp4">
            </video>
        </div>


        <section class="presentation">
            <h2>Présentation du boxe</h2>
            <p>
            <h3>La boxe, c’est bien plus qu’un sport : c’est un combat intense entre force, stratégie et détermination ! 🥊🔥 Entre
                puissance, vitesse et maîtrise de soi, chaque échange est une invitation à repousser ses limites tout en canalisant son
                énergie. Que vous soyez novice en quête de dépassement ou passionné cherchant à perfectionner vos techniques, la boxe
                vous promet adrénaline et progrès constants. Enfilez vos gants, montez sur le ring et vivez l’expérience du combat avec
                intensité et passion ! 💥🏆</h3>
            </p>
            <img src="box1.png" alt="Skieur en pleine descente" class="presentation-image">

        </section>

        <div class="container3">
            <div class="card-container">
                <div class="card">
                    <div class="overlay-text2">
                        <h4>"profitez de NOS COACHES Disponibles:</h4>


                    </div>
                    <img src="box2.png" alt="Athletic Training">
                    <h1> MIKE</h1>
                    <p>Améliorez votre technique et votre endurance grâce à un entraînement personnalisé en boxe.</p>
                    
                </div>

                <div class="card">
                    <img src="box3.png" alt="Structured Training">
                    <h1> JOAN</h1>
                    <p>Perfectionnez votre jeu, affinez votre technique et boostez votre endurance au boxe.</p>
                    
                </div>

                <div class="card">
                    <img src="box4.png" alt="AI Performance">
                    <h1> ALICE</h1>
                    <p>Développez votre force, votre stratégie et votre rapidité avec un coaching expert en boxe.</p>
                   
                </div>
            </div>
        </div>

        </div>


        <section class="actualites">
            <h2>Dans le même thème :</h2>
            <div class="actualites-container">
                <div class="actualite">
                    <img src="box5.png" alt="Récupération active">
                    <p>Imane Khelif : Parcours et Polémiques
                        succès Olympique et Controverses : La boxeuse algérienne Imane Khelif a marqué les esprits lors des Jeux Olympiques de
                        Paris 2024 en remportant la médaille d'or dans la catégorie des moins de 66 kg. Cependant, sa victoire a été entachée
                        par des rumeurs infondées concernant son genre, initiées lors des championnats du monde de boxe en 2023.</p>
                </div>
                <div class="actualite">
                    <img src="box6.png" alt="Étirements en musculation">
                    <p>Développement de la Boxe en Algérie:
                        Engouement Féminin pour la Boxe : Le succès d'Imane Khelif a inspiré de nombreuses jeunes filles en Algérie à
                        s'intéresser à la boxe. Depuis sa victoire olympique, plusieurs clubs ont constaté une augmentation significative du
                        nombre d'inscriptions féminines, témoignant de l'impact positif de son parcours sur la jeunesse algérienne</p>
                </div>
                <div class="actualite">
                    <img src="box7.png" alt="Récupération musculaire efficace">
                    <p>Événements Internationaux et Initiatives
                        Réhabilitation par la Boxe en France : En Corrèze, l'association Concienta, fondée par l'ancien boxeur Gilles Martin,
                        utilise la boxe comme outil de réinsertion pour d'anciens détenus. En combinant entraînements sur le ring et activités
                        communautaires, cette initiative vise à faciliter leur réintégration sociale et à réduire le taux de récidive.</p>
                </div>
                <div class="actualite">
                    <img src="box8.jpg" alt="Progression en musculation">
                    <p>ournoi National des Novices et Coupe de France 2025 : La Fédération Française de Boxe organise deux nouveaux événements
                        majeurs du 19 au 21 avril 2025 au Parc d’Olhain (62620). Ces compétitions visent à promouvoir la boxe amateur en France.
                        ​</p>
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