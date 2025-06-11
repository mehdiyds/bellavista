<?php 
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
?>

<section class="contact-hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <h1>Nous trouver</h1>
        <p>Venez nous rencontrer à Korba</p>
    </div>
</section>

<section class="contact-main">
    <div class="container">
        <div class="contact-grid">
            <div class="contact-info">
                <div class="info-card">
                    <div class="icon-box">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Adresse exacte</h3>
                    <p>Bella Vista Korba<br>HVG7+CH Korba<br>Nabeul, Tunisie</p>
                    <p class="map-code">Code Google Maps: HVG7+CH Korba</p>
                </div>

                <div class="info-card">
                    <div class="icon-box">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3>Réservations</h3>
                    <p>+216 72 234 567<br>+49 1575 7803845</p>
                </div>

                <div class="info-card">
                    <div class="icon-box">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3>Horaires d'ouverture</h3>
                    <p>Ouvert jusqu'à 02:00<br>Service continu</p>
                    <div class="service-badges">
                        <span class="badge">✓ Repas sur place</span>
                        <span class="badge">✓ Service au volant</span>
                        <span class="badge">✓ Livraison</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="icon-box">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>À proximité</h3>
                    <ul class="nearby-list">
                        <li>Mosquée Al Faith (1 min)</li>
                        <li>Lycée de Korba (3 min)</li>
                        <li>Stade Municipal (5 min)</li>
                    </ul>
                </div>
            </div>

            <div class="contact-map-container">
                <h2 class="map-title">Notre emplacement exact</h2>
                <div class="contact-map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3237.123456789012!2d10.8639316!3d36.5761121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1302bbb769337b73%3A0x2f4c992397b8776f!2sBELLA%20VISTA!5e0!3m2!1sfr!2stn!4v1234567890123!5m2!1sfr!2stn" 
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
                <div class="map-actions">
                    <a href="https://maps.google.com?q=36.5761121,10.8639316" class="map-btn" target="_blank">
                        <i class="fas fa-directions"></i> Itinéraire
                    </a>
                    <a href="#" class="map-btn" onclick="shareLocation()">
                        <i class="fas fa-share-alt"></i> Partager
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Contact Hero */
.contact-hero {
    position: relative;
    height: 400px;
    background: url('<?php echo $base_url; ?>uploads/korba-beach.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.contact-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 15px;
    font-weight: 300;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Contact Main */
.contact-main {
    padding: 80px 0;
    background: #f8f5f0;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 50px;
}

.map-code {
    background: #f0f0f0;
    padding: 5px 10px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.85rem;
    margin-top: 10px;
    display: inline-block;
}

.service-badges {
    margin-top: 15px;
}

.badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    margin-right: 5px;
    margin-bottom: 5px;
}

.nearby-list {
    list-style: none;
    padding-left: 0;
    margin-top: 10px;
}

.nearby-list li {
    padding: 8px 0;
    border-bottom: 1px dashed #eee;
    font-size: 0.9rem;
}

.nearby-list li:last-child {
    border-bottom: none;
}

.contact-map-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.map-title {
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #333;
    font-weight: 400;
}

.contact-map {
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid #eee;
}

.map-actions {
    display: flex;
    gap: 15px;
}

.map-btn {
    flex: 1;
    padding: 12px;
    background: #c8a97e;
    color: white;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s;
}

.map-btn:hover {
    background: #b5986e;
    transform: translateY(-2px);
}

.map-btn i {
    margin-right: 8px;
}

/* Responsive */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        height: 300px;
    }
    
    .map-actions {
        flex-direction: column;
    }
}
</style>

<script>
function shareLocation() {
    if (navigator.share) {
        navigator.share({
            title: 'Bella Vista Korba',
            text: 'Venez nous visiter à Bella Vista Korba',
            url: 'https://maps.google.com?q=36.5761121,10.8639316'
        }).catch(console.error);
    } else {
        alert("Fonction de partage non disponible - Copiez ce lien:\nhttps://maps.google.com?q=36.5761121,10.8639316");
    }
}
</script>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>