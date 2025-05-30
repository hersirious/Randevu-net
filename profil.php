<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$hata_mesaji = '';
$basari_mesaji = '';

try {

    $stmt = $db->prepare("SELECT ad, soyad, eposta FROM kullanicilar WHERE kullanici_id = :kullanici_id");
    $stmt->bindParam(':kullanici_id', $kullanici_id);
    $stmt->execute();
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$kullanici) {
        $hata_mesaji = "Kullanıcı bilgileri bulunamadı.";
    }

    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guncelle_profil'])) {
        $ad = htmlspecialchars(trim($_POST['ad']));
        $soyad = htmlspecialchars(trim($_POST['soyad']));
        $eposta = htmlspecialchars(trim($_POST['eposta']));

        $stmt_guncelle = $db->prepare("UPDATE kullanicilar SET ad = :ad, soyad = :soyad, eposta = :eposta WHERE kullanici_id = :kullanici_id");
        $stmt_guncelle->bindParam(':ad', $ad);
        $stmt_guncelle->bindParam(':soyad', $soyad);
        $stmt_guncelle->bindParam(':eposta', $eposta);
        $stmt_guncelle->bindParam(':kullanici_id', $kullanici_id);

        if ($stmt_guncelle->execute()) {
            $basari_mesaji = "Profil bilgileriniz başarıyla güncellendi.";
         
            $stmt->execute();
            $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $hata_mesaji = "Profil bilgileri güncellenirken bir hata oluştu.";
        }
    }

   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guncelle_sifre'])) {
    $mevcut_sifre = trim($_POST['mevcut_sifre']);
    $yeni_sifre = trim($_POST['yeni_sifre']);
    $yeni_sifre_tekrar = trim($_POST['yeni_sifre_tekrar']);


    $stmt_sifre = $db->prepare("SELECT sifre FROM kullanicilar WHERE kullanici_id = :kullanici_id");
    $stmt_sifre->bindParam(':kullanici_id', $kullanici_id);
    $stmt_sifre->execute();
    $sonuc = $stmt_sifre->fetch(PDO::FETCH_ASSOC);

    if ($sonuc && $mevcut_sifre === $sonuc['sifre']) {
        if ($yeni_sifre === $yeni_sifre_tekrar) {
            $stmt_yeni_sifre = $db->prepare("UPDATE kullanicilar SET sifre = :yeni_sifre WHERE kullanici_id = :kullanici_id");
            $stmt_yeni_sifre->bindParam(':yeni_sifre', $yeni_sifre);
            $stmt_yeni_sifre->bindParam(':kullanici_id', $kullanici_id);

            if ($stmt_yeni_sifre->execute()) {
                $basari_mesaji = "Şifreniz başarıyla güncellendi.";
            } else {
                $hata_mesaji = "Şifre güncellenirken bir hata oluştu.";
            }
        } else {
            $hata_mesaji = "Yeni şifreler eşleşmiyor.";
        }
    } else {
        $hata_mesaji = "Mevcut şifre yanlış.";
    }
}

} catch (PDOException $e) {
    $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
}


$firma_sahibi = isset($_SESSION['firma_sahibi']) && $_SESSION['firma_sahibi'] == 1;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNet - Profil</title>
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
display: flex;
flex-direction: column;
min-height: 100vh; 
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    flex: 1; 
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



footer {
    background-color: #222;
    color: white;
    padding: 50px 0 20px;
    text-align: center;
    margin-top: auto; 
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
       
        .profil-form {
            background-color: #fff;
            padding: 120px;
            width: 1000px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
    
            margin-left: auto;
            margin-right: 150px;
        }
        .profil-form h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: flex;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-group button {
            background-color: #4a6cf7;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #365bd6;
        }
        .hata-mesaji {
            color: red;
            margin-bottom: 10px;
        }
        .basari-mesaji {
            color: green;
            margin-bottom: 10px;
        }
        .sifre-degistir-bolumu {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .sifre-degistir-bolumu h3 {
            color: #333;
            margin-bottom: 15px;
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

    <div class="container">
        <main class="content">
            <h2>Profil Bilgileri</h2>

            <?php if ($hata_mesaji): ?>
                <div class="hata-mesaji"><?php echo htmlspecialchars($hata_mesaji); ?></div>
            <?php endif; ?>

            <?php if ($basari_mesaji): ?>
                <div class="basari-mesaji"><?php echo htmlspecialchars($basari_mesaji); ?></div>
            <?php endif; ?>

            <?php if ($kullanici): ?>
                <form method="post" class="profil-form">
                    <h2>Profilini Düzenle</h2>
                    <div class="form-group">
                        <label for="ad">Ad:</label>
                        <input type="text" id="ad" name="ad" value="<?php echo htmlspecialchars($kullanici['ad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="soyad">Soyad:</label>
                        <input type="text" id="soyad" name="soyad" value="<?php echo htmlspecialchars($kullanici['soyad']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="eposta">E-posta:</label>
                        <input type="email" id="eposta" name="eposta" value="<?php echo htmlspecialchars($kullanici['eposta']); ?>" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="guncelle_profil">Bilgileri Güncelle</button>
                    </div>
                </form>

                <div class="profil-form sifre-degistir-bolumu">
                    <h3>Şifre Değiştir</h3>
                    <form method="post">
                        <div class="form-group">
                            <label for="mevcut_sifre">Mevcut Şifre:</label>
                            <input type="password" id="mevcut_sifre" name="mevcut_sifre" required>
                        </div>
                        <div class="form-group">
                            <label for="yeni_sifre">Yeni Şifre:</label>
                            <input type="password" id="yeni_sifre" name="yeni_sifre" required>
                        </div>
                        <div class="form-group">
                            <label for="yeni_sifre_tekrar">Yeni Şifre Tekrar:</label>
                            <input type="password" id="yeni_sifre_tekrar" name="yeni_sifre_tekrar" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="guncelle_sifre">Şifreyi Değiştir</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <p><?php echo htmlspecialchars($hata_mesaji); ?></p>
            <?php endif; ?>
        </main>
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