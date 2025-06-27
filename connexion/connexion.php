<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bella Vista</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('background.jpg');
            background-size: cover;
            background-position: center;
        }

        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 400px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: block;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
        }

        .btn-livreur {
            background-color: #3498db;
        }

        .btn-livreur:hover {
            background-color: #2980b9;
        }

        .btn-admin {
            background-color: #2ecc71;
        }

        .btn-admin:hover {
            background-color: #27ae60;
        }

        .logo {
            width: 120px;
            margin-bottom: 20px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 20px;
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Vous pouvez ajouter un logo ici si vous en avez un -->
        <!-- <img src="logo.png" alt="Logo Bella Vista" class="logo"> -->
        
        <h1>Choisissez votre mode de connexion</h1>
        
        <div class="btn-container">
            <a href="login.php" class="btn btn-livreur">Livreur</a>
            <a href="login-admin.php" class="btn btn-admin">Semi-Administrateur</a>
        </div>
    </div>
</body>
</html>