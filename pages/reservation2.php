<?php 
include 'C:\xampp\htdocs\bellavista\includes\header.php';

// Define base URL
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $full_name = htmlspecialchars($_POST['full_name']);
    $phone = htmlspecialchars($_POST['phone']);
    $seats = intval($_POST['seats']);
    $reservation_date = htmlspecialchars($_POST['reservation_date']);
    $special_requests = htmlspecialchars($_POST['special_requests']);
    
    // Basic validation
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if ($seats < 1 || $seats > 20) {
        $errors[] = "Number of seats must be between 1 and 20";
    }
    
    if (empty($reservation_date)) {
        $errors[] = "Reservation date is required";
    } elseif (strtotime($reservation_date) < strtotime('today')) {
        $errors[] = "Reservation date cannot be in the past";
    }
    
    // If no errors, process the reservation
    if (empty($errors)) {
        // Database connection
        $host = 'localhost';
        $dbname = 'bellavista';
        $username = 'root';
        $password = '';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Insert reservation into database
            $stmt = $pdo->prepare("INSERT INTO reservations2 
                                  (full_name, phone, seats, reservation_date, special_requests, created_at) 
                                  VALUES (:full_name, :phone, :seats, :reservation_date, :special_requests, NOW())");
            
            $stmt->execute([
                ':full_name' => $full_name,
                ':phone' => $phone,
                ':seats' => $seats,
                ':reservation_date' => $reservation_date,
                ':special_requests' => $special_requests
            ]);
            
            // Success message
            $success_message = "Thank you, $full_name! Your reservation for $seats people on $reservation_date has been confirmed.";
            
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<section class="reservation-section">
    <div class="container">
        <h1 class="section-title">Make a Reservation</h1>
        <p class="section-subtitle">Book your table at Bella Vista</p>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <h3>Please fix the following errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <h3>Reservation Confirmed!</h3>
                <p><?php echo $success_message; ?></p>
                <p>We'll contact you shortly to confirm your booking.</p>
                <a href="<?php echo $base_url; ?>" class="btn">Back to Home</a>
            </div>
        <?php else: ?>
            <form method="POST" class="reservation-form">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo isset($full_name) ? $full_name : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?php echo isset($phone) ? $phone : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="seats">Number of Seats *</label>
                    <select id="seats" name="seats" required>
                        <option value="">Select...</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>" 
                                <?php echo (isset($seats) && $seats == $i ? 'selected' : ''); ?>>
                                <?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="reservation_date">Reservation Date *</label>
                    <input type="date" id="reservation_date" name="reservation_date" required 
                           min="<?php echo date('Y-m-d'); ?>" 
                           value="<?php echo isset($reservation_date) ? $reservation_date : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="special_requests">Special Requests</label>
                    <textarea id="special_requests" name="special_requests" rows="3"><?php 
                        echo isset($special_requests) ? $special_requests : ''; 
                    ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-reservation">Confirm Reservation</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>

<style>
.reservation-section {
    padding: 60px 0;
    background-color: #f9f9f9;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.section-title {
    text-align: center;
    font-size: 2.2rem;
    color: #333;
    margin-bottom: 15px;
}

.section-subtitle {
    text-align: center;
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 40px;
}

.alert {
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 30px;
}

.alert-danger {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-success {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    text-align: center;
}

.reservation-form {
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input[type="text"],
.form-group input[type="tel"],
.form-group input[type="date"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group input[type="tel"]:focus,
.form-group input[type="date"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #c8a97e;
    outline: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-actions {
    text-align: center;
    margin-top: 30px;
}

.btn-reservation {
    background-color: #c8a97e;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-reservation:hover {
    background-color: #b5986e;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #333;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #555;
}

@media (max-width: 768px) {
    .reservation-section {
        padding: 40px 0;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .reservation-form {
        padding: 20px;
    }
}
</style>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>