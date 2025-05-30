<?php

require_once 'db.php';


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ad = htmlspecialchars(trim($_POST["ad"]));
        $soyad = htmlspecialchars(trim($_POST["soyad"]));
        $eposta = htmlspecialchars(trim($_POST["eposta"]));
        $sifre = $_POST["sifre"]; 
        $firma_sahibi = isset($_POST["firma_sahibi"]) ? 1 : 0;
        $firma_adi = $firma_sahibi == 1 && isset($_POST["firma_adi"]) ? htmlspecialchars(trim($_POST["firma_adi"])) : null;
        $firma_meslek = $firma_sahibi == 1 && isset($_POST["firma_meslek"]) ? htmlspecialchars(trim($_POST["firma_meslek"])) : null;

        
        $stmt_kontrol = $conn->prepare("SELECT COUNT(*) FROM kullanicilar WHERE eposta = :eposta");
        $stmt_kontrol->bindParam(':eposta', $eposta);
        $stmt_kontrol->execute();
        if ($stmt_kontrol->fetchColumn() > 0) {
            echo "<p style='color: red;'>Bu e-posta adresi zaten kayıtlı.</p>";
            header("refresh:2;url=kayit.php");
            exit();
        }

        
        $stmt_kullanici = $conn->prepare("INSERT INTO kullanicilar (ad, soyad, eposta, sifre, firma_sahibi, firma_adi, firma_meslek) VALUES (:ad, :soyad, :eposta, :sifre, :firma_sahibi, :firma_adi, :firma_meslek)");
        $stmt_kullanici->bindParam(':ad', $ad);
        $stmt_kullanici->bindParam(':soyad', $soyad);
        $stmt_kullanici->bindParam(':eposta', $eposta);
        $stmt_kullanici->bindParam(':sifre', $sifre); 
        $stmt_kullanici->bindParam(':firma_sahibi', $firma_sahibi);
        $stmt_kullanici->bindParam(':firma_adi', $firma_adi);
        $stmt_kullanici->bindParam(':firma_meslek', $firma_meslek);
        $stmt_kullanici->execute();
        $kullanici_id = $conn->lastInsertId(); 

        
        if ($firma_sahibi == 1 && $firma_adi) {
            $stmt_firma_kontrol = $conn->prepare("SELECT COUNT(*) FROM firmalar WHERE firma_adi = :firma_adi");
            $stmt_firma_kontrol->bindParam(':firma_adi', $firma_adi);
            $stmt_firma_kontrol->execute();
            if ($stmt_firma_kontrol->fetchColumn() == 0) {
                $stmt_firma = $conn->prepare("INSERT INTO firmalar (firma_adi, firma_sahibi_id) VALUES (:firma_adi, :firma_sahibi_id)");
                $stmt_firma->bindParam(':firma_adi', $firma_adi);
                $stmt_firma->bindParam(':firma_sahibi_id', $kullanici_id);
                $stmt_firma->execute();
            }
        }

        echo "<p style='color: green;'>Kayıt başarıyla tamamlandı. Giriş sayfasına yönlendiriliyorsunuz.</p>";
        header("refresh:2;url=giris.php");
        exit();

    } else {
        
        header("Location: kayit.php");
        exit();
    }

} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}

$conn = null;
?>