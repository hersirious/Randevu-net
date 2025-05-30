<?php
session_start();
require_once 'db.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_giris.php");
    exit();
}


if (isset($_SESSION['silme_basarili'])) {
    echo "<p class='basari'>" . htmlspecialchars($_SESSION['silme_basarili']) . "</p>";
    unset($_SESSION['silme_basarili']);
}

if (isset($_SESSION['silme_hata'])) {
    echo "<p class='hata'>" . htmlspecialchars($_SESSION['silme_hata']) . "</p>";
    unset($_SESSION['silme_hata']);
}

try {
    
    $stmt_kullanicilar = $db->prepare("SELECT * FROM kullanicilar");
    $stmt_kullanicilar->execute();
    $kullanicilar = $stmt_kullanicilar->fetchAll(PDO::FETCH_ASSOC);

   
    if (isset($_POST['banla_kullanici_id']) && is_numeric($_POST['banla_kullanici_id'])) {
        $banlanacak_id = intval($_POST['banla_kullanici_id']);
        $stmt_banla = $db->prepare("UPDATE kullanicilar SET ban = 1 WHERE kullanici_id = :id");
        $stmt_banla->bindParam(':id', $banlanacak_id);
        if ($stmt_banla->execute()) {
            $ban_basari_mesaji = "Kullanıcı başarıyla banlandı.";
          
            $stmt_kullanicilar->execute();
            $kullanicilar = $stmt_kullanicilar->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $ban_hata_mesaji = "Kullanıcı banlanırken bir hata oluştu.";
        }
    }

    
    if (isset($_POST['ban_kaldir_kullanici_id']) && is_numeric($_POST['ban_kaldir_kullanici_id'])) {
        $kaldirilacak_id = intval($_POST['ban_kaldir_kullanici_id']);
        $stmt_kaldir = $db->prepare("UPDATE kullanicilar SET ban = 0 WHERE kullanici_id = :id");
        $stmt_kaldir->bindParam(':id', $kaldirilacak_id);
        if ($stmt_kaldir->execute()) {
            $ban_kaldirma_basari_mesaji = "Kullanıcının banı kaldırıldı.";
       
            $stmt_kullanicilar->execute();
            $kullanicilar = $stmt_kullanicilar->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $ban_kaldirma_hata_mesaji = "Kullanıcının banı kaldırılırken bir hata oluştu.";
        }
    }

    
    $stmt_iletisim = $db->prepare("SELECT * FROM iletisim_mesajlari ORDER BY olusturma_zamani DESC");
    $stmt_iletisim->execute();
    $iletisim_mesajlari = $stmt_iletisim->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $hata_mesaji = "Veritabanı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Paneli</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .banli { color: red; font-weight: bold; }
        .basari { color: green; }
        .hata { color: red; }
        .action-buttons button { margin-right: 5px; padding: 5px 10px; cursor: pointer; }
        .iletisim-mesajlari { margin-top: 20px; border: 1px solid #ddd; border-radius: 5px; padding: 15px; }
        .mesaj { margin-bottom: 15px; padding: 10px; border: 1px solid #eee; border-radius: 3px; background-color: #f9f9f9; display: flex; justify-content: space-between; align-items: center; }
        .mesaj p { margin: 5px 0; }
        .mesaj .bilgi { font-size: 0.9em; color: #777; }
        .sil-butonu { background-color: #f44336; color: white; border: none; padding: 8px 12px; text-align: center; text-decoration: none; display: inline-block; font-size: 14px; cursor: pointer; border-radius: 3px; margin-left: 10px; }
    </style>
</head>
<body>
    <h1>Admin Paneli</h1>

    <?php if (isset($hata_mesaji)): ?>
        <p class="hata"><?php echo htmlspecialchars($hata_mesaji); ?></p>
    <?php endif; ?>

    <?php if (isset($ban_basari_mesaji)): ?>
        <p class="basari"><?php echo htmlspecialchars($ban_basari_mesaji); ?></p>
    <?php endif; ?>

    <?php if (isset($ban_hata_mesaji)): ?>
        <p class="hata"><?php echo htmlspecialchars($ban_hata_mesaji); ?></p>
    <?php endif; ?>

    <?php if (isset($ban_kaldirma_basari_mesaji)): ?>
        <p class="basari"><?php echo htmlspecialchars($ban_kaldirma_basari_mesaji); ?></p>
    <?php endif; ?>

    <?php if (isset($ban_kaldirma_hata_mesaji)): ?>
        <p class="hata"><?php echo htmlspecialchars($ban_kaldirma_hata_mesaji); ?></p>
    <?php endif; ?>

    <h2>Kullanıcı Listesi</h2>
    <?php if (!empty($kullanicilar)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>E-posta</th>
                    <th>Şifre</th>
                    <th>Firma Sahibi</th>
                    <th>Ban Durumu</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kullanicilar as $kullanici): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($kullanici['kullanici_id']); ?></td>
                        <td><?php echo htmlspecialchars($kullanici['ad']); ?></td>
                        <td><?php echo htmlspecialchars($kullanici['soyad']); ?></td>
                        <td><?php echo htmlspecialchars($kullanici['eposta']); ?></td>
                        <td><?php echo htmlspecialchars($kullanici['sifre']); ?></td>
                        <td><?php echo htmlspecialchars($kullanici['firma_sahibi'] ? 'Evet' : 'Hayır'); ?></td>
                        <td class="<?php echo $kullanici['ban'] ? 'banli' : ''; ?>">
                            <?php echo $kullanici['ban'] ? 'Banlı' : 'Bansız'; ?>
                        </td>
                        <td class="action-buttons">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="banla_kullanici_id" value="<?php echo htmlspecialchars($kullanici['kullanici_id']); ?>">
                                <button type="submit" onclick="return confirm('Bu kullanıcıyı banlamak istediğinizden emin misiniz?')">Banla</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="ban_kaldir_kullanici_id" value="<?php echo htmlspecialchars($kullanici['kullanici_id']); ?>">
                                <button type="submit" onclick="return confirm('Bu kullanıcının banını kaldırmak istediğinizden emin misiniz?')">Ban Kaldır</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Kullanıcı bulunamadı.</p>
    <?php endif; ?>

    <h2>İletişim Mesajları</h2>
    <?php if (!empty($iletisim_mesajlari)): ?>
        <div class="iletisim-mesajlari">
            <?php foreach ($iletisim_mesajlari as $mesaj): ?>
                <div class="mesaj">
                    <div class="mesaj-icerik">
                        <p><strong>Ad:</strong> <?php echo htmlspecialchars($mesaj['ad']); ?></p>
                        <p><strong>E-posta:</strong> <?php echo htmlspecialchars($mesaj['eposta']); ?></p>
                        <?php if (!empty($mesaj['telefon'])): ?>
                            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($mesaj['telefon']); ?></p>
                        <?php endif; ?>
                        <p><strong>Mesaj:</strong><br><?php echo nl2br(htmlspecialchars($mesaj['mesaj'])); ?></p>
                        <p class="bilgi">Gönderildi: <?php echo htmlspecialchars($mesaj['olusturma_zamani']); ?></p>
                    </div>
                    <a href="iletisim_sil.php?id=<?php echo htmlspecialchars($mesaj['id']); ?>" class="sil-butonu" onclick="return confirm('Bu mesajı silmek istediğinizden emin misiniz?')">Sil</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Henüz iletişim mesajı bulunmuyor.</p>
    <?php endif; ?>

    <p><a href="admin_giris.php">Admin Giriş Sayfasına Geri Dön</a></p>
</body>
</html>