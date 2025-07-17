<?php
require_once '../php/db.php';
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Yetkisiz erişim!";
    exit;
}

// Yorumları çek
$yorumlar = pg_query($db, "SELECT * FROM yorumlar ORDER BY id DESC");

// Yorum silme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sil_id'])) {
    $sil_id = $_POST['sil_id'];
    pg_query_params($db, "DELETE FROM yorumlar WHERE id = $1", [$sil_id]);
    header("Location: admin_yorumlar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yorum Yönetimi - Admin Panel</title>
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
    .sil-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }
    .sil-btn:hover {
      background-color: #1e7e34;
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
  <h2>Kullanıcı Yorumları</h2>
  <table>
    <tr>
      <th>İsim</th>
      <th>Yorum</th>
      <th>Şehir</th>
      <th>Tarih</th>
      <th>İşlem</th>
    </tr>
    <?php while ($row = pg_fetch_assoc($yorumlar)): ?>
      <tr>
        <td><?= htmlspecialchars($row['isim']) ?></td>
        <td><?= htmlspecialchars($row['yorum']) ?></td>
        <td><?= htmlspecialchars($row['sehir']) ?></td>
        <td><?= htmlspecialchars($row['tarih']) ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="sil_id" value="<?= $row['id'] ?>">
            <button type="submit" class="sil-btn" onclick="return confirm('Yorumu silmek istiyor musunuz?')">Sil</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <!-- Geri Dön Linki -->
  <div class="back-link">
    <a href="admin_panel.php">← Geri Dön</a>
  </div>
</div>

</body>
</html>
