<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "<h2>Yetkisiz erişim!</h2>";
    exit;
}

$result = pg_query($db, "SELECT * FROM sehir_yemekleri ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Mevcut Yemekler - Admin Panel</title>
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
      max-width: 1100px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px 12px;
      text-align: left;
      vertical-align: top;
    }

    th {
      background-color: #f0f0f0;
    }

    img.thumb {
      width: 100px;
      border-radius: 6px;
    }

    .btn {
      display: inline-block;
      padding: 6px 12px;
      margin-right: 5px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
    }

    .edit {
      background-color: #007bff;
      color: white;
    }

    .delete {
      background-color: #dc3545;
      color: white;
    }

    .edit:hover {
      background-color: #0056b3;
    }

    .delete:hover {
      background-color: #c82333;
    }

    .back-link {
      margin-top: 20px;
      text-align: left;
    }

    .back-link a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
    }

    .back-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
</video>

<div class="admin-container">
  <h1>Mevcut Yemekler</h1>

  <table>
    <tr>
      <th>Resim</th>
      <th>Şehir</th>
      <th>Yemek Adı</th>
      <th>Açıklama</th>
      <th>İşlem</th>
    </tr>
    <?php while ($row = pg_fetch_assoc($result)): ?>
      <tr>
        <td>
          <?php if (!empty($row['resim_yolu']) && file_exists('../' . $row['resim_yolu'])): ?>
            <img class="thumb" src="../<?= htmlspecialchars($row['resim_yolu']) ?>" alt="Yemek Görseli">
          <?php else: ?>
            <span>Görsel yok</span>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['sehir']) ?></td>
        <td><?= htmlspecialchars($row['yemek_adi']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['aciklama'])) ?></td>
        <td>
          <a class="btn edit" href="admin_yemek_duzenle.php?id=<?= $row['id'] ?>">Düzenle</a>
          <a class="btn delete" href="admin_yemek_sil.php?id=<?= $row['id'] ?>" onclick="return confirm('Bu yemeği silmek istediğinize emin misiniz?')">Sil</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back-link">
    <a href="admin_panel.php">← Geri Dön</a>
  </div>
</div>

</body>
</html>
