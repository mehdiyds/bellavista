<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Livreur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bodylogin">
    <div class="login-container">
        <h1 class="titrelog">Connexion Livreur</h1>
        
        <form id="loginForm" method="POST" action="authenticate_livreur.php">
            <div class="form-group">
                <label for="livreurId">Identifiant</label>
                <input type="text" id="livreurId" name="livreurId" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div id="errorMessage" class="error-message"></div>
            
            <button type="submit" class="buttonlog">Se connecter</button>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const livreurId = document.getElementById('livreurId').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');
            
            // Validation basique côté client
            if (!livreurId || !password) {
                errorMessage.textContent = "Veuillez remplir tous les champs";
                errorMessage.style.display = "block";
                return;
            }
            
            // Envoi des données au serveur
            fetch('authenticate_livreur.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `livreurId=${encodeURIComponent(livreurId)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirection après connexion réussie
                    window.location.href = "livreur-dashboard.php";
                } else {
                    errorMessage.textContent = data.message || "Identifiant ou mot de passe incorrect";
                    errorMessage.style.display = "block";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = "Une erreur est survenue lors de la connexion";
                errorMessage.style.display = "block";
            });
        });
    </script>
</body>
</html>