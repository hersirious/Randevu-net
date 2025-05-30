<?php
session_start();
require_once 'db.php'; // Veritabanı bağlantısını dahil et

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];

try {
    // Kullanıcının aktif ('aktif_mi' = '*') randevularını çek
    $stmt = $db->prepare("SELECT r.randevu_id, r.firma_adi, r.tarih, r.saat, r.durum
                           FROM randevular r
                           WHERE r.kullanici_id = :kullanici_id AND r.aktif_mi = '*'
                           ORDER BY r.tarih ASC, r.saat ASC");
    $stmt->bindParam(':kullanici_id', $kullanici_id);
    $stmt->execute();
    $randevular = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
    $randevular = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNet - Randevularım</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Genel Stiller (index.php'deki stillerin aynısı) */
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
    padding-top: 70px; /* Sabit header için boşluk */
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Sayfanın en az ekran yüksekliği kadar olmasını sağlar */
}
        .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px; /* Genel padding */
    flex: 1; /* Container'ın mümkün olduğunca büyümesini sağlar */
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
        /* Dropdown Menü Stilleri */
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
        /* Randevular Sayfası Stilleri */
        .randevular-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .randevular-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .randevular-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .randevular-container th, .randevular-container td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .randevular-container th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .randevular-container tr:hover {
            background-color: #f9f9f9;
        }
        .iptal-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .iptal-button:hover {
            background-color: #e04040;
        }
        .durum-beklemede {
            color: orange;
        }
        .durum-kabul-edildi {
            color: green;
        }
        .durum-iptal-edildi {
            color: red;
            font-style: italic;
        }
        .durum-tamamlandi {
            color: blue;
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

    <div class="container randevular-container">
        <h2>My Appointments</h2>
        <?php if (empty($randevular)): ?>
            <p>You have not booked any appointments yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                   <th>Company Name</th>
                   <th>Date</th>
                   <th>Time</th>
                   <th>Status</th>
                   <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($randevular as $randevu): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($randevu['firma_adi']); ?></td>
                            <td><?php echo htmlspecialchars($randevu['tarih']); ?></td>
                            <td><?php echo htmlspecialchars($randevu['saat']); ?></td>
                            <td class="durum-<?php echo strtolower(str_replace(' ', '-', $randevu['durum'])); ?>"><?php echo htmlspecialchars($randevu['durum']); ?></td>
                            <td>
                                <form method="post" action="randevu_iptal.php">
                                    <input type="hidden" name="randevu_id" value="<?php echo $randevu['randevu_id']; ?>">
                                    <button type="submit" class="iptal-button">İptal Et</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

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