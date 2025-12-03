<?php
session_start();
/*
// Configuration de la session pour expirer quand le navigateur se ferme
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 0);

// Destruction de la session existante si on accède à la page de login
session_unset();
session_destroy();

// Activation du débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $dbname = 'bellavista';
    $db_username = 'root';
    $db_password = '';

    try {
        $conn = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
            $db_username, 
            $db_password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        // Recherche du livreur
        $stmt = $conn->prepare("SELECT * FROM livreurs WHERE telephone = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && $password === $user['mdp']) {
            // Démarrer une nouvelle session
            session_start();
            $_SESSION = array();
            
            $_SESSION['user'] = [
                'id' => $user['livreur_id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'telephone' => $user['telephone'],
                'statut' => $user['statut'],
                'type' => 'livreur',
                'timeout' => time() // Timestamp de connexion
            ];
            
            header('Location: livreur.php');
            exit();
        } else {
            throw new Exception("Identifiant ou mot de passe incorrect");
        }

    } catch (PDOException $e) {
        $error = "Erreur de connexion à la base de données";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Livreur - Bella Vista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #0b7dda;
        }
        
        .error {
            color: #d9534f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion Livreur</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Téléphone:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>