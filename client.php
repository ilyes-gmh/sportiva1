<?php
require 'db.php'; // This must come first to define $conn
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Fetch user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    $niveau = '';
    $objectif = 'S’améliorer';

    if ($user && $user['role'] === 'client') {
        $stmt2 = $conn->prepare("
            SELECT u.sport, c.niveau 
            FROM users u 
            JOIN clients c ON u.id = c.user_id 
            WHERE u.id = ?
        ");
        $stmt2->execute([$user_id]);
        $clientData = $stmt2->fetch();

        $sport = strtolower(trim($clientData['sport'] ?? ''));
        $niveau = strtolower(trim($clientData['niveau'] ?? ''));
    } else {
        $niveau = 'Coach';
        $sport = '';
    }
}




$client_id = $_SESSION['user_id']; // or however you store it

// Count total nbattendance for this client
$stmt = $conn->prepare("SELECT SUM(nbattendance) FROM attendance WHERE client_id = ? AND status = 'present'");
$stmt->execute([$client_id]);
$total_attendance = (int) $stmt->fetchColumn();







// Fetch notifications for this client (from `notifications` table)


$stmt = $conn->prepare("SELECT message, date_sent FROM notifications WHERE receiver_id = ? ORDER BY date_sent DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>













<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="client.css">

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















            <a href="compétition.php">Compétition</a>
            <a href="cour.php">Séance</a>
            <a href="#" class="user-profile" id="userIcon"><i class="fas fa-user-circle"></i></a>
        </nav>
    </header>

    <main>

        <?php
        // Your sport levels map
        $sport_levels = [
            'boxe' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Gants légers',
                'niveau3' => 'Compétiteur',
                'niveau4' => 'Avancé',
                'niveau5' => 'Champion'
            ],
            'tennis' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Club',
                'niveau3' => 'Régional',
                'niveau4' => 'National',
                'niveau5' => 'Pro'
            ],
            'musculation' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Intermédiaire',
                'niveau3' => 'Confirmé',
                'niveau4' => 'Athlète',
                'niveau5' => 'Bodybuilder'
            ],
            'judo' => [
                'niveau1' => 'Ceinture blanche',
                'niveau2' => 'Ceinture jaune',
                'niveau3' => 'Ceinture verte',
                'niveau4' => 'Ceinture marron',
                'niveau5' => 'Ceinture noire'
            ],
            'natation' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Apprenti nageur',
                'niveau3' => 'Intermédiaire',
                'niveau4' => 'Compétiteur',
                'niveau5' => 'Maître nageur'
            ],
            'athlétisme' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Amateur',
                'niveau3' => 'Régional',
                'niveau4' => 'National',
                'niveau5' => 'Élite'
            ]
        ];

        // Assuming you have these two values from your data:
        $sport = $clientData['sport'] ?? '';     // e.g. 'boxe'
        $niveau = $clientData['niveau'] ?? '';   // e.g. 'niveau2'

        // Get the label or fallback to raw niveau code if not found
        $display_niveau = $sport_levels[$sport][$niveau] ?? $niveau;

        ?>




        <section class="personal-info-section card-style">
            <h2><i class="fas fa-user"></i> Informations Personnelles</h2>
            <div class="info-grid">
                <p><strong>Nom:</strong> <span id="athlete-name"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></span></p>
                <p><strong>Sport:</strong> <span id="athlete-sport"><?= htmlspecialchars($user['sport']) ?></span></p>
                <p><strong>Niveau:</strong> <span id="athlete-level"><?= htmlspecialchars($display_niveau) ?></span></p>
                <p><strong>Prochain Objectif:</strong> <span id="athlete-goal"><?= htmlspecialchars($objectif) ?></span></p>
            </div>
        </section>




        <div class="client-dashboard-grid">
            <!-- Calendrier Personnel -->





            <?php
            // Assuming $user_id is defined (e.g., from session)
            $user_id = $_SESSION['user_id'] ?? null;

            if ($user_id) {
                // Connect to DB (update with your DB info)
                $conn = new mysqli('localhost', 'root', '', 'sportiva');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch competition requests for this user
                $sql = "SELECT c.name, c.date, c.location, cr.status, cr.registered_at 
                    FROM competition_registrations cr
                    JOIN competitions c ON cr.competition_id = c.id
                    WHERE cr.user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>

                <section class="competition-request-status card-style">
                    <h2><i class="fas fa-clock"></i> Vos demandes de participation</h2>

                    <?php if ($result->num_rows > 0): ?>
                        <table class="competition-status-table">
                            <thead>
                                <tr>
                                    <th>Compétition</th>
                                    <th>Date</th>
                                    <th>Lieu</th>
                                    <th>Statut</th>
                                    <th>Demandée le</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                        <td><?= htmlspecialchars($row['location']) ?></td>
                                        <td>
                                            <?php
                                            switch ($row['status']) {
                                                case 'approved':
                                                    echo "<span class='status-approved'>Approuvée</span>";
                                                    break;
                                                case 'rejected':
                                                    echo "<span class='status-rejected'>Rejetée</span>";
                                                    break;
                                                default:
                                                    echo "<span class='status-pending'>En attente</span>";
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['registered_at']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Vous n'avez encore fait aucune demande de participation.</p>
                    <?php endif; ?>
                </section>

            <?php
                $stmt->close();
                $conn->close();
            }
            ?>





            <!-- Notifications -->
            <section class="notifications-section card-style">
                <h2><i class="fas fa-bell"></i> Notifications</h2>
                <div class="notification-list">
                    <?php if (count($notifications) === 0): ?>
                        <div class="notification-item">
                            <p>Aucune notification disponible.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $note): ?>
                            <div class="notification-item">
                                <p><?= htmlspecialchars($note['message']) ?></p>
                                <small><?= htmlspecialchars($note['date_sent']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        </div>

        <!-- Statistiques de Performance (IA) -->


    </main>

    <footer>
        <p>© 2023 Sportiva - Tous droits réservés</p>
        <div class="footer-links">
            <a href="#">Mentions légales</a>
            <a href="#">Contact</a>
            <a href="#">CGU</a>
        </div>
    </footer>

    <script src="client.js"></script>
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

</body>

</html>