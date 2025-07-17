<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<h2>Yetkisiz erişim!</h2>";
    exit;
}

// Kayıt işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sehir = $_POST['sehir'];
    $baslik = $_POST['baslik'];
    $aciklama = $_POST['aciklama'];
    $resim_yolu = $_POST['resim_yolu'];
    $harita_embed = $_POST['harita_embed'];

    $sql = "INSERT INTO sehir_bilgileri (sehir, baslik, aciklama, resim_yolu, harita_embed)
            VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($db, $sql, [$sehir, $baslik, $aciklama, $resim_yolu, $harita_embed]);

    if (!$result) {
        echo "<p style='color:red'>Kayıt sırasında hata oluştu: " . pg_last_error($db) . "</p>";
    } else {
        echo "<p style='color:green'>Şehir bilgisi başarıyla eklendi.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Şehir Ekle - Admin Paneli</title>
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
      max-width: 900px;
      margin: 80px auto;
      background: rgba(255,255,255,0.9);
      padding: 40px;
      border-radius: 10px;
    }
    h1 {
      color: #333;
      text-align: center;
    }
    form {
      margin-top: 30px;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      background-color: #28a745;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background-color: #218838;
    }
    a.back {
      display: inline-block;
      margin-top: 20px;
      text-decoration: none;
      color: #007bff;
    }
  </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
</video>

<div class="admin-container">
  <h1>Yeni Şehir Bilgisi Ekle</h1>

  <form method="POST">
    <input type="text" name="sehir" placeholder="Şehir (örn: ankara)" required>
    <input type="text" name="baslik" placeholder="Başlık (örn: Kale)" required>
    <textarea name="aciklama" placeholder="Açıklama girin..." rows="4" required></textarea>
    <input type="text" name="resim_yolu" placeholder="Resim Yolu (örn: images/kale.jpg)" required>
    <textarea name="harita_embed" placeholder="Google Maps embed kodu" rows="3" required></textarea>
    <button type="submit">Ekle</button>
  </form>

  <a class="back" href="admin_panel.php">← Geri Dön</a>
</div>

</body>
</html>
