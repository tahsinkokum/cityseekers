<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<h2>Yetkisiz erişim!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Admin Paneli - CitySeekers</title>
  <link rel="stylesheet" href="../style.css">
  <style>
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
      margin: 100px auto;
      background: rgba(255, 255, 255, 0.92);
      padding: 40px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h1 {
      color: #333;
      margin-bottom: 30px;
    }

    .menu-btn {
      display: inline-block;
      margin: 10px;
      padding: 14px 24px;
      background-color: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background-color 0.3s;
      cursor: pointer;
      border: none;
    }

    .menu-btn:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
  Tarayıcınız video formatını desteklemiyor.
</video>

<div class="admin-container">
  <h1>CitySeekers Admin Paneli</h1>

  <a href="admin_sehir_ekle.php" class="menu-btn">Şehir Ekle</a>
  <a href="admin_bilgi_ekle.php" class="menu-btn">Şehir Bilgileri</a>
  <a href="admin_yorumlar.php" class="menu-btn">Yorumlar</a>
  <a href="admin_yemek_ekle.php" class="menu-btn">Yemek Ekle</a>
  <a href="admin_yemek_listele.php" class="menu-btn">Yemek Bilgileri</a>
  <a href="logout.php" class="menu-btn">Çıkış Yap</a>
</div>


</body>
</html>
