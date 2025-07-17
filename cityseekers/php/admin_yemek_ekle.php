<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<h2>Yetkisiz erişim!</h2>";
    exit;
}

$sehirler = pg_query($db, "SELECT DISTINCT sehir FROM sehir_bilgileri");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sehir = trim($_POST['sehir']);
    $yemek_adi = trim($_POST['yemek_adi']);
    $aciklama = trim($_POST['aciklama']);
    $mesaj = '';

    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $dosya_adi = basename($_FILES['resim']['name']);
        $hedef_klasor = '../images/';
        $hedef_yol = $hedef_klasor . $dosya_adi;
        $veritabani_yolu = 'images/' . $dosya_adi;

        // Sadece jpg, jpeg, png izin ver
        $uzanti = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));
        $izinli = ['jpg', 'jpeg', 'png'];
        if (in_array($uzanti, $izinli)) {
            if (move_uploaded_file($_FILES['resim']['tmp_name'], $hedef_yol)) {
                $query = "INSERT INTO sehir_yemekleri (sehir, yemek_adi, aciklama, resim_yolu) VALUES ($1, $2, $3, $4)";
                $result = pg_query_params($db, $query, [$sehir, $yemek_adi, $aciklama, $veritabani_yolu]);
                $mesaj = $result ? "Yemek başarıyla eklendi." : "Veritabanına eklenirken hata oluştu.";
            } else {
                $mesaj = "Resim yüklenemedi.";
            }
        } else {
            $mesaj = "Sadece JPG, JPEG veya PNG formatındaki dosyalar desteklenmektedir.";
        }
    } else {
        $mesaj = "Lütfen bir resim dosyası seçin.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yemek Ekle - Admin Panel</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    #bg-video {
      position: fixed;
      top: 0;
      left: 0;
      min-width: 100%;
      min-height: 100%;
      z-index: -1;
      object-fit: cover;
      filter: brightness(0.4);
    }

    .admin-container {
      position: relative;
      z-index: 1;
      max-width: 600px;
      margin: 80px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    textarea,
    select,
    input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
    }

    button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 12px 20px;
      margin-top: 25px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #218838;
    }

    .back-link {
      margin-top: 20px;
      text-align: center;
    }

    .back-link a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .alert {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border: 1px solid #c3e6cb;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
</video>

<div class="admin-container">
  <h1>Yemek Ekle</h1>

  <?php if (!empty($mesaj)): ?>
    <div class="alert"><?= htmlspecialchars($mesaj) ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Şehir Seç</label>
    <select name="sehir" required>
      <option value="" disabled selected>Şehir Seçiniz</option>
      <?php while ($row = pg_fetch_assoc($sehirler)): ?>
        <option value="<?= htmlspecialchars($row['sehir']) ?>"><?= htmlspecialchars($row['sehir']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Yemek Adı</label>
    <input type="text" name="yemek_adi" required>

    <label>Açıklama</label>
    <textarea name="aciklama" rows="4" required></textarea>

    <label>Yemek Resmi (JPG, JPEG, PNG)</label>
    <input type="file" name="resim" accept=".jpg, .jpeg, .png" required>

    <button type="submit">Yemeği Kaydet</button>
  </form>

  <div class="back-link">
    <a href="admin_panel.php">← Geri Dön</a>
  </div>
</div>

</body>
</html>
