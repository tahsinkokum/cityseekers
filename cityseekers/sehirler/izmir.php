<?php
session_start();
require_once '../php/db.php';

$isim = $_SESSION['isim'] ?? 'Ziyaretçi';
$sehir = 'İzmir';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['yorum'])) {
    $yorum = $_POST['yorum'];
    $puan = isset($_POST['puan']) ? intval($_POST['puan']) : 0;
    $tarih = date('Y-m-d H:i:s');

    $query = "INSERT INTO yorumlar (isim, yorum, tarih, sehir, puan) VALUES ($1, $2, $3, $4, $5)";
    pg_query_params($db, $query, [$isim, $yorum, $tarih, $sehir, $puan]);
}

// Yorumları çek
$yorumlar = [];
$result = pg_query_params($db, "SELECT * FROM yorumlar WHERE sehir = $1 ORDER BY id DESC", [$sehir]);
while ($row = pg_fetch_assoc($result)) {
    $yorumlar[] = $row;
}

// Yemekleri çek (şehri küçük/büyük harf duyarsız hale getirdik)
$yemekler = [];
$resultYemek = pg_query_params($db, "SELECT * FROM sehir_yemekleri WHERE LOWER(sehir) = LOWER($1)", [$sehir]);
while ($row = pg_fetch_assoc($resultYemek)) {
    $yemekler[] = $row;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>İzmir - CitySeekers</title>
    <link rel="stylesheet" href="izmir.css" />
    <style>
        #rating span {
            font-size: 24px;
            cursor: pointer;
            color: gray;
        }
        #rating span.selected {
            color: orange;
        }
        .yemekler-bolumu h2 {
            color: #fff;
            margin: 40px 0 20px 0;
        }
        .yemek-karti {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
        }
        .yemek-karti img {
            width: 220px;
            height: auto;
            border-radius: 8px;
        }
        .yemek-karti h3 {
            margin: 0 0 10px 0;
            color: #fff;
        }
        .yemek-karti p {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="../Logo_transparent.png" alt="CitySeekers Logo" class="logo">
        <ul class="nav-links">
            <li><a href="../anasayfa.html">Anasayfa</a></li>
            <li><a href="../profilim.php">Profilim</a></li>
            <li><a href="../index.php">Çıkış Yap</a></li>
        </ul>
    </div>

    <div class="hero">
        <video autoplay muted loop>
            <source src="../videos/izmir.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay">
            <h1>İzmir</h1>
            <p>Ege’nin incisi İzmir, özgür ruhu ve deniz kokan sokaklarıyla ünlüdür. Kordon Boyu, Saat Kulesi ve Çeşme, gezilecek başlıca yerlerdir.</p>
        </div>
    </div>

    <div class="icerik">
        <h2>Tarihi Yerler</h2>
        <?php include("../php/sehir_goster.php"); ?>

        <?php if (!empty($yemekler)): ?>
        <div class="yemekler-bolumu">
            <h2>Meşhur Lezzetler</h2>
            <?php foreach ($yemekler as $yemek): ?>
                <div class="yemek-karti">
                    <img src="../<?= htmlspecialchars($yemek['resim_yolu']) ?>" alt="<?= htmlspecialchars($yemek['yemek_adi']) ?>">
                    <div>
                        <h3><?= htmlspecialchars($yemek['yemek_adi']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($yemek['aciklama'])) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p style="color:white;">Bu şehre ait yemek bilgisi bulunamadı.</p>
        <?php endif; ?>

        <div class="yorum-alani">
            <h2>Ziyaretçi Yorumları</h2>
            <form id="yorumForm" method="POST">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <textarea name="yorum" placeholder="Yorumunuzu yazın..." required style="flex: 1;"></textarea>
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                        <div id="rating">
                            <span data-value="1">★</span>
                            <span data-value="2">★</span>
                            <span data-value="3">★</span>
                            <span data-value="4">★</span>
                            <span data-value="5">★</span>
                        </div>
                        <input type="hidden" name="puan" id="puanInput" value="0">
                        <button type="submit">Gönder</button>
                    </div>
                </div>
            </form>

            <div class="yorumlar" id="yorumlarAlani">
                <?php foreach ($yorumlar as $yorum): ?>
                    <div class="yorum">
                        <strong><?= htmlspecialchars($yorum['isim']) ?></strong>
                        <p><?= htmlspecialchars($yorum['yorum']) ?></p>
                        <small><?= $yorum['tarih'] ?></small>
                        <p class="puan-yildiz">
                            <?php
                            $puan = intval($yorum['puan']);
                            for ($i = 1; $i <= 5; $i++) {
                                echo $i <= $puan ? "★" : "☆";
                            }
                            ?>
                        </p>
                        <?php if ($_SESSION['isim'] == $yorum['isim']): ?>
                            <a href="../php/sil_yorum.php?id=<?= $yorum['id'] ?>" onclick="return confirm('Bu yorumu silmek istediğinize emin misiniz?')">❌ Yorumu Sil</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const stars = document.querySelectorAll("#rating span");
        const puanInput = document.getElementById("puanInput");

        stars.forEach((star, index) => {
            star.addEventListener("click", () => {
                puanInput.value = star.dataset.value;
                stars.forEach(s => s.classList.remove("selected"));
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add("selected");
                }
            });
        });

        document.getElementById("yorumForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch("<?php echo $_SERVER['PHP_SELF']; ?>", {
                method: "POST",
                body: formData
            }).then(() => window.location.reload());
        });
    </script>
</body>
</html>
