<?php
require_once 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $silinecek_id = intval($_GET['id']);

    try {
        $stmt = $db->prepare("DELETE FROM iletisim_mesajlari WHERE id = :id");
        $stmt->bindParam(':id', $silinecek_id);
        $stmt->execute();

     
        session_start();
        $_SESSION['silme_basarili'] = "İletişim mesajı başarıyla silindi.";
        header("Location: admin_paneli.php");
        exit();

    } catch (PDOException $e) {
     
        session_start();
        $_SESSION['silme_hata'] = "İletişim mesajı silinirken bir hata oluştu: " . $e->getMessage();
        header("Location: admin_paneli.php");
        exit();
    }
} else {
    
    session_start();
    $_SESSION['silme_hata'] = "Geçersiz mesaj ID'si.";
    header("Location: admin_paneli.php");
    exit();
}
?>