        // Données simulées
        const commandes = [
            {
                id: 1001,
                client: "Michel Bernard",
                details: "Pizza Margherita, 2 Coca-Cola",
                date: "2023-05-15 18:30",
                statut: "En attente"
            },
            {
                id: 1002,
                client: "Élodie Petit",
                details: "Salade César, Eau minérale",
                date: "2023-05-15 19:15",
                statut: "En attente"
            },
            {
                id: 1003,
                client: "Thomas Leroy",
                details: "Pizza 4 fromages, Tiramisu",
                date: "2023-05-15 20:00",
                statut: "En attente"
            },
            {
                id: 1004,
                client: "Nathalie Moreau",
                details: "Pasta Carbonara, Vin rouge",
                date: "2023-05-15 20:45",
                statut: "En attente"
            }
        ];

        const livreurs = [
            { id: 1, nom: "Jean Dupont" },
            { id: 2, nom: "Marie Martin" },
            { id: 3, nom: "Pierre Durand" },
            { id: 4, nom: "Sophie Lambert" }
        ];

        // Afficher les commandes
        function afficherCommandes() {
            const tbody = document.getElementById('commandes-list');
            tbody.innerHTML = '';
            
            commandes.forEach(commande => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="checkbox" class="commande-checkbox" value="${commande.id}"></td>
                    <td>${commande.id}</td>
                    <td>${commande.client}</td>
                    <td>${commande.details}</td>
                    <td>${commande.date}</td>
                    <td>${commande.statut}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Afficher un message
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

        // Assigner des commandes à un livreur
        function assignerCommandes(livreurId, commandesIds) {
            if (!livreurId) {
                showAlert('Veuillez sélectionner un livreur', 'error');
                return;
            }
            
            if (commandesIds.length === 0) {
                showAlert('Veuillez sélectionner au moins une commande', 'error');
                return;
            }
            
            const livreur = livreurs.find(l => l.id == livreurId);
            
            // Simulation de mise à jour
            commandes.forEach(commande => {
                if (commandesIds.includes(commande.id.toString())) {
                    commande.statut = "Assignée à " + livreur.nom;
                }
            });
            
            showAlert(`${commandesIds.length} commande(s) assignée(s) à ${livreur.nom}`);
            afficherCommandes();
        }

        // Supprimer des commandes
        function supprimerCommandes(commandesIds) {
            if (commandesIds.length === 0) {
                showAlert('Veuillez sélectionner au moins une commande', 'error');
                return;
            }
            
            // Simulation de suppression
            for (let i = commandes.length - 1; i >= 0; i--) {
                if (commandesIds.includes(commandes[i].id.toString())) {
                    commandes.splice(i, 1);
                }
            }
            
            showAlert(`${commandesIds.length} commande(s) supprimée(s)`);
            afficherCommandes();
        }

        // Événements au chargement
        document.addEventListener('DOMContentLoaded', () => {
            afficherCommandes();
            
            // Sélection/désélection de toutes les cases
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.commande-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Bouton Assigner
            document.getElementById('assign-btn').addEventListener('click', () => {
                const livreurId = document.getElementById('livreur-select').value;
                const checkboxes = document.querySelectorAll('.commande-checkbox:checked');
                const commandesIds = Array.from(checkboxes).map(cb => cb.value);
                
                assignerCommandes(livreurId, commandesIds);
            });
            
            // Bouton Supprimer
            document.getElementById('delete-btn').addEventListener('click', () => {
                const checkboxes = document.querySelectorAll('.commande-checkbox:checked');
                const commandesIds = Array.from(checkboxes).map(cb => cb.value);
                
                if (confirm(`Voulez-vous vraiment supprimer ${commandesIds.length} commande(s) ?`)) {
                    supprimerCommandes(commandesIds);
                }
            });
        });





/*pour la page historique*/


