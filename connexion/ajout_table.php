<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Table</title>
    <style>
        /* Reprenez les styles de gestion_tables.php */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #8e44ad;
        }
        .btn-secondary {
            background-color: #3498db;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Ajouter une Table</h1>
    
    <div class="form-container">
        <form action="traitement_table.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="numero">Numéro de table:</label>
                <input type="number" name="numero" id="numero" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="capacite">Capacité (nombre de personnes):</label>
                <input type="number" name="capacite" id="capacite" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="statut">Statut:</label>
                <select name="statut" id="statut" required>
                    <option value="disponible">Disponible</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description (optionnel):</label>
                <textarea name="description" id="description" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="caracteristiques">Caractéristiques (optionnel):</label>
                <textarea name="caracteristiques" id="caracteristiques" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Image (optionnel):</label>
                <input type="file" name="image" id="image" accept="image/*">
                <div id="imagePreview" class="image-preview"></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="gestion_tables.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
    
    <script>
        // Prévisualisation de l'image
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').innerHTML = 
                        '<img src="' + e.target.result + '" alt="Prévisualisation" style="max-width: 100%;">';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>