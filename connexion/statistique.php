<?php
// statistique.php - Command History Page

// Database connection
$host = '127.0.0.1';
$dbname = 'bellavista';
$username = 'root'; // Change as needed
$password = ''; // Change as needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Initialize filter variables with proper validation
$client_id = isset($_GET['client_id']) && $_GET['client_id'] !== '' ? (int)$_GET['client_id'] : null;
$status_filter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) && $_GET['date_from'] !== '' ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) && $_GET['date_to'] !== '' ? $_GET['date_to'] : '';
$min_amount = isset($_GET['min_amount']) && $_GET['min_amount'] !== '' ? (float)$_GET['min_amount'] : null;
$max_amount = isset($_GET['max_amount']) && $_GET['max_amount'] !== '' ? (float)$_GET['max_amount'] : null;

// Build the base query with proper filtering
$query = "SELECT h.*, c.nom as client_name 
          FROM historique_commandes h
          JOIN clients c ON h.client_id = c.client_id
          WHERE 1=1";

$params = [];

// Add filters if they exist
if ($client_id !== null) {
    $query .= " AND h.client_id = :client_id";
    $params[':client_id'] = $client_id;
}

if ($status_filter !== '') {
    $query .= " AND h.statut = :status";
    $params[':status'] = $status_filter;
}

if ($date_from !== '') {
    $query .= " AND h.date_commande >= :date_from";
    $params[':date_from'] = $date_from;
}

if ($date_to !== '') {
    $query .= " AND h.date_commande <= :date_to";
    $params[':date_to'] = $date_to . ' 23:59:59'; // Include entire day
}

if ($min_amount !== null) {
    $query .= " AND h.montant_total >= :min_amount";
    $params[':min_amount'] = $min_amount;
}

if ($max_amount !== null) {
    $query .= " AND h.montant_total <= :max_amount";
    $params[':max_amount'] = $max_amount;
}

$query .= " ORDER BY h.date_commande DESC";

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total statistics with the same filters
$stats_query = "SELECT 
                COUNT(*) as total_orders,
                SUM(montant_total) as total_amount,
                SUM(montant_paye) as total_paid,
                SUM(montant_total - montant_paye) as total_remaining
                FROM historique_commandes h
                WHERE 1=1";

$stats_params = [];
if ($client_id !== null) {
    $stats_query .= " AND h.client_id = :client_id";
    $stats_params[':client_id'] = $client_id;
}

if ($status_filter !== '') {
    $stats_query .= " AND h.statut = :status";
    $stats_params[':status'] = $status_filter;
}

if ($date_from !== '') {
    $stats_query .= " AND h.date_commande >= :date_from";
    $stats_params[':date_from'] = $date_from;
}

if ($date_to !== '') {
    $stats_query .= " AND h.date_commande <= :date_to";
    $stats_params[':date_to'] = $date_to . ' 23:59:59';
}

if ($min_amount !== null) {
    $stats_query .= " AND h.montant_total >= :min_amount";
    $stats_params[':min_amount'] = $min_amount;
}

if ($max_amount !== null) {
    $stats_query .= " AND h.montant_total <= :max_amount";
    $stats_params[':max_amount'] = $max_amount;
}

$stats_stmt = $pdo->prepare($stats_query);
$stats_stmt->execute($stats_params);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);

// Get list of clients for dropdown
$clients = $pdo->query("SELECT client_id, nom FROM clients ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

// Get distinct statuses for dropdown
$statuses = $pdo->query("SELECT DISTINCT statut FROM historique_commandes")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes - Bella Vista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            color: #333;
        }
        .filter-section {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 10px;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        button.reset {
            background-color: #f44336;
        }
        button.reset:hover {
            background-color: #d32f2f;
        }
        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            flex: 1;
            min-width: 200px;
            background: #e9f7ef;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            color: #2e7d32;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1b5e20;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status-pending { color: #ff9800; }
        .status-preparing { color: #2196f3; }
        .status-assigned { color: #673ab7; }
        .status-delivering { color: #3f51b5; }
        .status-delivered { color: #4caf50; }
        .status-cancelled { color: #f44336; }
        .negative-amount { color: #f44336; }
        .positive-amount { color: #4caf50; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historique des Commandes</h1>
        
        <div class="filter-section">
            <h2>Filtres</h2>
            <form method="get" action="statistique.php">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="client_id">Client</label>
                        <select id="client_id" name="client_id">
                            <option value="">Tous les clients</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['client_id'] ?>" <?= $client_id == $client['client_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($client['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status">Statut</label>
                        <select id="status" name="status">
                            <option value="">Tous les statuts</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status ?>" <?= $status_filter == $status ? 'selected' : '' ?>>
                                    <?= ucfirst($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_from">Date de début</label>
                        <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($date_from) ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_to">Date de fin</label>
                        <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($date_to) ?>">
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="min_amount">Montant minimum (DT)</label>
                        <input type="number" step="0.01" min="0" id="min_amount" name="min_amount" 
                               value="<?= $min_amount !== null ? htmlspecialchars($min_amount) : '' ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label for="max_amount">Montant maximum (DT)</label>
                        <input type="number" step="0.01" min="0" id="max_amount" name="max_amount" 
                               value="<?= $max_amount !== null ? htmlspecialchars($max_amount) : '' ?>">
                    </div>
                </div>
                
                <button type="submit">Appliquer les filtres</button>
                <button type="button" class="reset" onclick="window.location.href='statistique.php'">Réinitialiser</button>
            </form>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Commandes totales</h3>
                <div class="stat-value"><?= $stats['total_orders'] ?? 0 ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Montant total</h3>
                <div class="stat-value"><?= number_format($stats['total_amount'] ?? 0, 2) ?> DT</div>
            </div>
            
            <div class="stat-card">
                <h3>Montant payé</h3>
                <div class="stat-value"><?= number_format($stats['total_paid'] ?? 0, 2) ?> DT</div>
            </div>
            
            <div class="stat-card">
                <h3>Reste à payer</h3>
                <div class="stat-value <?= ($stats['total_remaining'] ?? 0) < 0 ? 'negative-amount' : 'positive-amount' ?>">
                    <?= number_format(abs($stats['total_remaining'] ?? 0), 2) ?> DT
                </div>
            </div>
        </div>
        
        <h2>Résultats</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Commande</th>
                    <th>Montant Total</th>
                    <th>Montant Payé</th>
                    <th>Reste</th>
                    <th>Statut</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['commande_id']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></td>
                        <td><?= htmlspecialchars($order['client_name']) ?></td>
                        <td><?= htmlspecialchars($order['commande']) ?></td>
                        <td><?= number_format($order['montant_total'], 2) ?> DT</td>
                        <td><?= number_format($order['montant_paye'], 2) ?> DT</td>
                        <td class="<?= ($order['montant_total'] - $order['montant_paye']) < 0 ? 'negative-amount' : 'positive-amount' ?>">
                            <?= number_format($order['montant_total'] - $order['montant_paye'], 2) ?> DT
                        </td>
                        <td class="status-<?= str_replace(' ', '-', strtolower($order['statut'])) ?>">
                            <?= ucfirst($order['statut']) ?>
                        </td>
                        <td><?= htmlspecialchars($order['notes']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">Aucune commande trouvée avec les critères sélectionnés</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>