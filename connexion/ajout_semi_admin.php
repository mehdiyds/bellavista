<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Semi-Administrateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            background-color: burlywood;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Semi-Administrateur</h1>
        
        <?php
        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Connexion à la base de données
                $conn = new mysqli('localhost', 'root', '', 'bellavista');
                
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }
                
                // Récupération des données du formulaire
                $nom = $conn->real_escape_string($_POST['nom']);
                $prenom = $conn->real_escape_string($_POST['prenom']);
                $num_cin = $conn->real_escape_string($_POST['num_cin']);
                $adresse = $conn->real_escape_string($_POST['adresse']);
                $telephone = $conn->real_escape_string($_POST['telephone']);
                $email = $conn->real_escape_string($_POST['email']);
                $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Hashage du mot de passe
                
                // Vérification si le CIN ou l'email existe déjà
                $check_query = "SELECT * FROM semi_administrateurs WHERE num_cin = '$num_cin' OR email = '$email'";
                $result = $conn->query($check_query);
                
                if ($result->num_rows > 0) {
                    throw new Exception("Un semi-administrateur avec ce CIN ou cet email existe déjà.");
                }
                
                // Insertion dans la base de données
                $sql = "INSERT INTO semi_administrateurs 
                        (nom, prenom, num_cin, adresse, telephone, email, mdp, statut) 
                        VALUES ('$nom', '$prenom', '$num_cin', '$adresse', '$telephone', '$email', '$mdp', 'actif')";
                
                if ($conn->query($sql) === TRUE) {
                    echo '<div class="alert alert-success">Semi-administrateur ajouté avec succès!</div>';
                } else {
                    throw new Exception("Erreur: " . $sql . "<br>" . $conn->error);
                }
                
                $conn->close();
                
            } catch (Exception $e) {
                echo '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        
        <form method="post" action="ajout_semi_admin.php">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            
            <div class="form-group">
                <label for="num_cin">Numéro CIN:</label>
                <input type="text" id="num_cin" name="num_cin" required>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse:</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="tel" id="telephone" name="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="mdp">Mot de passe:</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            
            <button type="submit" class="btn">Ajouter</button>
        </form>
    </div>
</body>
</html>