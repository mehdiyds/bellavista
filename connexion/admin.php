<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Gestion des Commandes</title>
    <link rel="stylesheet" href="style.css">
    <script src="controle.js"></script>
    <style>
        .action-panel {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .commandes-table {
            width: 100%;
            border-collapse: collapse;
        }
        .commandes-table th, .commandes-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
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
    <script>
        

        function populateTable() {
            // Sample data - replace with actual data from your database
            const commandes = [
                {
                    id: 1001,
                    client: "Michel Bernard",
                    telephone: "123456789",
                    adresse: "10 Rue de Paris, Tunis",
                    commande: "Plaza Margherita, 2 Coca-Cola",
                    montant: "25.50",
                    reste: "0.00",
                    date: "2023-06-15 18:30",
                    statut: "En attente"
                },
                {
                    id: 1002,
                    client: "Élodie Petit",
                    telephone: "987654321",
                    adresse: "15 Avenue Habib Bourguiba, Sousse",
                    commande: "Salade César, Eau minérale",
                    montant: "18.75",
                    reste: "5.25",
                    date: "2023-06-15 19:15",
                    statut: "En attente"
                },
                {
                    id: 1003,
                    client: "Thomas Leroy",
                    telephone: "555123456",
                    adresse: "22 Rue de la Liberté, Bizerte",
                    commande: "Plaza 4 fromages, Tiramisu",
                    montant: "32.00",
                    reste: "0.00",
                    date: "2023-06-15 20:00",
                    statut: "En attente"
                },
                {
                    id: 1004,
                    client: "Nathalie Moreau",
                    telephone: "555987654",
                    adresse: "5 Avenue Farhat Hached, Hammamet",
                    commande: "Pasta Carbonara, Vin rouge",
                    montant: "28.50",
                    reste: "3.50",
                    date: "2023-06-15 20:45",
                    statut: "En attente"
                }
            ];

            const tbody = document.getElementById('commandes-list');
            tbody.innerHTML = '';

            commandes.forEach(commande => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="checkbox" class="commande-checkbox" data-id="${commande.id}"></td>
                    <td>${commande.id}</td>
                    <td>${commande.client}</td>
                    <td>${commande.telephone}</td>
                    <td>${commande.adresse}</td>
                    <td>${commande.commande}</td>
                    <td>${commande.montant} DT</td>
                    <td>${commande.reste} DT</td>
                    <td>${commande.date}</td>
                    <td>${commande.statut}</td>
                `;
                tbody.appendChild(row);
            });
        }

        // Initialize when page loads
        window.onload = function() {
            populateTable();
            
            // Select all checkbox functionality
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.commande-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        };
    </script>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <div id="alertSuccess" class="alert alert-success" style="display: none;"></div>
        <div id="alertError" class="alert alert-error" style="display: none;"></div>
        
        <table class="commandes-table">
            <thead>
                <tr>
                    <th width="50"><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Commande</th>
                    <th>Montant Total</th>
                    <th>Reste à Payer</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody id="commandes-list">
                <!-- Dynamic content will be inserted here -->
            </tbody>
        </table>
        
        <div class="action-panel">
            <select id="livreur-select" required>
                <option value="">-- Sélectionner un livreur --</option>
                <option value="1">Jean Dupont</option>
                <option value="2">Marie Martin</option>
                <option value="3">Pierre Durand</option>
                <option value="4">Sophie Lambert</option>
            </select>
            
            <button type="button" id="assign-btn" class="assign-btn">Assigner au livreur</button>
            <button type="button" id="delete-btn" class="delete-btn">Supprimer la sélection</button>
            <a href="ajout_livreur.php">
                <button type="button" id="ajout-btn" class="ajout-btn">Ajouter un livreur</button>
            </a>
        </div>
    </div>
</body>
</html>