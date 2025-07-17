<?php
require_once '../php/db.php';
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Yetkisiz erişim!";
    exit;
}

// Verileri çek
$bilgiler = pg_query($db, "SELECT * FROM sehir_bilgileri ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Şehir Bilgileri - Admin Panel</title>
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
      max-width: 1000px;
      margin: 50px auto;
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 10px;
    }
    h1 {
      color: #333;
      text-align: center;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
    }
    th {
      background-color: #eee;
    }
    .btn {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      margin-right: 5px;
    }
    .btn-edit {
      background-color: #007bff;
    }
    .btn-edit:hover {
      background-color: #0056b3;
    }
    .btn-delete {
      background-color: #dc3545;
    }
    .btn-delete:hover {
      background-color: #b02a37;
    }
    .back-link {
      margin-top: 20px;
      padding-left: 10px;
      text-align: left;
    }
    .back-link a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      font-size: 15px;
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
  <h1>Mevcut Şehir Bilgileri</h1>
  <table>
    <tr><th>Şehir</th><th>Başlık</th><th>Açıklama</th><th>İşlem</th></tr>
    <?php while ($row = pg_fetch_assoc($bilgiler)): ?>
      <tr>
        <td><?= htmlspecialchars($row['sehir']) ?></td>
        <td><?= htmlspecialchars($row['baslik']) ?></td>
        <td><?= mb_strimwidth(htmlspecialchars($row['aciklama']), 0, 100, "...") ?></td>
        <td>
          <div style="display: flex; gap: 10px;">
            <a href="duzenle_bilgi.php?id=<?= $row['id'] ?>" class="btn btn-edit">Düzenle</a>
            <form method="POST" action="sil_bilgi.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" onclick="return confirm('Bu bilgiyi silmek istiyor musunuz?')" class="btn btn-delete">Sil</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <!-- Geri Dön Linki - Tablonun altı, sola hizalı -->
  <div class="back-link">
    <a href="admin_panel.php">← Geri Dön</a>
  </div>
</div>

</body>
</html>
