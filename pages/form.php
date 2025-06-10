<?php
session_start();

// Vérification du panier
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: panier.php');
    exit;
}

// Calcul du total
$total = 0;
$cart = $_SESSION['cart'];
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $cart_items = json_decode($_POST['cart_items'], true);
    $paiement = $_POST['paiement'];
    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'bellavista');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Détermination du montant payé
    if ($paiement === 'exact') {
        $montant_paye = $total;
    } else {
        $montant_paye = $_POST['montant'];
    }

    // Vérifier si le client existe déjà
    $sql_check = "SELECT client_id FROM clients WHERE telephone = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $telephone);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $client_id = $client['client_id'];
        
        // Mettre à jour les infos du client si nécessaire
        $sql_update = "UPDATE clients SET nom = ?, adresse = ? WHERE client_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $nom, $adresse, $client_id);
        $stmt_update->execute();
    } else {
        $sql = "INSERT INTO clients (nom, adresse, telephone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nom, $adresse, $telephone);

        if ($stmt->execute()) {
            $client_id = $stmt->insert_id;
            $_SESSION['is_new_client'] = true;
        } else {
            die("Erreur lors de l'ajout du client: " . $conn->error);
        }
    }

    // Préparation de la liste des produits commandés
    $liste_produits_commandes = [];
    $produits_counts = [];

    foreach ($cart_items as $item) {
        $produit_nom = $item['name'];
        if (isset($produits_counts[$produit_nom])) {
            $produits_counts[$produit_nom] += $item['quantity'];
        } else {
            $produits_counts[$produit_nom] = $item['quantity'];
        }
    }

    foreach ($produits_counts as $nom_produit => $quantite) {
        $liste_produits_commandes[] = $quantite . ' ' . $nom_produit;
    }

    $liste_produits = implode(' + ', $liste_produits_commandes);

    // Insertion de la commande avec le montant payé ET la liste des produits
    $sql_commande = "INSERT INTO commandes (client_id, montant_total, montant_paye, commande, date_commande, statut) 
                     VALUES (?, ?, ?, ?, NOW(), 'en attente')";
    $stmt_commande = $conn->prepare($sql_commande);
    $stmt_commande->bind_param("idds", $client_id, $total, $montant_paye, $liste_produits);
    
    if ($stmt_commande->execute()) {
        $commande_id = $stmt_commande->insert_id;
        
        // Insertion des détails de commande
        foreach ($cart_items as $item) {
            $sql_produit = "SELECT produit_id FROM produits WHERE nom = ?";
            $stmt_produit = $conn->prepare($sql_produit);
            $stmt_produit->bind_param("s", $item['name']);
            $stmt_produit->execute();
            $result = $stmt_produit->get_result();
            $produit = $result->fetch_assoc();
            
            if ($produit) {
                $sql_details = "INSERT INTO details_commandes (commande_id, produit_id, quantite, prix_unitaire) 
                               VALUES (?, ?, ?, ?)";
                $stmt_details = $conn->prepare($sql_details);
                $stmt_details->bind_param("iiid", $commande_id, $produit['produit_id'], $item['quantity'], $item['price']);
                $stmt_details->execute();
            }
        }
        
        // Préparation des données pour la confirmation
        $_SESSION['order_details'] = [
            'order_id' => $commande_id,
            'customer_name' => $nom,
            'address' => $adresse,
            'total' => number_format($total, 2) . ' DNT',
            'montant_paye' => number_format($montant_paye, 2) . ' DNT',
            'monnaie_rendue' => ($paiement === 'plus') ? number_format($montant_paye - $total, 2) . ' DNT' : '0.00 DNT',
            'produits_commandes' => $liste_produits
        ];
        
        unset($_SESSION['cart']);
        header('Location: confirmation.php');
        exit;
    } else {
        die("Erreur lors de la commande: " . $conn->error);
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de Commande - Bella Vista</title>
    <link rel="stylesheet" href="/bellavista/css/styles.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .panier-summary {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            background-color: #f8f9fa;

        }
        th {
            font-weight: bold;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .form-container {
            background-color: rgb(5, 46, 86);
            padding: 30px;
            border-radius: 8px;
            margin-top: 20px;
            color: white;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: black;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .payment-options {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            color: black;
            font-weight: bold;
        }
        .payment-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .button_commander {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .button_commander:hover {
            background-color: #45a049;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'C:\xampp\htdocs\bellavista\includes\header.php'; ?>

    <div class="container">
        <h1 class="section-title">Validation de Commande</h1>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="success-message">
                 Votre panier est prêt.
            </div>
        <?php endif; ?>
        
        <div class="panier-summary">
            <h2 style="padding: 15px; background: #f8f9fa; margin: 0;">Récapitulatif de votre panier</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Image</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $baseUrl = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
                    
                    foreach ($cart as $item) {
                        $itemTotal = $item['price'] * $item['quantity'];
                        $image = $baseUrl . $item['image'];
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                        echo '<td><img src="' . $image . '" alt="' . htmlspecialchars($item['name']) . '" class="product-image" onerror="this.src=\'' . $baseUrl . 'uploads/default.jpg\'"></td>';
                        echo '<td>' . number_format($item['price'], 2) . ' DT</td>';
                        echo '<td>' . $item['quantity'] . '</td>';
                        echo '<td>' . number_format($itemTotal, 2) . ' DT</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold; padding: 15px;">Total TTC:</td>
                        <td style="font-weight: bold; padding: 15px;"><?php echo number_format($total, 2); ?> D</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="form-container">
            <form action="form.php" method="post">
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                
                <div class="form-group">
                    <label for="adresse">Adresse de livraison</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>
                
                <div class="form-group">
                    <label for="telephone">Numéro de téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>
                
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" id="exact" name="paiement" value="exact" checked>
                        <label for="exact">Paiement exact</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="plus" name="paiement" value="plus">
                        <label for="plus">Paiement avec monnaie</label>
                    </div>
                </div>
                
                <div class="form-group" id="montant-group" style="display: none;">
                    <label for="montant">Montant payé</label>
                    <input type="number" id="montant" name="montant" min="<?php echo $total; ?>" step="0.01">
                </div>
                
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                <input type="hidden" name="cart_items" value="<?php echo htmlspecialchars(json_encode($cart)); ?>">
                
                <button type="submit" class="button_commander">Valider la commande</button>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exactRadio = document.getElementById('exact');
            const plusRadio = document.getElementById('plus');
            const montantGroup = document.getElementById('montant-group');
            const montantInput = document.getElementById('montant');
            const total = <?php echo $total; ?>;

            function toggleMontantField() {
                if (plusRadio.checked) {
                    montantGroup.style.display = 'block';
                    montantInput.min = total;
                    montantInput.value = total;
                } else {
                    montantGroup.style.display = 'none';
                }
            }

            exactRadio.addEventListener('change', toggleMontantField);
            plusRadio.addEventListener('change', toggleMontantField);

            // Initialisation
            toggleMontantField();
            
            // Validation
            document.querySelector('form').addEventListener('submit', function(e) {
                if (plusRadio.checked) {
                    const montant = parseFloat(montantInput.value);
                    if (isNaN(montant) || montant < total) {
                        alert('Le montant doit être supérieur ou égal au total de la commande.');
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
    
    <?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>
</body>
</html>