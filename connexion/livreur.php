<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Livreur - Bella Vista</title>
    <style>
        /* Reset et styles de base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        /* Styles du tableau */
        .commandes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        .commandes-table thead tr {
            background-color: #3498db;
            color: white;
            text-align: left;
        }
        
        .commandes-table th,
        .commandes-table td {
            padding: 12px 15px;
        }
        
        .commandes-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
        
        .commandes-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }
        
        .commandes-table tbody tr:last-of-type {
            border-bottom: 2px solid #3498db;
        }
        
        .commandes-table tbody tr:hover {
            background-color: #e8f4fc;
        }
        
        /* Styles des formulaires et boutons */
        .action-panel {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            align-items: center;
            flex-wrap: wrap;
        }
        
        select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 250px;
            font-size: 16px;
            background-color: white;
        }
        
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .assign-btn {
            background-color: #2ecc71;
            color: white;
        }
        
        .delete-btn {
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
        
        /* Badge de statut */
        .statut-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .statut-badge[data-statut="disponible"] {
            background-color: #d4edda;
            color: #155724;
        }
        
        .statut-badge[data-statut="indisponible"] {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .statut-badge[data-statut="en cours"] {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .statut-badge[data-statut="livrée"] {
            background-color: #cce5ff;
            color: #004085;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .commandes-table {
                display: block;
                overflow-x: auto;
            }
            
            .action-panel {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Espace Livreur - Jean Dupont</h1>
        
        <div id="alertSuccess" class="alert alert-success"></div>
        <div id="alertError" class="alert alert-error"></div>
        
        <div class="livreur-info">
            <p><strong>Téléphone:</strong> 06 12 34 56 78</p>
            <p><strong>Statut:</strong> <span class="statut-badge" data-statut="disponible">disponible</span></p>
        </div>
        
        <h2>Livraisons assignées</h2>
        <table class="commandes-table">
            <thead>
                <tr>
                    <th>ID Livraison</th>
                    <th>Commande</th>
                    <th>Client</th>
                    <th>Adresse</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="livraisons-list">
                <!-- Les livraisons seront ajoutées ici par JavaScript -->
            </tbody>
        </table>
        
        <div class="action-panel">
            <button type="button" id="disponible-btn" class="assign-btn">
                Marquer comme disponible
            </button>
            <button type="button" id="indisponible-btn" class="delete-btn">
                Marquer comme indisponible
            </button>
        </div>
    </div>

    <script>
        // Données simulées
        const livreur = {
            id: 1,
            nom: "Jean Dupont",
            telephone: "06 12 34 56 78",
            statut: "disponible"
        };

        const livraisons = [
            {
                livraison_id: 101,
                commande_id: 1001,
                date_commande: "2023-05-15 18:30",
                client_nom: "Bernard",
                client_prenom: "Michel",
                telephone: "06 11 22 33 44",
                adresse: "12 Rue des Fleurs, 75001 Paris",
                montant_total: "25.50",
                statut: "en cours"
            },
            {
                livraison_id: 102,
                commande_id: 1002,
                date_commande: "2023-05-15 19:15",
                client_nom: "Petit",
                client_prenom: "Élodie",
                telephone: "06 55 66 77 88",
                adresse: "34 Avenue des Champs, 75008 Paris",
                montant_total: "18.00",
                statut: "en cours"
            }
        ];

        // Afficher les livraisons
        function afficherLivraisons() {
            const tbody = document.getElementById('livraisons-list');
            tbody.innerHTML = '';
            
            if (livraisons.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center;">Aucune livraison assignée</td>
                    </tr>
                `;
                return;
            }
            
            livraisons.forEach(livraison => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>#${livraison.livraison_id}</td>
                    <td>
                        #${livraison.commande_id}<br>
                        <small>${livraison.date_commande}</small>
                    </td>
                    <td>
                        ${livraison.client_prenom} ${livraison.client_nom}<br>
                        <small>${livraison.telephone}</small>
                    </td>
                    <td>${livraison.adresse}</td>
                    <td>${livraison.montant_total} €</td>
                    <td>
                        <span class="statut-badge" data-statut="${livraison.statut}">${livraison.statut}</span>
                    </td>
                    <td>
                        <button class="assign-btn marquer-btn" data-livraison="${livraison.livraison_id}">
                            Marquer comme livrée
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Fonction pour marquer une livraison comme terminée
        function marquerLivree(livraisonId) {
            const livraison = livraisons.find(l => l.livraison_id == livraisonId);
            if (livraison) {
                livraison.statut = "livrée";
                showAlert(`Livraison #${livraisonId} marquée comme livrée`, 'success');
                afficherLivraisons();
            } else {
                showAlert("Livraison non trouvée", 'error');
            }
        }

        // Fonction pour changer le statut du livreur
        function changerStatut(statut) {
            livreur.statut = statut;
            showAlert(`Statut mis à jour: ${statut}`, 'success');
            document.querySelector('.livreur-info .statut-badge').textContent = statut;
            document.querySelector('.livreur-info .statut-badge').setAttribute('data-statut', statut);
        }

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

        // Événements au chargement
        document.addEventListener('DOMContentLoaded', () => {
            afficherLivraisons();
            
            // Boutons de marquage de livraison (délégué car dynamiques)
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('marquer-btn')) {
                    const livraisonId = e.target.getAttribute('data-livraison');
                    if (confirm("Marquer cette livraison comme terminée ?")) {
                        marquerLivree(livraisonId);
                    }
                }
            });
            
            // Bouton disponible
            document.getElementById('disponible-btn').addEventListener('click', () => {
                changerStatut('disponible');
            });
            
            // Bouton indisponible
            document.getElementById('indisponible-btn').addEventListener('click', () => {
                changerStatut('indisponible');
            });
        });
    </script>
</body>
</html>