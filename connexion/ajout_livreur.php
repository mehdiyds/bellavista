<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livreur - Bella Vista</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reset et styles de base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
      
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        
        
        /* Styles du formulaire */
        .form-group {
            margin-bottom: 20px;
        }
        
        .label_ajout {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        button {
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .submit-btn {
            background-color: #2ecc71;
            color: white;
        }
        
        .cancel-btn {
            background-color: #e74c3c;
            color: white;
        }
        
        button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Messages d'alerte */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajouter un nouveau livreur</h1>
        
        <div id="alertSuccess" class="alert alert-success"></div>
        <div id="alertError" class="alert alert-error"></div>
        
        <form id="form-ajout-livreur">
            <div class="form-group">
                <label for="id-livreur" class="label_ajout">ID Livreur</label>
                <input type="text" id="id-livreur" required>
            </div>
            
            <div class="form-group">
                <label for="nom"  class="label_ajout">Nom</label>
                <input type="text" id="nom" required>
            </div>
            
            <div class="form-group">
                <label for="prenom" class="label_ajout">Prénom</label>
                <input type="text" id="prenom" required>
            </div>
            
            <div class="form-group">
                <label for="telephone" class="label_ajout">Téléphone</label>
                <input type="tel" id="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="statut" class="label_ajout">Statut</label>
                <select id="statut" required>
                    <option value="">-- Sélectionner un statut --</option>
                    <option value="disponible">Disponible</option>
                    <option value="indisponible">Indisponible</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn">Enregistrer</button>
                <button type="button" class="cancel-btn" id="cancel-btn">Annuler</button>
            </div>
        </form>
    </div>

    <script>
        // Fonction pour afficher les messages
        function showAlert(message, type = 'success') {
            const alertDiv = type === 'success' 
                ? document.getElementById('alertSuccess')
                : document.getElementById('alertError');
            
            alertDiv.textContent = message;
            alertDiv.style.display = 'block';
            
            setTimeout(() => {
                alertDiv.style.display = 'none';
            }, 5000);
        }

        // Gestion de la soumission du formulaire
        document.getElementById('form-ajout-livreur').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupération des valeurs du formulaire
            const livreur = {
                id: document.getElementById('id-livreur').value,
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                telephone: document.getElementById('telephone').value,
                statut: document.getElementById('statut').value
            };
            
            // Validation simple
            if (!livreur.id || !livreur.nom || !livreur.prenom || !livreur.telephone || !livreur.statut) {
                showAlert('Veuillez remplir tous les champs', 'error');
                return;
            }
            
            // Ici, normalement on enverrait les données au serveur
            // Pour cette démo, on simule juste l'enregistrement
            showAlert(`Livreur ${livreur.prenom} ${livreur.nom} ajouté avec succès!`);
            
            // Réinitialisation du formulaire
            this.reset();
            
            // Redirection après 2 secondes (simulation)
            setTimeout(() => {
                window.location.href = 'admin.php';
            }, 2000);
        });

        // Bouton Annuler
        document.getElementById('cancel-btn').addEventListener('click', function() {
            if (confirm('Voulez-vous vraiment annuler ? Les données saisies seront perdues.')) {
                window.location.href = 'admin.php';
            }
        });
    </script>
</body>
</html>