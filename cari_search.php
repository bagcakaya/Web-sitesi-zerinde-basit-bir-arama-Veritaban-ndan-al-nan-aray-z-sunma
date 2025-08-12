<?php
session_start();
require_once 'config.php';

// Tablo yoksa oluştur ve örnek verileri yükle
function ensureCariTable(PDO $conn): void {
    // Tablo var mı?
    $checkSql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'CARI_KARTLAR'";
    $exists = (int)$conn->query($checkSql)->fetchColumn() > 0;
    if ($exists) {
        return;
    }
    // Tabloyu oluştur
    $createSql = <<<SQL
CREATE TABLE `CARI_KARTLAR` (
  `Sıra_No` INT NOT NULL AUTO_INCREMENT,
  `Cari_Grup` VARCHAR(50) NOT NULL,
  `Ek_Ad` VARCHAR(50) NULL,
  `Adı` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`Sıra_No`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;
    $conn->exec($createSql);
    // Örnek veri ekle
    $insertSql = "INSERT INTO `CARI_KARTLAR` (`Cari_Grup`, `Ek_Ad`, `Adı`) VALUES
('MÜŞTERİ','AŞ','ABC Yazılım Anonim Şirketi'),
('TEDARİKÇİ','Ltd','XYZ Teknoloji Limited Şirketi'),
('MÜŞTERİ',NULL,'Kaya İnşaat'),
('MÜŞTERİ','San.','Demir Sanayi ve Ticaret')";
    $conn->exec($insertSql);
}

// Basit arama parametresi
$arama = isset($_GET['q']) ? trim($_GET['q']) : '';

// Sütun adlarını INFORMATION_SCHEMA'dan al (Türkçe/Unicode kolon adlarını korumak için)
function getCariColumns(PDO $conn): array {
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'CARI_KARTLAR' ORDER BY ORDINAL_POSITION";
    return $conn->query($sql)->fetchAll(PDO::FETCH_COLUMN);
}

$kolonlar = [];
$sonuclar = [];
$hata = null;

try {
    // Tabloyu garanti altına al
    ensureCariTable($conn);

    $kolonlar = getCariColumns($conn);

    // Arama sorgusu: Sıra_No tam eşleşme, diğer kolonda LIKE
    $where = '';
    $params = [];
    if ($arama !== '') {
        $where = "WHERE `Sıra_No` = ? OR `Cari_Grup` LIKE ? OR `Ek_Ad` LIKE ? OR `Adı` LIKE ?";
        $params = [
            ctype_digit($arama) ? (int)$arama : -1,
            "%$arama%",
            "%$arama%",
            "%$arama%",
        ];
    }

    $sql = "SELECT * FROM `CARI_KARTLAR` " . $where . " ORDER BY `Sıra_No` DESC LIMIT 200";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $sonuclar = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $hata = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cari Kart Arama</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f8fa; margin:0; padding:20px; }
    .container { max-width:1200px; margin:0 auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,.06); }
    h1 { margin-top:0; }
    form { display:flex; gap:10px; margin-bottom:20px; }
    input[type=text] { flex:1; padding:10px; border:1px solid #d0d7de; border-radius:6px; }
    button { padding:10px 16px; border:none; border-radius:6px; background:#2da44e; color:#fff; cursor:pointer; }
    button:hover { background:#2c974b; }
    table { width:100%; border-collapse:collapse; }
    th, td { text-align:left; padding:10px; border-bottom:1px solid #d8dee4; }
    th { background:#f3f4f6; }
    .muted { color:#6e7781; }
    .error { background:#fde8e8; color:#b42318; padding:10px; border:1px solid #fecaca; border-radius:6px; margin-bottom:16px; }
    .empty { text-align:center; color:#6e7781; padding:16px; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Cari Kart Arama</h1>
    <form method="get" action="">
      <input type="text" name="q" placeholder="Sıra_No, Cari_Grup, Ek_Ad veya Adı ile ara..." value="<?php echo htmlspecialchars($arama); ?>" />
      <button type="submit">Ara</button>
    </form>

    <?php if ($hata): ?>
      <div class="error">Hata: <?php echo htmlspecialchars($hata); ?></div>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <?php foreach ($kolonlar as $k): ?>
            <th><?php echo htmlspecialchars($k); ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($sonuclar)): ?>
          <?php foreach ($sonuclar as $satir): ?>
            <tr>
              <?php foreach ($kolonlar as $k): ?>
                <td><?php echo htmlspecialchars($satir[$k] ?? ''); ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td class="empty" colspan="<?php echo max(1, count($kolonlar)); ?>">Sonuç bulunamadı.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    
</body>
</html>
