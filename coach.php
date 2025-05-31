<?php
session_start();
require_once 'db.php'; // This should define your $conn PDO connection

// Assuming the logged-in coach's ID is stored in session
$coach_id = $_SESSION['user_id'] ?? null;

$coach_nom = 'Nom inconnu';
$coach_sport = 'Sport inconnu';

if ($coach_id) {
    $stmt = $conn->prepare("SELECT nom, sport FROM users WHERE id = ?");
    $stmt->execute([$coach_id]);
    $coach = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coach) {
        $coach_nom = $coach['nom'];
        $coach_sport = $coach['sport'];
    }
}
?>
<?php
if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour gérer les compétitions.");
}
$coach_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_competition'])) {
    $name = trim($_POST['name'] ?? '');
    $date = $_POST['date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $sport_type = trim($_POST['sport_type'] ?? '');
    $min_niveau_required = trim($_POST['min_niveau_required'] ?? 0);

    if ($name && $date && $location && $sport_type && $min_sessions_required >= 0) {
        $stmt = $conn->prepare("INSERT INTO competitions (coach_id, name, date, location, sport_type, min_niveau_required) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$coach_id, $name, $date, $location, $sport_type, $min_niveau_required]);
        $_SESSION['notification'] = ['type' => 'success', 'message' => '✅ Compétition créée avec succès.'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['notification'] = ['type' => 'error', 'message' => '❌ Tous les champs sont requis et le nombre de sessions doit être >= 0.'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch competitions for this coach
$stmt = $conn->prepare("SELECT * FROM competitions WHERE coach_id = ? ORDER BY date DESC");
$stmt->execute([$coach_id]);
$competitions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Coach - Sportiva</title>
    <link rel="stylesheet" href="coach.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <div class="profile">
            <h3>Coach <?php echo htmlspecialchars($coach_nom); ?></h3>
            <p>Coach de <?php echo htmlspecialchars($coach_sport); ?></p>
        </div>
        <ul>

            <li><a href="#athletes"><i class="fas fa-users"></i> Mes Athlètes</a></li>
            <li><a href="#training-management"><i class="fas fa-dumbbell"></i> Gestion des Séances</a></li>
            <li><a href="#competition-management"><i class="fas fa-flag"></i> Gestion Compétitions</a></li>
            <li><a href="#notification-management"><i class="fas fa-flag"></i> Gestion notification</a></li>

        </ul>
    </div>

    <div class="content">
        <header>
            <h1 id="page-title">Tableau de Bord</h1>
            <div class="user-actions">

                <form action="logoutco.php" method="post" style="margin-left:auto;">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>

            </div>
        </header>



        <?php

        $coach_id = $_SESSION['user_id'] ?? 0;

        // الحصول على رياضة المدرب
        $stmt = $conn->prepare("SELECT sport FROM users WHERE id = :id AND role = 'coach'");
        $stmt->execute(['id' => $coach_id]);
        $coach = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coach) {
            $sport = $coach['sport'];

            $sql = "
            SELECT 
                u.id, 
                u.prenom, 
                u.nom, 
                u.email, 
                u.sport, 
                c.niveau,
                (SELECT COUNT(*) FROM client_sessions cs WHERE cs.client_id = u.id) AS sessions_count
            FROM users u
            LEFT JOIN clients c ON u.id = c.user_id
            WHERE u.role = 'client' AND u.sport = :sport
            ORDER BY u.nom
        ";


            $stmt = $conn->prepare($sql);
            $stmt->execute(['sport' => $sport]);
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $clients = [];
        }

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
            'athletisme' => [
                'niveau1' => 'Débutant',
                'niveau2' => 'Amateur',
                'niveau3' => 'Régional',
                'niveau4' => 'National',
                'niveau5' => 'Élite'
            ],

        ];

        ?>
        <!-- ===== same HTML as before, no “mine” check needed ===== -->
        <section id="athletes" class="hidden-section competition-request-status">
            <h2><i class="fas fa-users"></i> Mes Athlètes</h2>

            <div class="athletes-grid">
                <?php if (count($clients) > 0): ?>
                    <?php foreach ($clients as $cl): ?>
                        <div class="client-card">
                            <h3><?= htmlspecialchars($cl['prenom'] . ' ' . $cl['nom']) ?></h3>
                            <p><strong>Email:</strong> <?= htmlspecialchars($cl['email']) ?></p>
                            <p><strong>Sport:</strong> <?= htmlspecialchars($cl['sport']) ?></p>
                            <p><strong>Sessions:</strong> <span id="count-<?= $cl['id'] ?>"><?= $cl['sessions_count'] ?></span></p>

                            <p class="niveau-control">
                                <strong>Niveau:</strong>
                                <select id="niveau-<?= $cl['id'] ?>">
                                    <?php
                                    $sport = strtolower($cl['sport']);
                                    $levels = $sport_levels[$sport] ?? [];

                                    foreach ($levels as $value => $label):
                                        $selected = ($cl['niveau'] === $value) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $value ?>" <?= $selected ?>><?= htmlspecialchars($label) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button onclick="updateNiveau(<?= $cl['id'] ?>)">Save</button>
                            </p>

                            <button class="add_session" type="button"
                                onclick="addSession(<?= $cl['id'] ?>)">
                                Add session
                            </button>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun athlète pour votre spécialité.</p>
                <?php endif; ?>
            </div>
        </section>
        <script>
            function addSession(clientId) {
                fetch('record_session.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            client_id: clientId
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            const span = document.querySelector('#count-' + clientId);
                            if (span) span.textContent = parseInt(span.textContent, 10) + 1;
                        } else {
                            alert(data.error || 'Unknown error');
                        }
                    })
                    .catch(console.error);
            }
        </script>
        <script>
            function showNotification(message, type = 'success') {
                const existing = document.getElementById('notification');
                if (existing) existing.remove();

                const notif = document.createElement('div');
                notif.id = 'notification';
                notif.className = `notification ${type}`;
                notif.textContent = message;
                document.body.prepend(notif); // Or insert where you want

                setTimeout(() => {
                    notif.classList.add('fade-out');
                    setTimeout(() => notif.remove(), 1000);
                }, 5000);
            }

            function updateNiveau(clientId) {
                const niveau = document.querySelector('#niveau-' + clientId).value;

                fetch('update_niveau.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            client_id: clientId,
                            niveau: niveau
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            showNotification('Niveau mis à jour.', 'success');
                        } else {
                            showNotification(data.error || 'Erreur inconnue', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur réseau', 'error'));
            }
        </script>



        <?php
        if (isset($_SESSION['notification'])) {
            $notif = $_SESSION['notification'];
            $type = htmlspecialchars($notif['type']);
            $message = htmlspecialchars($notif['message']);
            echo "<div id='notification' class='notification $type'>$message</div>";
            unset($_SESSION['notification']);
        }
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const notif = document.getElementById('notification');
                if (notif) {
                    setTimeout(() => {
                        notif.classList.add('fade-out');
                        setTimeout(() => notif.remove(), 1000);
                    }, 5000);
                }
            });
        </script>

        <section id="training-management" class="hidden-section">
            <h2>Gestion des Séances</h2>
            <h2>Ajouter une Nouvelle Séance</h2>

            <form method="POST" action="add_session.php">
                <input type="date" name="date" required>
                <input type="time" name="time" required>
                <input type="text" name="location" placeholder="Lieu" required>
                <select name="sport_type" required>
                    <option value="">-- Choisir un sport --</option>
                    <option value="boxe">BOXE</option>
                    <option value="tennis">TENNIS</option>
                    <option value="musculation">MUSCULATION</option>
                    <option value="judo">judo</option>
                    <option value="natation">Natation</option>
                    <option value="athlétisme">Athlétisme</option>
                </select>
                <button type="submit" name="add_session">Ajouter Séance</button>
            </form>
            <?php
            $stmt = $conn->prepare("SELECT * FROM sessions WHERE coach_id = ?");
            $stmt->execute([$coach_id]);
            $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Lieu</th>
                        <th>Sport</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['date']) ?></td>
                            <td><?= htmlspecialchars($s['time']) ?></td>
                            <td><?= htmlspecialchars($s['location']) ?></td>
                            <td><?= htmlspecialchars($s['sport_type']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        </section>



        <section id="competition-management" class="hidden-section">
            <h2>Gestion des Compétitions</h2>
            <form id="competition-form" method="POST" action="">
                <input type="text" name="name" placeholder="Nom de la compétition" required>
                <input type="date" name="date" required>
                <input type="text" name="location" placeholder="Lieu" required>
                <select name="sport_type" required>
                    <option value="">-- Choisir un sport --</option>
                    <option value="boxe">BOXE</option>
                    <option value="tennis">TENNIS</option>
                    <option value="musculation">MUSCULATION</option>
                    <option value="judo">judo</option>
                    <option value="natation">Natation</option>
                    <option value="athlétisme">Athlétisme</option>
                </select>
                <select name="min_niveau_required" required>
                    <option value="">-- minimum niveau --</option>
                    <option value="niveau1">niveau1</option>
                    <option value="niveau2">niveau2</option>
                    <option value="niveau3">niveau3</option>
                    <option value="niveau4">niveau4</option>
                    <option value="niveau5">niveau5</option>
                </select>
                <button type="submit" name="add_competition">Créer</button>
            </form>


            <ul id="competition-list">
                <?php if ($competitions): ?>
                    <?php foreach ($competitions as $comp): ?>
                        <li>
                            <strong><?= htmlspecialchars($comp['name']) ?></strong> -
                            <?= htmlspecialchars($comp['date']) ?> -
                            <?= htmlspecialchars($comp['location']) ?> -
                            <?= htmlspecialchars($comp['sport_type']) ?>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>Aucune compétition pour le moment.</li>
                <?php endif; ?>
            </ul>
        </section>

        <section id="notification-management" class="hidden-section">
            <form method="POST" action="send_notification.php" class="notification-form card-style">
                <h2>Envoyer une Notification</h2>

                <label>
                    <input type="radio" name="target" value="sport" checked>
                    Tous les utilisateurs avec le même sport
                </label><br>

                <label>
                    <input type="radio" name="target" value="email">
                    Un utilisateur spécifique (par email)

                    <input type="email" name="email" placeholder="Email de l'utilisateur" class="input-email">
                </label>




                <textarea name="message" placeholder="Votre message ici..." required class="input-message"></textarea><br>

                <button type="submit" name="send_notification" class="btn-send">Envoyer</button>
            </form>


        </section>
    </div>



    </div>
    <script src="coach.js"></script>
</body>

</html>