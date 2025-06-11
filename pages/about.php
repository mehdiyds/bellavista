<?php 
include 'C:\xampp\htdocs\bellavista\includes\header.php'; 
$base_url = "http://".$_SERVER['HTTP_HOST']."/bellavista/";
?>

<section class="about-hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <h1>Notre Histoire</h1>
        <p>Découvrez la passion derrière Bella Vista</p>
    </div>
</section>

<section class="about-main">
    <div class="container">
        <div class="about-section">
            <div class="about-content">
                <h2>Bienvenue à Bella Vista</h2>
                <p>Fondé en 2010, Bella Vista est passé d'un petit café à l'une des destinations culinaires les plus appréciées de Tunis. Notre parcours a été guidé par une passion pour les saveurs exceptionnelles et une hospitalité chaleureuse.</p>
                
                <div class="about-features">
                    <div class="feature">
                        <i class="fas fa-coffee"></i>
                        <h3>Café Premium</h3>
                        <p>Nous sélectionnons uniquement les meilleurs grains auprès de producteurs éthiques à travers le monde.</p>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-utensils"></i>
                        <h3>Cuisine Gastronomique</h3>
                        <p>Notre menu propose des plats de saison préparés avec des ingrédients locaux.</p>
                    </div>
                    
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <h3>Équipe Passionnée</h3>
                        <p>Notre équipe est dédiée à créer des expériences mémorables pour chaque client.</p>
                    </div>
                </div>
            </div>
            
            <div class="about-image">
                <img src="<?php echo $base_url; ?>uploads/about-interior.jpg" alt="Intérieur de Bella Vista">
            </div>
        </div>
        
        <div class="history-section">
            <h2>Notre Histoire</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2010</div>
                    <div class="timeline-content">
                        <h3>Ouverture</h3>
                        <p>Bella Vista ouvre ses portes comme un petit café avec seulement 10 tables.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2014</div>
                    <div class="timeline-content">
                        <h3>Première Expansion</h3>
                        <p>Nous avons agrandi notre espace et ajouté une cuisine complète pour servir le petit-déjeuner et le déjeuner.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2018</div>
                    <div class="timeline-content">
                        <h3>Prix du Meilleur Café</h3>
                        <p>Reconnu comme servant le meilleur café à Tunis par Food & Wine Magazine.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-year">2022</div>
                    <div class="timeline-content">
                        <h3>Emplacement Actuel</h3>
                        <p>Déménagement à notre emplacement actuel plus spacieux sur la Rue du Café.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="team-section">
            <h2>Rencontrez Notre Équipe</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="<?php echo $base_url; ?>uploads/chef-1.jpg" alt="Chef Principal">
                    <h3>Mohamed Ali</h3>
                    <p>Chef Principal</p>
                </div>
                
                <div class="team-member">
                    <img src="<?php echo $base_url; ?>uploads/manager.jpg" alt="Directeur Général">
                    <h3>Sarah Ben Ammar</h3>
                    <p>Directrice Générale</p>
                </div>
                
                <div class="team-member">
                    <img src="<?php echo $base_url; ?>uploads/barista.jpg" alt="Maître Barista">
                    <h3>Karim Bouazizi</h3>
                    <p>Maître Barista</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* About Hero */
.about-hero {
    position: relative;
    height: 400px;
    background: url('<?php echo $base_url; ?>uploads/about-bg.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
}

.about-hero .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.about-hero .container {
    position: relative;
    z-index: 1;
}

.about-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 15px;
    font-weight: 300;
    letter-spacing: 1px;
}

.about-hero p {
    font-size: 1.2rem;
    font-weight: 300;
}

/* About Main */
.about-main {
    padding: 80px 0;
    background: #f8f5f0;
}

.about-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: center;
    margin-bottom: 80px;
}

.about-content h2 {
    font-size: 2rem;
    margin-bottom: 25px;
    color: #333;
    font-weight: 400;
}

.about-content p {
    color: #666;
    line-height: 1.8;
    margin-bottom: 30px;
    font-size: 1.05rem;
}

.about-features {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 40px;
}

.feature {
    text-align: center;
    padding: 25px 15px;
    background: white;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
}

.feature i {
    font-size: 2rem;
    color: #c8a97e;
    margin-bottom: 15px;
}

.feature h3 {
    font-size: 1.1rem;
    margin-bottom: 10px;
    color: #333;
}

.feature p {
    font-size: 0.9rem;
    margin-bottom: 0;
    color: #777;
}

.about-image img {
    width: 100%;
    border-radius: 5px;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

/* History Section */
.history-section {
    margin-bottom: 80px;
}

.history-section h2 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 50px;
    color: #333;
    font-weight: 400;
}

.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline::before {
    content: '';
    position: absolute;
    width: 2px;
    background: #c8a97e;
    top: 0;
    bottom: 0;
    left: 50%;
    margin-left: -1px;
}

.timeline-item {
    padding: 20px 40px;
    position: relative;
    width: 50%;
    box-sizing: border-box;
}

.timeline-item:nth-child(odd) {
    left: 0;
}

.timeline-item:nth-child(even) {
    left: 50%;
}

.timeline-year {
    position: absolute;
    width: 100px;
    height: 100px;
    background: #c8a97e;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    top: 20px;
}

.timeline-item:nth-child(odd) .timeline-year {
    right: -170px;
}

.timeline-item:nth-child(even) .timeline-year {
    left: -170px;
}

.timeline-content {
    padding: 30px;
    background: white;
    border-radius: 5px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.timeline-content h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #333;
}

.timeline-content p {
    color: #666;
    line-height: 1.6;
}

/* Team Section */
.team-section {
    text-align: center;
}

.team-section h2 {
    font-size: 2rem;
    margin-bottom: 50px;
    color: #333;
    font-weight: 400;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.team-member {
    background: white;
    padding-bottom: 30px;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s;
}

.team-member:hover {
    transform: translateY(-10px);
}

.team-member img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    margin-bottom: 20px;
}

.team-member h3 {
    font-size: 1.2rem;
    margin-bottom: 5px;
    color: #333;
}

.team-member p {
    color: #c8a97e;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 992px) {
    .about-section {
        grid-template-columns: 1fr;
    }
    
    .about-image {
        order: -1;
    }
    
    .about-features {
        grid-template-columns: 1fr;
    }
    
    .timeline::before {
        left: 40px;
    }
    
    .timeline-item {
        width: 100%;
        padding-left: 70px;
        padding-right: 25px;
    }
    
    .timeline-item:nth-child(even) {
        left: 0;
    }
    
    .timeline-year {
        width: 80px;
        height: 80px;
        font-size: 1rem;
        left: -10px !important;
        right: auto !important;
    }
    
    .team-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .about-hero {
        height: 300px;
    }
    
    .about-hero h1 {
        font-size: 2.5rem;
    }
}
</style>

<?php include 'C:\xampp\htdocs\bellavista\includes\footer.php'; ?>