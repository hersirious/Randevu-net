<?php
session_start();
require_once 'db.php';

$hata_mesaji = '';


if (isset($_SESSION['admin_id'])) {
    header("Location: admin_paneli.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_adi = htmlspecialchars(trim($_POST['kullanici_adi']));
    $sifre = $_POST['sifre'];

    if (empty($kullanici_adi) || empty($sifre)) {
        $hata_mesaji = "Lütfen kullanıcı adı ve şifrenizi girin.";
    } else {
        try {
            $stmt_giris = $db->prepare("SELECT admin_id FROM adminler WHERE kullanici_adi = :kullanici_adi AND sifre = :sifre");
            $stmt_giris->bindParam(':kullanici_adi', $kullanici_adi);
            $stmt_giris->bindParam(':sifre', $sifre);
            $stmt_giris->execute();
            $admin = $stmt_giris->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                header("Location: admin_paneli.php");
                exit();
            } else {
                $hata_mesaji = "Yanlış kullanıcı adı veya şifre.";
            }
        } catch (PDOException $e) {
            $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Girişi</title>
</head>
<body>
    <h1>Admin Girişi</h1>
    <?php if ($hata_mesaji): ?>
        <p style="color: red;"><?php echo $hata_mesaji; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required><br><br>
        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required><br><br>
        <button type="submit">Giriş</button>
    </form>
</body>
</html>