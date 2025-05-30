<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['kullanici_id']) || !isset($_SESSION['firma_sahibi']) || $_SESSION['firma_sahibi'] != 1) {
    header("Location: giris.php");
    exit();
}

$firma_sahibi_id = $_SESSION['kullanici_id'];

try {
  
    $stmt_randevular = $db->prepare("
        SELECT
            r.randevu_id,
            r.tarih,
            r.saat,
            r.durum,
            k.ad AS kullanici_adi,
            k.soyad AS kullanici_soyadi,
            k.telefon AS kullanici_telefon
        FROM
            randevular r
        JOIN
            kullanicilar k ON r.kullanici_id = k.kullanici_id
        JOIN
            firmalar f ON r.firma_adi = f.firma_adi
        WHERE
            f.firma_sahibi_id = :firma_sahibi_id AND r.aktif_mi = '*'
        ORDER BY
            r.tarih ASC, r.saat ASC
    ");
    $stmt_randevular->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_randevular->execute();
    $firma_randevulari = $stmt_randevular->fetchAll(PDO::FETCH_ASSOC);

    
    $stmt_firma = $db->prepare("SELECT firma_adi FROM firmalar WHERE firma_sahibi_id = :firma_sahibi_id");
    $stmt_firma->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_firma->execute();
    $firma = $stmt_firma->fetch(PDO::FETCH_ASSOC);
    $firma_adi = $firma['firma_adi'] ?? 'Bilinmeyen Firma';

    $stmt_toplam = $db->prepare("
        SELECT COUNT(*) FROM randevular r
        JOIN firmalar f ON r.firma_adi = f.firma_adi
        WHERE f.firma_sahibi_id = :firma_sahibi_id
    ");
    $stmt_toplam->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_toplam->execute();
    $toplam_randevu = $stmt_toplam->fetchColumn();

    $stmt_aktif = $db->prepare("
        SELECT COUNT(*) FROM randevular r
        JOIN firmalar f ON r.firma_adi = f.firma_adi
        WHERE f.firma_sahibi_id = :firma_sahibi_id AND r.durum = 'Beklemede'
    ");
    $stmt_aktif->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_aktif->execute();
    $aktif_randevu = $stmt_aktif->fetchColumn();

    $stmt_tamamlanan = $db->prepare("
        SELECT COUNT(*) FROM randevular r
        JOIN firmalar f ON r.firma_adi = f.firma_adi
        WHERE f.firma_sahibi_id = :firma_sahibi_id AND r.durum = 'Tamamlandı'
    ");
    $stmt_tamamlanan->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_tamamlanan->execute();
    $tamamlanan_randevu = $stmt_tamamlanan->fetchColumn();

    $stmt_iptal = $db->prepare("
        SELECT COUNT(*) FROM randevular r
        JOIN firmalar f ON r.firma_adi = f.firma_adi
        WHERE f.firma_sahibi_id = :firma_sahibi_id AND r.durum = 'İptal Edildi'
    ");
    $stmt_iptal->bindParam(':firma_sahibi_id', $firma_sahibi_id);
    $stmt_iptal->execute();
    $iptal_randevu = $stmt_iptal->fetchColumn();

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
    $firma_adi = 'Hata';
    $toplam_randevu = 0;
    $aktif_randevu = 0;
    $tamamlanan_randevu = 0;
    $iptal_randevu = 0;
    $firma_randevulari = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNet - Firma Paneli</title>
    <link rel="stylesheet" href="style.css">
    <style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        html {
            height: 100%;
        }
        body {
            background-color: #f4f6f8;
            color: #333;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            max-width: 100%;
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
        }
       
        .sidebar {
            background-color: #2c3e50;
            color: white;
            width: 250px;
            padding: 20px;
            border-radius: 8px 0 0 8px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .sidebar-logo {
            font-size: 24px;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 30px;
            text-decoration: none;
            align-self: center;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            width: 100%;
        }
        .sidebar-menu li {
            margin-bottom: 15px;
        }
        .sidebar-menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }
        .sidebar-menu a:hover {
            background-color: #34495e;
        }

       
        .content {
            flex: 1;
            padding: 20px;
        }
        .content h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .randevu-tablo {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .randevu-tablo th, .randevu-tablo td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .randevu-tablo th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .randevu-tablo tr:hover {
            background-color: #f9f9f9;
        }
        .iptal-button, .kabul-button, .tamamlandi-button {
            background-color: #ff4d4d; 
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-right: 5px;
        }
        .kabul-button {
            background-color: #5cb85c; 
        }
        .tamamlandi-button {
            background-color: #5bc0de; 
        }
        .iptal-button:hover {
            background-color: #e04040;
        }
        .kabul-button:hover {
            background-color: #4cae4c;
        }
        .tamamlandi-button:hover {
            background-color: #46b8da;
        }
        .durum-beklemede { color: orange; }
        .durum-kabul-edildi { color: green; }
        .durum-iptal-edildi { color: red; font-style: italic; }
        .durum-tamamlandi { color: blue; }

        /* İstatistik Stilleri */
        .istatistik-kartlar {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .istatistik-kart {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(50% - 10px); 
            min-width: 300px;
        }
        .istatistik-kart h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .istatistik-deger {
            font-size: 24px;
            font-weight: bold;
            color: #4a6cf7;
        }
        @media (max-width: 600px) {
            .istatistik-kart {
                width: 100%; 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-logo">RandevuNet</a>
            <ul class="sidebar-menu">
                <li><a href="firma_paneli.php?sayfa=randevular" class="<?php if (!isset($_GET['sayfa']) || $_GET['sayfa'] == 'randevular') echo 'active'; ?>">Randevular</a></li>
                <li><a href="firma_paneli.php?sayfa=istatistik" class="<?php if (isset($_GET['sayfa']) && $_GET['sayfa'] == 'istatistik') echo 'active'; ?>">İstatistik</a></li>
                <li><a href="cikis.php">Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="content">
            <?php
            if (isset($_GET['sayfa'])) {
                $sayfa = $_GET['sayfa'];
                if ($sayfa == 'randevular') {
                    echo "<h2>Randevular</h2>";
                    if (empty($firma_randevulari)): ?>
                        <p>Henüz bir randevunuz bulunmamaktadır.</p>
                    <?php else: ?>
                        <table class="randevu-tablo">
                            <thead>
                                <tr>
                                    <th>Randevu ID</th>
                                    <th>Tarih</th>
                                    <th>Saat</th>
                                    <th>Kullanıcı Adı</th>
                                    <th>Kullanıcı Soyadı</th>
                                    <th>Kullanıcı Telefon</th>
                                    <th>Durum</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($firma_randevulari as $randevu): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($randevu['randevu_id']); ?></td>
                                        <td><?php echo htmlspecialchars($randevu['tarih']); ?></td>
                                        <td><?php echo htmlspecialchars($randevu['saat']); ?></td>
                                        <td><?php echo htmlspecialchars($randevu['kullanici_adi']); ?></td>
                                        <td><?php echo htmlspecialchars($randevu['kullanici_soyadi']); ?></td>
                                        <td><?php echo htmlspecialchars($randevu['kullanici_telefon']); ?></td>
                                        <td class="durum-<?php echo strtolower(str_replace(' ', '-', $randevu['durum'])); ?>"><?php echo htmlspecialchars($randevu['durum']); ?></td>
                                        <td>
                                            <form method="post" action="randevu_durum_guncelle.php" style="display: inline;">
                                                <input type="hidden" name="randevu_id" value="<?php echo $randevu['randevu_id']; ?>">
                                                <input type="hidden" name="durum" value="Kabul Edildi">
                                                <button type="submit" class="kabul-button">Kabul Et</button>
                                            </form>
                                            <form method="post" action="randevu_durum_guncelle.php" style="display: inline;">
                                                <input type="hidden" name="randevu_id" value="<?php echo $randevu['randevu_id']; ?>">
                                                <input type="hidden" name="durum" value="Tamamlandı">
                                                <button type="submit" class="tamamlandi-button">Tamamlandı</button>
                                            </form>
                                            <form method="post" action="randevu_durum_guncelle.php" style="display: inline;">
                                                <input type="hidden" name="randevu_id" value="<?php echo $randevu['randevu_id']; ?>">
                                                <input type="hidden" name="durum" value="İptal Edildi">
                                                <button type="submit" class="iptal-button">İptal Et</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif;
                } elseif ($sayfa == 'istatistik') {
                    echo "<h2>" . htmlspecialchars($firma_adi) . " İstatistikleri</h2>";
                    ?>
                    <div class="istatistik-kartlar">
                        <div class="istatistik-kart">
                            <h3>Toplam Randevu</h3>
                            <div class="istatistik-deger"><?php echo htmlspecialchars($toplam_randevu); ?></div>
                        </div>
                        <div class="istatistik-kart">
                            <h3>Aktif Randevular</h3>
                            <div class="istatistik-deger"><?php echo htmlspecialchars($aktif_randevu); ?></div>
                        </div>
                        <div class="istatistik-kart">
                            <h3>Tamamlanan Randevular</h3>
                            <div class="istatistik-deger"><?php echo htmlspecialchars($tamamlanan_randevu); ?></div>
                        </div>
                        <div class="istatistik-kart">
                            <h3>İptal Edilen Randevular</h3>
                            <div class="istatistik-deger"><?php echo htmlspecialchars($iptal_randevu); ?></div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<h2>Firma Paneli</h2>";
                    echo "<p>Lütfen sol menüden bir bölüm seçin.</p>";
                }
            } else {
                echo "<h2>Firma Paneli</h2>";
                echo "<p>Lütfen sol menüden bir bölüm seçin.</p>";
            }
            ?>
        </main>
    </div>


</body>
</html>