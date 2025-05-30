<?php
session_start(); 

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eposta = trim($_POST['eposta']);
    $sifre = $_POST['sifre'];

    if (empty($eposta) || empty($sifre)) {
        header("Location: giris.php?hata=Lütfen e-posta ve şifrenizi girin.");
        exit();
    }

  
    $stmt_kontrol = $db->prepare("SELECT kullanici_id, sifre, firma_sahibi, ban FROM kullanicilar WHERE eposta = ?");
    $stmt_kontrol->execute([$eposta]);
    $kullanici = $stmt_kontrol->fetch(PDO::FETCH_ASSOC);

    if ($kullanici) {
       
        if ($kullanici['ban'] == 1) {
            header("Location: giris.php?hata=Hesabınız banlanmıştır. Lütfen yöneticilerle iletişime geçin.");
            exit();
        } else {
            
            if ($sifre == $kullanici['sifre']) {
             
                $_SESSION['kullanici_id'] = $kullanici['kullanici_id'];
                $_SESSION['firma_sahibi'] = $kullanici['firma_sahibi'];

                
                if ($kullanici['firma_sahibi'] == 1) {
                    header("Location: index.php"); 
                    exit();
                } else {
                    header("Location: index.php");
                    exit();
                }
            } else {
                
                header("Location: giris.php?hata=Yanlış şifre girdiniz.");
                exit();
            }
        }
    } else {
        
        header("Location: giris.php?hata=Bu e-posta adresine kayıtlı bir kullanıcı bulunamadı.");
        exit();
    }

} else {
 
    header("Location: giris.php");
    exit();
}
?>