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

        $telephone = trim($_POST['telephone']);
        $password = trim($_POST['password']);

        if (empty($telephone) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires");
        }

        // Recherche du semi-admin
        $stmt = $conn->prepare("SELECT * FROM semi_administrateurs WHERE telephone = :telephone AND statut = 'actif'");
        $stmt->bindParam(':telephone', $telephone);
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['mdp']) {
            // Démarrer une nouvelle session
            session_start();
            $_SESSION = array();
            
            $_SESSION['semi_admin'] = [
                'id' => $admin['semi_admin_id'],
                'nom' => $admin['nom'],
                'prenom' => $admin['prenom'],
                'telephone' => $admin['telephone'],
                'type' => 'semi_admin',
                'timeout' => time() // Timestamp de connexion
            ];
            
            header('Location: semiadmin.php');
            exit();
        } else {
            throw new Exception("Identifiants incorrects ou compte inactif");
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
    <title>Connexion Semi-Admin - Bella Vista</title>
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
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('background-admin.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #27ae60;
        }
        
        .error {
            color: #d9534f;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .back-link {
            display: block;
            margin-top: 20px;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link:hover {
            color: #34495e;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion Semi-Administrateur</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Se connecter</button>
            
            <a href="connexion.php" class="back-link">← Retour à la sélection de connexion</a>
        </form>
    </div>
</body>
</html>