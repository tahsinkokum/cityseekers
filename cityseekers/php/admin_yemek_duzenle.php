<?php
require_once '../php/db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "ID eksik.";
    exit;
}

$id = $_GET['id'];
$mesaj = '';

// Güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sehir = trim($_POST['sehir']);
    $yemek_adi = trim($_POST['yemek_adi']);
    $aciklama = trim($_POST['aciklama']);

    // Varsayılan olarak eski resim kullanılır
    $resim_yolu = $_POST['mevcut_resim'];

    if (isset($_FILES['resim']) && $_FILES['resim']['error'] === UPLOAD_ERR_OK) {
        $dosya_adi = basename($_FILES['resim']['name']);
        $uzanti = strtolower(pathinfo($dosya_adi, PATHINFO_EXTENSION));
        $izinli = ['jpg', 'jpeg', 'png'];

        if (in_array($uzanti, $izinli)) {
            $hedef_klasor = '../images/';
            $hedef_yol = $hedef_klasor . $dosya_adi;
            $veritabani_yolu = 'images/' . $dosya_adi;

            if (move_uploaded_file($_FILES['resim']['tmp_name'], $hedef_yol)) {
                $resim_yolu = $veritabani_yolu;
            } else {
                $mesaj = "Yeni resim yüklenemedi. Mevcut resim korunuyor.";
            }
        } else {
            $mesaj = "Sadece JPG, JPEG, PNG dosyalarına izin verilir.";
        }
    }

    $query = "UPDATE sehir_yemekleri SET sehir = $1, yemek_adi = $2, aciklama = $3, resim_yolu = $4 WHERE id = $5";
    $result = pg_query_params($db, $query, [$sehir, $yemek_adi, $aciklama, $resim_yolu, $id]);

    if ($result) {
        header("Location: admin_yemek_listele.php");
        exit;
    } else {
        $mesaj = "Güncelleme başarısız.";
    }
}

// Mevcut veriyi çek
$query = "SELECT * FROM sehir_yemekleri WHERE id = $1";
$result = pg_query_params($db, $query, [$id]);
$data = pg_fetch_assoc($result);

// Şehirler
$sehirler = pg_query($db, "SELECT DISTINCT sehir FROM sehir_bilgileri");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yemeği Düzenle</title>
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

        .form-container {
            max-width: 500px;
            margin: 100px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
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
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            margin-top: 20px;
            text-align: center;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
        }

        .resim-onizleme {
            margin-top: 10px;
            text-align: center;
        }

        .resim-onizleme img {
            max-width: 150px;
            border-radius: 8px;
        }

        .mesaj {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="../videos/amasra.mp4" type="video/mp4">
</video>

<div class="form-container">
    <h2>Yemeği Düzenle</h2>

    <?php if (!empty($mesaj)): ?>
        <div class="mesaj"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Şehir</label>
        <select name="sehir" required>
            <?php while ($row = pg_fetch_assoc($sehirler)): ?>
                <option value="<?= htmlspecialchars($row['sehir']) ?>" <?= $data['sehir'] == $row['sehir'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['sehir']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Yemek Adı</label>
        <input type="text" name="yemek_adi" value="<?= htmlspecialchars($data['yemek_adi']) ?>" required>

        <label>Açıklama</label>
        <textarea name="aciklama" rows="5" required><?= htmlspecialchars($data['aciklama']) ?></textarea>

        <label>Mevcut Resim</label>
        <div class="resim-onizleme">
            <?php if (!empty($data['resim_yolu']) && file_exists('../' . $data['resim_yolu'])): ?>
                <img src="../<?= htmlspecialchars($data['resim_yolu']) ?>" alt="Yemek Görseli">
            <?php else: ?>
                <p>Görsel bulunamadı.</p>
            <?php endif; ?>
        </div>

        <label>Yeni Resim (Opsiyonel)</label>
        <input type="file" name="resim" accept=".jpg,.jpeg,.png">

        <input type="hidden" name="mevcut_resim" value="<?= htmlspecialchars($data['resim_yolu']) ?>">

        <button type="submit">Güncelle</button>
    </form>

    <div class="back-link">
        <a href="admin_yemek_listele.php">← Geri Dön</a>
    </div>
</div>

</body>
</html>
