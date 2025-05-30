<?php
session_start();
require_once 'db.php'; 

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['randevu_id'])) {
    $randevu_id = htmlspecialchars(trim($_POST['randevu_id']));
    $kullanici_id = $_SESSION['kullanici_id'];

    try {
        // Sadece kullanıcının kendi randevusunu iptal edebilmesi için kontrol yap
        $stmt_kontrol = $db->prepare("SELECT randevu_id FROM randevular WHERE randevu_id = :randevu_id AND kullanici_id = :kullanici_id AND aktif_mi = '*'");
        $stmt_kontrol->bindParam(':randevu_id', $randevu_id);
        $stmt_kontrol->bindParam(':kullanici_id', $kullanici_id);
        $stmt_kontrol->execute();

        if ($stmt_kontrol->rowCount() > 0) {
            
            $stmt_iptal = $db->prepare("UPDATE randevular SET aktif_mi = '-', durum = 'İptal Edildi' WHERE randevu_id = :randevu_id");
            $stmt_iptal->bindParam(':randevu_id', $randevu_id);
            $stmt_iptal->execute();

            echo "<p style='color: green;'>Randevunuz başarıyla iptal edilmiştir.</p>";
            header("refresh:2;url=randevular.php");
            exit();
        } else {
            echo "<p style='color: red;'>Hata: Bu randevuyu iptal etme yetkiniz yok veya randevu zaten iptal edilmiş.</p>";
            header("refresh:2;url=randevular.php");
            exit();
        }

    } catch (PDOException $e) {
        echo "Veritabanı hatası: " . $e->getMessage();
    }
} else {
    
    header("Location: randevular.php");
    exit();
}

$db = null; 
?>