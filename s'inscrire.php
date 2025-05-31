<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription - Sportiva</title>
    <link rel="stylesheet" href="s'inscrire.css">
</head>

<body>



    <div class="container">
        <h2>Inscription Sportiva</h2>
        <form action="register.php" method="post">
            <label for="role">Je suis :</label>
            <select id="role" name="role" required>
                <option value="">-- Sélectionnez --</option>
                <option value="client">Client</option>
             
            </select>

            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="motdepasse">Mot de passe :</label>
            <input type="password" id="motdepasse" name="motdepasse" required>

            <label for="sport">Sport pratiqué ou enseigné :</label>
            <select id="sport" name="sport" required>
                <option value="">-- Choisir un sport --</option>
                <option value="judo">Judo</option>
                <option value="tennis">Tennis</option>
                <option value="boxe">Boxe</option>
                <option value="athletisme">Athlétisme</option>
                <option value="natation">Natation</option>
                <option value="musculation">Musculation</option>
            </select>

         

            <div id="clientFields" class="role-section">
                <label for="age">Âge (de 18 à 100) :</label>
                <select id="age" name="age">
                    <option value="">-- Sélectionnez votre âge --</option>
                    <?php
                    for ($i = 18; $i <= 100; $i++) {
                        echo "<option value=\"$i\">$i</option>";
                    }
                    ?>
                </select>
</div>

            <button type="submit">S'inscrire</button>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
      
        const clientFields = document.getElementById('clientFields');

        roleSelect.addEventListener('change', () => {
            const role = roleSelect.value;
            clientFields.style.display = role === 'client' ? 'block' : 'none';
        });
    </script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        const notification = document.getElementById('notification');

        if (success === '1') {
            notification.textContent = "✅ Inscription réussie !";
            notification.classList.remove('hidden');
        } else if (success === '0') {
            notification.textContent = "❌ Erreur lors de l'inscription.";
            notification.classList.add('error');
            notification.classList.remove('hidden');
        }

        // إخفاء الإشعار بعد 5 ثوانٍ
        if (success !== null) {
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 5000);
        }
    </script>

</body>




</html>