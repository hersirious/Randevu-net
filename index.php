<?php
session_start();

function temizle($veri) {
    return htmlspecialchars(stripslashes(trim($veri)));
}

$kullanici_id = isset($_SESSION['kullanici_id']) ? $_SESSION['kullanici_id'] : null;
$firma_sahibi = isset($_SESSION['firma_sahibi']) ? $_SESSION['firma_sahibi'] : 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNet - Ana Sayfa</title>
    <link rel="stylesheet" href="style.css">
    <style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
     
        html {
            scroll-behavior: smooth;
        }
        body {
            color: #333;
            line-height: 1.6;
            scroll-behavior: smooth;
            padding-top: 70px; 
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

       
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center; 
            padding: 20px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4a6cf7;
        }

        .nav-links {
            display: flex;
            list-style: none;
            align-items: center; 
        }

        .nav-links li {
            margin-left: 30px;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #4a6cf7;
        }

       .hero {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            margin-top: 70px;
            padding: 100px 0; 
        }

        .hero .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; 
            gap: 40px;
        }

        .hero-content {
            flex: 1;
            padding-right: 20px;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #222;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #555;
        }

        .cta-button {
            display: inline-block;
            background-color: #4a6cf7;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .cta-button:hover {
            background-color: #3a5bd9;
        }

        .hero-image {
            flex: 1;
            max-width: 500px;
            text-align: right;
        }

        .hero-image img {
            width: 50%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* Bölümler */
        section {
            padding: 100px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 36px;
            color: #222;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #777;
            max-width: 700px;
            margin: 0 auto;
        }

        
        .about-content {
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .about-text {
            flex: 1;
        }

        .about-image {
            flex: 1;
            text-align: center;
        }

        .about-image img {
            max-width: 100%;
            border-radius: 10px;
        }

    
        .services {
            background-color: #f9fafc;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card h3 {
            font-size: 22px;
            margin: 20px 0 15px;
        }

        
        .contact-form {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 150px;
        }

       
        footer {
            background-color: #222;
            color: white;
            padding: 50px 0 20px;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 20px 0;
        }

        .footer-links li {
            margin: 0 15px;
        }

        .footer-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }

        
        .dropdown {
            position: relative;
            display: flex; 
            align-items: center; 
        }

        .dropbtn {
            color: #333;
            padding: 14px 15px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            background: none;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            left: 0;
            top: 100%;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            white-space: nowrap;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            color: #4a6cf7;
        }

        
        .kullanici-bilgi {
            color: #4a6cf7;
            font-weight: 600;
            padding: 14px 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <nav>
            <div class="logo">RandevuNet</div>
            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
                <li><a href="randevular.php">My Appointments</a></li>
                <li><a href="randevu_al.php">Book Appointment</a></li>
                <?php if(isset($_SESSION['kullanici_id'])): ?>
                    <li class="dropdown">
                        <a href="#" class="dropbtn" style="color: #4a6cf7; font-weight: 600;">Profile</a>
                        <div class="dropdown-content">
                            <?php if($firma_sahibi == 1): ?>
                                <a href="firma_paneli.php">Dashboard</a>
                            <?php endif; ?>
                            <a href="profil.php">My Information</a>
                            <a href="cikis.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="kayit.php">Register</a></li>
                    <li><a href="giris.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<section id="home" class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Professional Appointment Management System</h1>
            <p>Manage your business’s appointment processes easily with RandevuNet. Let your customers book appointments online while you use your time efficiently.</p>
            <a href="giris.php" class="cta-button">Get Started Now</a>
        </div>
        <div class="hero-image">
            <img src="resimler/randevunetuygu.png" alt="RandevuNet Application">
        </div>
    </div>
</section>


    <section id="about" class="about">
        <div class="container">
            <div class="section-title">
                <h2>About</h2>
                <p>As RandevuNet, our goal is to digitize businesses' appointment processes and enhance their efficiency.</p
            </div>
            <div class="about-content">
                <div class="about-text">
                    <p>Since 2025, we have been providing solutions for businesses in various industries to manage their appointment needs. With our expert team and advanced technology, we ensure that you offer the best experience to your customers.</p>
<p>To date, we have served many businesses and have always prioritized customer satisfaction. With our innovative solutions, we hold a leading position in the industry.</p>

                </div>
                <div class="about-image">
                    <img src="resimler/ekip.jpg" alt="RandevuNet Ekibi">
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>We offer tailored solutions to meet the needs of your business.</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <img src="resimler/home-button.png" style="width: 15%; margin-left: 40%;">
                  <h3>Online Appointment System</h3>
                  <p>Allow your customers to book appointments online 24/7. Appointments are automatically added to your calendar.</p>
                </div>
                <div class="service-card">
                        <img src="resimler/credit-card.png" style="width: 15%; margin-left: 40%;">
                   <h3>Integrated Payment System</h3>
                   <p>We offer secure payment solutions for online collection of appointment fees.</p> /* not made */
                </div>
                <div class="service-card">
                        <img src="resimler/iphone.png" style="width: 15%; margin-left: 40%;">
                    <h3>Mobile Compatibility</h3>
                    <p>Provide seamless service to your customers with responsive designs accessible from all devices.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section-title">
               <h2>Contact</h2>
               <p>Get in touch with us.</p>
            </div>
            <div class="contact-form">
                <form action="iletisim_gonder.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="cta-button" style="border: none; cursor: pointer;">Gönder</button>
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="logo">RandevuNet</div>
            <ul class="footer-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About us</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="copyright">
                &copy; 2025 RandevuNet. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>