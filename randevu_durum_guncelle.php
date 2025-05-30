<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['kullanici_id']) || !isset($_SESSION['firma_sahibi']) || $_SESSION['firma_sahibi'] != 1) {
    header("Location: giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['randevu_id']) && isset($_POST['durum'])) {
    $randevu_id = htmlspecialchars(trim($_POST['randevu_id']));
    $yeni_durum = htmlspecialchars(trim($_POST['durum']));
    $firma_sahibi_id = $_SESSION['kullanici_id'];

    
    try {
        $stmt_kontrol = $db->prepare("
            SELECT r.randevu_id
            FROM randevular r
            JOIN firmalar f ON r.firma_adi = f.firma_adi
            WHERE r.randevu_id = :randevu_id AND f.firma_sahibi_id = :firma_sahibi_id
        ");
        $stmt_kontrol->bindParam(':randevu_id', $randevu_id);
        $stmt_kontrol->bindParam(':firma_sahibi_id', $firma_sahibi_id);
        $stmt_kontrol->execute();

        if ($stmt_kontrol->rowCount() > 0) {
            
            if ($yeni_durum == 'İptal Edildi') {
                $stmt_guncelle = $db->prepare("UPDATE randevular SET durum = :durum WHERE randevu_id = :randevu_id");
                $stmt_guncelle->bindParam(':durum', $yeni_durum);
                $stmt_guncelle->bindParam(':randevu_id', $randevu_id);
                $stmt_guncelle->execute();

                echo "<p style='color: green;'>Randevu durumu başarıyla güncellendi: " . htmlspecialchars($yeni_durum) . "</p>";
                header("refresh:2;url=firma_paneli.php?sayfa=randevular");
                exit();
            } else {
                
                $stmt_guncelle = $db->prepare("UPDATE randevular SET durum = :durum WHERE randevu_id = :randevu_id");
                $stmt_guncelle->bindParam(':durum', $yeni_durum);
                $stmt_guncelle->bindParam(':randevu_id', $randevu_id);
                $stmt_guncelle->execute();

                echo "<p style='color: green;'>Randevu durumu başarıyla güncellendi: " . htmlspecialchars($yeni_durum) . "</p>";
                header("refresh:2;url=firma_paneli.php?sayfa=randevular");
                exit();
            }
        } else {
            echo "<p style='color: red;'>Hata: Bu randevuyu güncelleme yetkiniz yok.</p>";
            header("refresh:2;url=firma_paneli.php?sayfa=randevular");
            exit();
        }

    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
} else {
    header("Location: firma_paneli.php?sayfa=randevular");
    exit();
}

$db = null;
?>