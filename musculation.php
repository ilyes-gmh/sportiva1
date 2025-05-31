<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>musculation</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="musculation.css">

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
                    <a href="coach.php">Espace Coach</a>

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
            session_start();
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
















            <?php

            $user_id = $_SESSION['user_id'] ?? null;

            if ($user_id): ?>
                <a class="cc" href="compétition.php">Compétition</a>
                <a class="cc" href="cour.php">Séance</a>
            <?php endif; ?>
            <a href="#" class="user-profile" id="userIcon"><i class="fas fa-user-circle"></i></a>
        </nav>
    </header>

    <body>
        <div class="video-container">
            <video autoplay muted loop class="video-background">
                <source src="home97.mp4" type="video/mp4">
            </video>
        </div>


        <section class="presentation">
            <h2>Présentation de la musculation</h2>
            <p>
            <h3>La musculation est une discipline complète qui développe la force, l’endurance musculaire et la condition physique générale. Chez Sportiva, nous proposons un programme progressif accessible à tous, que vous soyez débutant ou athlète expérimenté.

                Encadrés par des coachs spécialisés, vous apprendrez à maîtriser les mouvements essentiels, à adapter vos charges et à structurer vos séances selon vos objectifs : prise de masse, sèche, renforcement ou préparation physique. Le parcours d’entraînement est structuré en trois étapes, avec la possibilité de participer à des compétitions internes ou externes après validation par nos entraîneurs.</h3>
            </p>
            <img src="mus1.png" alt="Athlète en pleine séance de musculation" class="presentation-image">
        </section>

        </section>

        <div class="container3">
            <div class="card-container">
                <div class="card">
                    <div class="overlay-text2">
                        <h4>"profitez de NOS COACHES Disponibles:</h4>


                    </div>
                    <img src="musc2.png" alt="Athletic Training">
                    <h1> Tison</h1>
                    <p>Améliorez votre technique et votre endurance grâce à un entraînement personnalisé en tennis.</p>
                   
                </div>

                <div class="card">
                    <img src="musc3.png" alt="Structured Training">
                    <h1> Bugha</h1>
                    <p>Perfectionnez votre jeu, affinez votre technique et boostez votre endurance au tennis.</p>
                    
                </div>

                <div class="card">
                    <img src="musc4.png" alt="AI Performance">
                    <h1> Malone</h1>
                    <p>Développez votre force, votre stratégie et votre rapidité avec un coaching expert en tennis.</p>
                
                </div>
            </div>
        </div>

        </div>


        <section class="actualites">
            <h2>Dans le même thème :</h2>
            <div class="actualites-container">
                <div class="actualite">
                    <img src="muscact1.png" alt="Nouveaux programmes de musculation">
                    <p>Lancement des nouveaux programmes de musculation 2025
                        Sportiva dévoile ses nouvelles routines ciblées pour la prise de masse, la sèche et le renforcement musculaire, conçues par des coachs certifiés.</p>
                </div>

                <div class="actualite">
                    <img src="muscact2.png" alt="Nutrition et entraînement">
                    <p>La nutrition au cœur de la performance musculaire
                        Une étude menée en mars 2025 confirme l’impact d’une alimentation personnalisée sur les gains musculaires et la récupération après l’effort.</p>
                </div>

                <div class="actualite">
                    <img src="muscact3.png" alt="Compétition de bodybuilding">
                    <p>Championnat national de bodybuilding 2025
                        Le 28 avril dernier, la compétition a réuni plus de 150 athlètes dans différentes catégories. Sportiva a soutenu trois finalistes dans la catégorie -80 kg.</p>
                </div>

                <div class="actualite">
                    <img src="muscact4.png" alt="Prévention des blessures en musculation">
                    <p>Prévenir les blessures en musculation : les bons gestes
                        Les experts de Sportiva rappellent l’importance d’un bon échauffement, d’une posture maîtrisée et d’un respect du rythme d’entraînement pour éviter les blessures.</p>
                </div>
            </div>
        </section>

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




    </body>

</html>