<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Yetkisiz erişim!";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Geçersiz ID.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sehir = $_POST['sehir'];
    $baslik = $_POST['baslik'];
    $aciklama = $_POST['aciklama'];
    $resim_yolu = $_POST['resim_yolu'];
    $harita_embed = $_POST['harita_embed'];

    $update = pg_query_params($db, "UPDATE sehir_bilgileri SET sehir=$1, baslik=$2, aciklama=$3, resim_yolu=$4, harita_embed=$5 WHERE id=$6", [$sehir, $baslik, $aciklama, $resim_yolu, $harita_embed, $id]);

    if ($update) {
        header("Location: admin_bilgi_ekle.php");
        exit;
    } else {
        echo "Güncelleme hatası: " . pg_last_error($db);
    }
}

$result = pg_query_params($db, "SELECT * FROM sehir_bilgileri WHERE id = $1", [$id]);
$veri = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Bilgi Düzenle</title>
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
      max-width: 800px;
      margin: 80px auto;
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
    }
    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 15px;
    }
    button:hover {
      background-color: #0056b3;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
    a.back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
</video>

<div class="admin-container">
  <h2>Şehir Bilgisi Düzenle</h2>
  <form method="POST">
    <input type="text" name="sehir" value="<?= htmlspecialchars($veri['sehir']) ?>" required>
    <input type="text" name="baslik" value="<?= htmlspecialchars($veri['baslik']) ?>" required>
    <textarea name="aciklama" rows="4" required><?= htmlspecialchars($veri['aciklama']) ?></textarea>
    <input type="text" name="resim_yolu" value="<?= htmlspecialchars($veri['resim_yolu']) ?>" required>
    <textarea name="harita_embed" rows="3" required><?= htmlspecialchars($veri['harita_embed']) ?></textarea>
    <button type="submit">Kaydet</button>
  </form>
  <a class="back" href="admin_bilgi_ekle.php">← Geri Dön</a>
</div>

</body>
</html>
