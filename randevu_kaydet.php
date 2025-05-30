<?php
session_start();
require_once 'db.php'; 

if (isset($_SESSION['kullanici_id'])) {
    $kullanici_id = $_SESSION['kullanici_id'];
} else {
    echo "<p style='color: red;'>Hata: Giriş yapmanız gerekmektedir.</p>";
    header("refresh:3;url=giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firma_id']) && isset($_POST['tarih']) && isset($_POST['saat'])) {
    $firma_id_gelen = htmlspecialchars(trim($_POST['firma_id']));
    $tarih = htmlspecialchars(trim($_POST['tarih']));
    $saat = htmlspecialchars(trim($_POST['saat']));

    
    try {
        $stmt_firma = $db->prepare("SELECT firma_adi FROM firmalar WHERE firma_id = :firma_id");
        $stmt_firma->bindParam(':firma_id', $firma_id_gelen);
        $stmt_firma->execute();
        $firma_bilgisi = $stmt_firma->fetch(PDO::FETCH_ASSOC);

        if ($firma_bilgisi && isset($firma_bilgisi['firma_adi'])) {
            $firma_adi = $firma_bilgisi['firma_adi'];

            
            $stmt_kontrol = $db->prepare("SELECT COUNT(*) FROM randevular WHERE kullanici_id = :kullanici_id AND tarih = :tarih AND saat = :saat");
            $stmt_kontrol->bindParam(':kullanici_id', $kullanici_id);
            $stmt_kontrol->bindParam(':tarih', $tarih);
            $stmt_kontrol->bindParam(':saat', $saat);
            $stmt_kontrol->execute();
            $randevu_sayisi = $stmt_kontrol->fetchColumn();

            if ($randevu_sayisi > 0) {
                echo "<p style='color: red;'>Aynı tarih ve saatte zaten bir randevunuz var.</p>";
                header("refresh:3;url=randevu_al.php");
                exit();
            }

            try {
                $stmt = $db->prepare("INSERT INTO randevular (kullanici_id, firma_adi, tarih, saat) VALUES (:kullanici_id, :firma_adi, :tarih, :saat)");
                $stmt->bindParam(':kullanici_id', $kullanici_id);
                $stmt->bindParam(':firma_adi', $firma_adi);
                $stmt->bindParam(':tarih', $tarih);
                $stmt->bindParam(':saat', $saat);
                $stmt->execute();

                echo "<p style='color: green;'>Randevunuz başarıyla alınmıştır.</p>";
                header("refresh:3;url=randevular.php");
                exit();

            } catch (PDOException $e) {
                echo "Veritabanı hatası (randevu kaydetme): " . $e->getMessage();
            }
        } else {
            echo "<p style='color: red;'>Hata: Seçilen firma bulunamadı.</p>";
            header("refresh:3;url=randevu_al.php");
            exit();
        }

    } catch (PDOException $e) {
        echo "Veritabanı hatası (firma adı çekme): " . $e->getMessage();
    }

} else {
    echo "<p style='color: red;'>Hata: Lütfen tüm alanları doldurun.</p>";
    header("refresh:3;url=randevu_al.php");
    exit();
}

$db = null; 
?>