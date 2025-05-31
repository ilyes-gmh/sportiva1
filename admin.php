<?php
session_start();
require_once 'db.php';

// Fetch competition requests (already correct using PDO)
$stmt = $conn->query("
    SELECT r.id, u.prenom, c.name AS competition, r.status 
    FROM competition_registrations r
    JOIN users u ON r.user_id = u.id
    JOIN competitions c ON r.competition_id = c.id
    ORDER BY r.registered_at DESC
");
$requests = $stmt->fetchAll();

// Fetch total users
$stmt = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $stmt->fetch()['total'];

// Fetch active courses
$stmt = $conn->query("SELECT COUNT(*) AS total FROM sessions ");
$activeCourses = $stmt->fetch()['total'];



// Fetch new sign-ups this month
$stmt = $conn->query("
    SELECT COUNT(*) AS total 
    FROM users 
    WHERE MONTH(date_inscription) = MONTH(CURRENT_DATE()) 
      AND YEAR(date_inscription) = YEAR(CURRENT_DATE())
");

$monthSignups = $stmt->fetch()['total'];

// Fetch pending approvals
$stmt = $conn->query("SELECT COUNT(*) AS total FROM competition_registrations WHERE status = 'pending'");
$pendingApprovals = $stmt->fetch()['total'];



$stmt = $conn->query("SELECT id, prenom, email,nom, role, sport FROM users WHERE role = 'client'");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt = $conn->query("SELECT id, nom, prenom, email, role, sport FROM users WHERE role = 'coach'");
$coaches = $stmt->fetchAll(PDO::FETCH_ASSOC);



$notificationStmt = $conn->query("
    SELECT message, date_sent 
    FROM notifications 
    ORDER BY date_sent DESC
");
$notifications = $notificationStmt->fetchAll(PDO::FETCH_ASSOC);


?>













<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sportiva</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <h2>Sportiva Admin</h2>
            </div>
            <nav class="menu">
                <ul>
                    <li class="active"><a href="#dashboard">Dashboard</a></li>
                    <li><a href="#users">User Management</a></li>
                    <li><a href="#sports">Coach Management</a></li>
                    <li><a href="#Compétition">Compétition Management</a></li>
                    <li><a href="#notifications">Notifications</a></li>
                   
                    <li><a href="#logout">Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
           
            <!-- Dashboard Section -->
            <section id="dashboard" class="content-section">
                    <h1>Dashboard</h1>
                    <div class="dashboard-cards">
                        <div class="card">
                            <h3>Total Users</h3>
                            <p class="count"><?php echo $totalUsers; ?></p>
                            <p>Athletes & Coaches</p>
                        </div>
                        <div class="card">
                            <h3>Active Courses</h3>
                            <p class="count"><?php echo $activeCourses; ?></p>
                            <p>Across all sports</p>
                        </div>
                        <div class="card">
                            <h3>New Sign-ups (Month)</h3>
                            <p class="count"><?php echo $monthSignups; ?></p>
                            <p>Recent registrations</p>
                        </div>
                        <div class="card">
                            <h3>Pending Approvals</h3>
                            <p class="count"><?php echo $pendingApprovals; ?></p>
                            <p>Competition requests</p>
                        </div>
                    </div>
                </section>



            <!-- User Management Section -->
            <section id="users" class="content-section" style="display:none;">
                <h1>User Management</h1>
                <a href="add_user.php" class="btn" style="margin-bottom: 15px;">Add New User</a>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Sport</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
                                        <td><?= htmlspecialchars($user['nom']) ?></td>
                                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['role']) ?></td>
                                        <td><?= htmlspecialchars($user['sport']) ?></td>
                                    
                                        <td class="action-buttons">
                                        <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline-block;">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn-delete">Delete</button>
                                        </form>

                                        <!-- زر التغيير -->
                                        <button class="btn-edit" onclick="toggleEditForm('editFormUser<?= $user['id'] ?>')">Change</button>

                                        <!-- نموذج التعديل المخفي -->
                                        <form method="POST" action="update_user.php" id="editFormUser<?= $user['id'] ?>" class="edit-form" style="display:none; margin-top:10px;">
                                                   
                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                                                    <!-- Full Name -->
                                                    <!-- First Name -->
                                                <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required placeholder="First Name">

                                                <!-- Last Name -->
                                                <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required placeholder="Last Name">


                                                    <!-- باقي الحقول -->
                                                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                                    <input type="text" name="role" value="<?= htmlspecialchars($user['role']) ?>" required>
                                                    <input type="text" name="sport" value="<?= htmlspecialchars($user['sport']) ?>">
                                                    <button type="submit">Save</button>
                                                </form>

                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                    </table>
                </div>
            </section>


            <!-- Sports Management Section -->
            <section id="sports" class="content-section" style="display:none;">
                    <h1>Coach Management</h1>
                    <button class="btn" style="margin-bottom: 15px;" onclick="window.location.href='add_coach.php'">Add New Coach</button>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Sport</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($coaches as $coach): ?>
                                <tr>
                                    <td><?= htmlspecialchars($coach['id']) ?></td>
                                    <td><?= htmlspecialchars($coach['prenom']) ?></td>
                                    <td><?= htmlspecialchars($coach['nom']) ?></td>
                                    <td><?= htmlspecialchars($coach['email']) ?></td>
                                    <td><?= htmlspecialchars($coach['role']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($coach['sport'])) ?></td>
                                    <td class="action-buttons">
                                        <!-- زر حذف -->
                                        <button class="btn-delete" onclick="if(confirm('Are you sure to delete this coach?')) window.location.href='delete_coach.php?id=<?= $coach['id'] ?>'">Delete</button>

                                        <!-- زر تعديل -->
                                        <button class="btn-edit" onclick="document.getElementById('editFormCoach<?= $coach['id'] ?>').style.display='block';">Change</button>
                                    </td>
                                </tr>

                                <!-- نموذج تعديل المدرب -->

                                <tr>

                                <td colspan="6">
                                    <form method="POST" action="update_coach.php" id="editFormCoach<?= $coach['id'] ?>" class="edit-form" style="display:none; margin-top:10px;">
                                        <input type="hidden" name="id" value="<?= $coach['id'] ?>">

                                        <!-- الاسم الأول -->
                                        <input type="text" name="prenom" value="<?= htmlspecialchars($coach['prenom']) ?>" required placeholder="First Name">

                                        <!-- الاسم الأخير -->
                                        <input type="text" name="nom" value="<?= htmlspecialchars($coach['nom']) ?>" required placeholder="Last Name">

                                        <!-- البريد -->
                                        <input type="email" name="email" value="<?= htmlspecialchars($coach['email']) ?>" required>

                                        <!-- الدور -->
                                        <input type="text" name="role" value="<?= htmlspecialchars($coach['role']) ?>" required>

                                        <!-- الرياضة -->
                                        <input type="text" name="sport" value="<?= htmlspecialchars($coach['sport']) ?>">

                                        <!-- زر الحفظ -->
                                        <button type="submit">Save</button>
                                    </form>
                                </td>
                            </tr>
                                <?php endforeach; ?>

                                <?php if (empty($coaches)): ?>
                                <tr><td colspan="6" style="text-align:center;">No coaches found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>










            <!-- Course Management Section -->
            <section id="Compétition" class="content-section" style="display:none;">

                <h2>Demandes d'inscription</h2>
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Compétition</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['prenom']) ?></td>
                                <td><?= htmlspecialchars($r['competition']) ?></td>
                                <td><?= $r['status'] ?></td>
                                <td>
                                    <?php if ($r['status'] === 'pending'): ?>
                                        <a href="approve.php?id=<?= $r['id'] ?>" class="btn-approve">✅ Accepter</a>
                                        <a href="reject.php?id=<?= $r['id'] ?>" class="btn-reject">❌ Rejeter</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </section>



            <!-- Notifications Section -->
            <section id="notifications" class="content-section" style="display:none;">
                    <h1>Notifications</h1>
                    <div class="notification-list">
                        <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="notification-item">
                                    <p><strong>Notification:</strong> <?= htmlspecialchars($notif['message']) ?></p>
                                    <span class="timestamp"><?= date('F d Y, h:i A', strtotime($notif['date_sent'])) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No notifications found.</p>
                        <?php endif; ?>
                    </div>
                </section>


      

        </main>



    </div>
    <script>
                document.querySelectorAll('.menu a').forEach(item => {
                    item.addEventListener('click', event => {
                        const href = event.target.getAttribute('href');

                        // If logout, redirect to home page
                        if (href === '#logout') {
                            window.location.href = 'home.php'; // Change 'index.php' to your actual home page
                            return;
                        }

                        // Prevent default anchor click behavior
                        event.preventDefault();

                        // Hide all content sections
                        document.querySelectorAll('.content-section').forEach(section => {
                            section.style.display = 'none';
                        });

                        // Show the target section
                        const targetId = href.substring(1);
                        const targetSection = document.getElementById(targetId);
                        if (targetSection) {
                            targetSection.style.display = 'block';
                        }

                        // Update active class in menu
                        document.querySelectorAll('.menu li').forEach(li => li.classList.remove('active'));
                        event.target.closest('li').classList.add('active');
                    });
                });

                // Show dashboard by default
                if (document.getElementById('dashboard')) {
                    document.getElementById('dashboard').style.display = 'block';
                }








             
                    function toggleEditForm(formId) {
                        var form = document.getElementById(formId);
                        if (form.style.display === 'none') {
                            form.style.display = 'block';
                        } else {
                            form.style.display = 'none';
                        }
                    }














    </script>
</body>

</html>