<?php
session_start();
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';
    $sport = $_POST['sport'] ?? '';

    // Basic validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($motdepasse) || empty($sport)) {
        $message = "All fields are required.";
    } else {
        try {
            // Hash password
            $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);

            // Insert into users table as coach
            $stmt = $conn->prepare("INSERT INTO users (role, nom, prenom, email, motdepasse, sport) 
                                    VALUES ('coach', :nom, :prenom, :email, :motdepasse, :sport)");
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':motdepasse' => $hashedPassword,
                ':sport' => $sport,
            ]);

            $message = "Coach added successfully!";
            header("Location: admin.php#sport");
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Coach</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 40px;
        }

        .form-container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        input,
        select,
        button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>Add New Coach</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="nom">Last Name:</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">First Name:</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="motdepasse">Password:</label>
            <input type="password" id="motdepasse" name="motdepasse" required>

            <label for="sport">Sport:</label>
            <select id="sport" name="sport" required>
                <option value="">-- Select Sport --</option>
                <option value="judo">Judo</option>
                <option value="tennis">Tennis</option>
                <option value="boxe">Boxe</option>
                <option value="athletisme">Athlétisme</option>
                <option value="natation">Natation</option>
                <option value="musculation">Musculation</option>
            </select>

            <button type="submit">Add Coach</button>
        </form>
    </div>

</body>

</html>