<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haydra - Notre Équipe</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --accent-color: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-align: center;
            padding: 3rem 0 4rem;
            position: relative;
            overflow: hidden;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .logo-container {
            margin-bottom: 1.5rem;
        }
        
        .dragon-logo {
            width: 150px;
            height: auto;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.7));
            transition: transform 0.5s ease;
        }
        
        .dragon-logo:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        header h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        header p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .team-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 20px;
        }
        
        .team-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .team-title h2 {
            font-size: 2.5rem;
            color: var(--dark-color);
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }
        
        .team-title h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--secondary-color);
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .team-member {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .member-image {
            height: 300px;
            overflow: hidden;
        }
        
        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .team-member:hover .member-image img {
            transform: scale(1.05);
        }
        
        .member-info {
            padding: 25px;
            text-align: center;
        }
        
        .member-info h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--dark-color);
        }
        
        .member-info p.position {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .member-info p.age {
            color: #777;
            margin-bottom: 15px;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-links a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        footer {
            background: var(--dark-color);
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .contact-btn {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
            border: 2px solid var(--accent-color);
        }
        
        .contact-btn:hover {
            background: transparent;
            color: var(--accent-color);
        }
        
        @media (max-width: 768px) {
            header h1 {
                font-size: 2.5rem;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
            }
            
            .dragon-logo {
                width: 120px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <img src="https://imgs.search.brave.com/kmtScHhKfTueHsX96U-QXoTVfbfj6f6vBkrieUJgMPo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzEwLzQ5LzI5LzEw/LzM2MF9GXzEwNDky/OTEwMzNfTHgzdFJS/ZEZjNW1KWDhIUkV0/YXNrcnlRV09vR2JV/bGwuanBn" alt="Logo Haydra - Three-Headed Dragon" class="dragon-logo">
            </div>
            <h1>Haydra Bits</h1>
            <p>Une équipe jeune et dynamique de développeurs passionnés par la création de solutions innovantes</p>
        </div>
    </header>

    <div class="team-container">
        <div class="team-title">
            <h2>Notre Équipe</h2>
        </div>
        
        <div class="team-grid">
            <!-- Membre 1 -->
            <div class="team-member">
                <div class="member-image">
                    <img src="https://scontent.ftun15-1.fna.fbcdn.net/v/t39.30808-1/419045455_383050521224902_2783104926074659518_n.jpg?stp=dst-jpg_s200x200_tt6&_nc_cat=110&ccb=1-7&_nc_sid=e99d92&_nc_ohc=voDQzGmTmqAQ7kNvwHlY8C5&_nc_oc=AdllnRHyNe9ySrsziu-YNLZ923A5xQ_JbaxXsVL9cl1MtbpMV-rUF1ZqfruxqvvhGmQ&_nc_zt=24&_nc_ht=scontent.ftun15-1.fna&_nc_gid=FKF9cHBcJPbYPrDuc7Qpjg&oh=00_AfMzAZ_70dN4Mg8lh-jzy4-HrdVTMHEsZck5MllDnAEggw&oe=6864DBEF" alt="Mtar Rayane">
                </div>
                <div class="member-info">
                    <h3>Mtar Rayane</h3>
                    <p class="position">Développeur Full-Stack</p>
                    <p class="age">19 ans</p>
                    <p><strong>Téléphone:</strong> +216 12 345 678</p>
                    <div class="social-links">
                        <a href="https://facebook.com/mtar.rayane" target="_blank" title="Facebook"><i>FB</i></a>
                        <a href="https://instagram.com/mtar.rayane" target="_blank" title="Instagram"><i>IG</i></a>
                        <a href="https://linkedin.com/in/mtar-rayane" target="_blank" title="LinkedIn"><i>LI</i></a>
                        <a href="mailto:mtar.rayane@haydra.com" title="Email"><i>✉</i></a>
                    </div>
                    <a href="contact.php?member=mtar" class="contact-btn">Contact</a>
                </div>
            </div>
            
            <!-- Membre 2 -->
            <div class="team-member">
                <div class="member-image">
                    <img src="https://scontent.ftun15-1.fna.fbcdn.net/v/t39.30808-6/487824234_694161376642330_2274680851797059061_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=gPSnc_nl5EoQ7kNvwHyN8EG&_nc_oc=AdmfKhgj3889ZWTRHuKxkCsGNp_g_Tr-AIaFEnQPIQxcjJrT4E7zD8Jg9GV5Ij1TI9c&_nc_zt=23&_nc_ht=scontent.ftun15-1.fna&_nc_gid=vrAiO5F4y4uSpDFpPjef_w&oh=00_AfMvIaHFW7dDvpKba2N7AYu12IPmGxr5YnEegDNtyR34RQ&oe=6864BD1A" alt="Nassim Charaabi">
                </div>
                <div class="member-info">
                    <h3>Nassim Charaabi</h3>
                    <p class="position">Développeur Back-End</p>
                    <p class="age">19 ans</p>
                    <p><strong>Téléphone:</strong> +216 23 456 789</p>
                    <div class="social-links">
                        <a href="https://facebook.com/nassim.charaabi" target="_blank" title="Facebook"><i>FB</i></a>
                        <a href="https://instagram.com/nassim.charaabi" target="_blank" title="Instagram"><i>IG</i></a>
                        <a href="https://linkedin.com/in/nassim-charaabi" target="_blank" title="LinkedIn"><i>LI</i></a>
                        <a href="mailto:nassim.charaabi@haydra.com" title="Email"><i>✉</i></a>
                    </div>
                    <a href="contact.php?member=nassim" class="contact-btn">Contact</a>
                </div>
            </div>
            
            <!-- Membre 3 -->
            <div class="team-member">
                <div class="member-image">
                    <img src="https://scontent.ftun15-1.fna.fbcdn.net/v/t39.30808-1/490654903_1379431153244589_1328918101645009794_n.jpg?stp=cp6_dst-jpg_s200x200_tt6&_nc_cat=101&ccb=1-7&_nc_sid=e99d92&_nc_ohc=TD3cEd-sIaAQ7kNvwFMg7Dn&_nc_oc=Adm-M7Vus74b-FBo025ExPyOVdUf3k22i9XiG4YbNbtCZx9vV1uhuZPVLboSlCaKhnA&_nc_zt=24&_nc_ht=scontent.ftun15-1.fna&_nc_gid=kAg_MdehTTevQAbmHA5-bA&oh=00_AfMdvTU1qW93q1ASwHFXZ576D9aJkVbYIhV0xS9AtW2TSw&oe=6864E464" alt="Mehdi Yedaes">
                </div>
                <div class="member-info">
                    <h3>Mehdi Yedaes</h3>
                    <p class="position">Développeur Front-End</p>
                    <p class="age">19 ans</p>
                    <p><strong>Téléphone:</strong> +216 34 567 890</p>
                    <div class="social-links">
                        <a href="https://facebook.com/mehdi.yedaes" target="_blank" title="Facebook"><i>FB</i></a>
                        <a href="https://instagram.com/mehdi.yedaes" target="_blank" title="Instagram"><i>IG</i></a>
                        <a href="https://linkedin.com/in/mehdi-yedaes" target="_blank" title="LinkedIn"><i>LI</i></a>
                        <a href="mailto:mehdi.yedaes@haydra.com" title="Email"><i>✉</i></a>
                    </div>
                    <a href="contact.php?member=mehdi" class="contact-btn">Contact</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Haydra. Tous droits réservés.</p>
        <p>Une équipe de jeunes développeurs passionnés par la technologie et l'innovation.</p>
    </footer>
</body>
</html>