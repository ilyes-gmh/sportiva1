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
    <title>Natation</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="natation.css">

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
                <source src="home16.mp4" type="video/mp4">
            </video>
        </div>

        <section class="presentation">
            <h2>Présentation de la natation</h2>
            <p>
            <h3>La natation est bien plus qu’un simple sport : c’est une harmonie entre souffle, technique et puissance ! 🏊‍♂️💧 Que ce soit pour
                glisser dans l’eau avec grâce, développer son endurance ou améliorer ses performances, chaque nage est un voyage vers le dépassement
                de soi. Idéale pour tous les âges, la natation renforce le corps sans choc articulaire et procure une sensation de liberté unique.
                Plongez dans un univers aquatique où chaque mouvement vous rapproche de vos objectifs ! 🌊🏅</h3>
            </p>
        </section>

        <img src="natt.png" alt="Skieur en pleine descente" class="presentation-image">

        </section>
        <div class="container3">
            <div class="card-container">
                <div class="card">
                    <div class="overlay-text2">
                        <h4>"Profitez de NOS COACHES Disponibles :</h4>
                    </div>
                    <img src="nat1.png" alt="Swimming Training">
                    <h1> KARIM</h1>
                    <p>Améliorez votre technique de nage et votre respiration avec un coaching individualisé en natation.</p>
                </div>

                <div class="card">
                    <img src="nat2.png" alt="Endurance Coach">
                    <h1> mahmoud</h1>
                    <p>Développez votre endurance et vos performances grâce à des séances de crawl, brasse et papillon sur mesure.</p>
                </div>

                <div class="card">
                    <img src="nat3.png" alt="Swim Coach">
                    <h1> RYAN</h1>
                    <p>Perfectionnez vos virages, départs et stratégies de course pour exceller en compétition.</p>
                </div>
            </div>
        </div>






        <section class="actualites">
            <h2>Dans le même thème :</h2>
            <div class="actualites-container">
                <div class="actualite">
                    <img src="nat4.png" alt="Championnats Africains">
                    <p>Championnats Africains 2025 : Le nageur algérien Sofiane Aït-Mouhoub décroche l’or au 100m nage libre avec un temps record,
                        positionnant l’Algérie parmi les meilleures nations aquatiques du continent.</p>
                </div>
                <div class="actualite">
                    <img src="nat5.png" alt="Écoles de natation">
                    <p>Programme "Tous à l’eau" : Le ministère de l'Éducation lance une initiative pour enseigner la natation dès le primaire, afin
                        de prévenir les noyades et détecter les futurs talents.</p>
                </div>
                <div class="actualite">
                    <img src="nat6.png" alt="Stages internationaux">
                    <p>Stage de perfectionnement en France : Cinq nageurs algériens ont été invités à s'entraîner à l'INSEP en vue des JO 2028,
                        sous la direction d’experts internationaux.</p>
                </div>
                <div class="actualite">
                    <img src="nat7.png" alt="Piscines modernes">
                    <p>Ouverture de la piscine olympique d’Oran : Une infrastructure ultra-moderne accueille désormais les compétitions nationales
                        et stages de haut niveau pour les jeunes nageurs prometteurs.</p>
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