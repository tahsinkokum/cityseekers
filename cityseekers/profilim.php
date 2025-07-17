<?php
session_start();
if (!isset($_SESSION['isim'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Profilim - CitySeekers</title>
  <link rel="stylesheet" href="anasayfa.css">
  <style>
    /* Ek stil tanımlamaları */
    .info-card {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 15px;
      color: white;
      max-width: 500px;
      margin: 200px auto 0 auto;
      text-align: center;
    }

    .info-card h1 {
      font-size: 32px;
      margin-bottom: 20px;
    }

    .info-card p {
      font-size: 18px;
      margin: 10px 0;
    }

    .btn-group {
      margin-top: 30px;
    }

    .btn {
      display: inline-block;
      padding: 10px 25px;
      margin: 10px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    /* Navbar için ek stil */
    .nav-links li a.disabled {
      pointer-events: none;
      cursor: default;
      color: gray;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">
      <img src="images/Logo_transparent.png" alt="CitySeekers Logo">
    </div>
    <ul class="nav-links">
      <li><a href="anasayfa.html">Anasayfa</a></li>
      <li><a href="#" class="disabled">Profilim</a></li>
    </ul>
  </nav>

  <!-- Bilgi Kartı -->
  <div class="info-card">
    <h1>Hoş geldin, <?php echo htmlspecialchars($_SESSION['isim']) . ' ' . htmlspecialchars($_SESSION['soyad']); ?>!</h1>
    <p><strong>Mail:</strong> <?php echo htmlspecialchars($_SESSION['mail']); ?></p>
    <p><strong>Doğum Tarihi:</strong> <?php echo htmlspecialchars($_SESSION['dogum_tarihi']); ?></p>

    <div class="btn-group">
      <a href="php/logout.php" class="btn">Çıkış Yap</a>
      <a href="anasayfa.html" class="btn">Ana Sayfaya Dön</a>
    </div>
  </div>
</body>
</html>
