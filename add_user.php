<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);
    $sport = $_POST['sport'];
    $age = $_POST['age'];
    $niveau = $_POST['niveau'];
    $role = 'client';

    try {
        // Insert into users
        $stmt = $conn->prepare("INSERT INTO users (role, nom, prenom, email, motdepasse, sport) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$role, $nom, $prenom, $email, $motdepasse, $sport]);
        
        $user_id = $conn->lastInsertId();

        // Insert into clients
        $stmt2 = $conn->prepare("INSERT INTO clients (user_id, age, niveau, total_attendances) VALUES (?, ?, ?, 0)");
        $stmt2->execute([$user_id, $age, $niveau]);

        header("Location: admin.php#users");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Ajouter un nouveau client</title>
<style>
  /* Same clean CSS as before but added styles for select and number inputs */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f7f8;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
  }
  .container {
    background: white;
    padding: 2.5rem 3rem;
    margin: 3rem 1rem;
    border-radius: 8px;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
    max-width: 420px;
    width: 100%;
  }
  h1 {
    text-align: center;
    margin-bottom: 1.8rem;
    color: #222;
  }
  form label {
    display: block;
    margin-bottom: 0.4rem;
    font-weight: 600;
    color: #555;
  }
  form input, form select {
    width: 100%;
    padding: 0.6rem 0.9rem;
    margin-bottom: 1.3rem;
    border: 1.8px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }
  form input:focus, form select:focus {
    border-color: #007bff;
    outline: none;
  }
  button[type="submit"] {
    width: 100%;
    padding: 0.7rem;
    font-size: 1.1rem;
    font-weight: 600;
    background-color: #007bff;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  button[type="submit"]:hover {
    background-color: #0056b3;
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Ajouter un nouveau client</h1>
    <form method="POST" action="">
      <label for="nom">Nom</label>
      <input type="text" id="nom" name="nom" required />

      <label for="prenom">Prénom</label>
      <input type="text" id="prenom" name="prenom" required />

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required />

      <label for="motdepasse">Mot de passe</label>
      <input type="password" id="motdepasse" name="motdepasse" required />

      <label for="sport">Sport</label>
      <select id="sport" name="sport" required>
        <option value="">--Choisissez un sport--</option>
        <option value="ski">Ski</option>
        <option value="tennis">Tennis</option>
        <option value="boxe">Boxe</option>
        <option value="athletisme">Athlétisme</option>
        <option value="natation">Natation</option>
        <option value="musculation">Musculation</option>
      </select>

      <label for="age">Âge</label>
      <input type="number" id="age" name="age" min="5" required />

      <label for="niveau">Niveau</label>
      <select id="niveau" name="niveau" required>
        <option value="">--Choisissez un niveau--</option>
        <option value="débutant">Débutant</option>
        <option value="intermédiaire">Intermédiaire</option>
        <option value="avancé">Avancé</option>
      </select>

      <button type="submit">Ajouter</button>
    </form>
  </div>
</body>
</html>
