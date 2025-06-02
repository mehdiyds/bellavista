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
        
        <form id="loginForm" action="ajout_livreur.php">
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
            
            // Validation basique (à remplacer par une vérification réelle en production)
            if (livreurId && password) {
                // Simulation de vérification
                if (livreurId === "livreur1" && password === "delivery123") {
                    // Redirection vers la page du livreur après connexion réussie
                    window.location.href = "livreur-dashboard.html";
                } else {
                    errorMessage.textContent = "Identifiant ou mot de passe incorrect";
                    errorMessage.style.display = "block";
                }
            } else {
                errorMessage.textContent = "Veuillez remplir tous les champs";
                errorMessage.style.display = "block";
            }
        });
    </script>
</body>
</html>