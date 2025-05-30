<?php
require_once 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = htmlspecialchars(trim($_POST["name"] ?? ''));
    $eposta = htmlspecialchars(trim($_POST["email"] ?? ''));
    $telefon = htmlspecialchars(trim($_POST["phone"] ?? ''));
    $mesaj = htmlspecialchars(trim($_POST["message"] ?? ''));

    if ($ad && $eposta && $mesaj) {
        try {
            $stmt = $db->prepare("INSERT INTO iletisim_mesajlari (ad, eposta, telefon, mesaj) VALUES (:ad, :eposta, :telefon, :mesaj)");
            $stmt->bindParam(':ad', $ad);
            $stmt->bindParam(':eposta', $eposta);
            $stmt->bindParam(':telefon', $telefon);
            $stmt->bindParam(':mesaj', $mesaj);
            $stmt->execute();

            $_SESSION['iletisim_basarili'] = "Mesajınız başarıyla gönderildi.";
        } catch (PDOException $e) {
            $_SESSION['iletisim_hata'] = "Bir hata oluştu: " . $e->getMessage();
        }
    } else {
        $_SESSION['iletisim_hata'] = "Lütfen tüm gerekli alanları doldurun.";
    }

    header("Location: index.php");
    exit();
} else {
    echo "<p>Bu sayfaya doğrudan erişim yasaktır.</p>";
}
