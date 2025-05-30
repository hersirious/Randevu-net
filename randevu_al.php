<?php
session_start();
require_once 'db.php'; 

function temizle($veri) {
    return htmlspecialchars(stripslashes(trim($veri)));
}

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = isset($_SESSION['kullanici_id']) ? $_SESSION['kullanici_id'] : null;
$firma_sahibi = isset($_SESSION['firma_sahibi']) ? $_SESSION['firma_sahibi'] : 0;

$firmalar = []; 

try {
    $stmt = $db->prepare("SELECT firma_id, firma_adi FROM firmalar ORDER BY firma_adi ASC");
    $stmt->execute();
    $firmalar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNet - Book an Appointment</title>
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
            padding: 20px; 
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
        
        .randevu-form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .randevu-form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .form-group input[type="date"],
        .form-group input[type="time"],
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .form-group button {
            background-color: #4a6cf7;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #3a5bd9;
        }
       
        footer {
            background-color: #222;
            color: white;
            padding: 50px 0 20px;
            text-align: center;
            margin-top: 50px; 
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

    <div class="container randevu-form-container">
        <h2>Randevu Al</h2>
        <form action="randevu_kaydet.php" method="post">
            <div class="form-group">
                <label for="firma_id">Firma Adı</label>
                <select id="firma_id" name="firma_id" required>
                    <option value="">Lütfen bir firma seçin</option>
                    <?php foreach ($firmalar as $firma): ?>
                        <option value="<?php echo $firma['firma_id']; ?>"><?php echo htmlspecialchars($firma['firma_adi']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tarih">Tarih</label>
                <input type="date" id="tarih" name="tarih" required>
            </div>
            <div class="form-group">
                <label for="saat">Saat</label>
                <input type="time" id="saat" name="saat" required>
            </div>
            <div class="form-group">
                <button type="submit">Randevu Al</button>
            </div>
        </form>
    </div>

    <footer>
        <div class="container">
            <div class="logo">RandevuNet</div>
            <ul class="footer-links">
                <li><a href="index.php#home">Ana Sayfa</a></li>
                <li><a href="index.php#about">Hakkımızda</a></li>
                <li><a href="index.php#services">Hizmetler</a></li>
                <li><a href="index.php#contact">İletişim</a></li>
            </ul>
            <div class="copyright">
                &copy; 2025 RandevuNet. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>
</body>
</html>