<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - Gestion des Commandes</title>
    <link rel="stylesheet" href="style.css">
    <script src="controle.js"></script>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes - Espace Admin</h1>
        
        <div id="alertSuccess" class="alert alert-success"></div>
        <div id="alertError" class="alert alert-error"></div>
        
        <table class="commandes-table">
            <thead>
                <tr>
                    <th width="50"><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Détails</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody id="commandes-list">
                <!-- Les commandes seront ajoutées ici par JavaScript -->
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
            <button type="button" id="assign-btn" class="ajout-btn">Ajouter un livreur</button>
        </div>
    </div>
</body>
</html>