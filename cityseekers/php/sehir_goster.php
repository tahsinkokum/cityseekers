<?php
require_once 'db.php';

if (!isset($sehir)) {
    echo "<p>Şehir belirtilmedi.</p>";
    return;
}

$query = "SELECT * FROM sehir_bilgileri WHERE LOWER(sehir) = LOWER($1)";
$result = pg_query_params($db, $query, [$sehir]);

if (!$result || pg_num_rows($result) === 0) {
    echo "<p>Bu şehre ait bilgi bulunamadı.</p>";
    return;
}

while ($row = pg_fetch_assoc($result)) {
    echo '<div class="tarihi-yer">';
    echo '<h3>' . htmlspecialchars($row['baslik']) . '</h3>';
    echo '<p>' . nl2br(htmlspecialchars($row['aciklama'])) . '</p>';

    if (!empty($row['resim_yolu'])) {
        $resim_yolu = str_replace("C:\\xampp\\htdocs\\cityseekers\\", "../", $row['resim_yolu']);
        echo '<img src="' . htmlspecialchars($resim_yolu) . '" alt="resim" style="max-width: 400px; max-height: 300px;">';
    }

    if (!empty($row['harita_embed'])) {
        echo '<div class="harita">' . $row['harita_embed'] . '</div>';
    }

    echo '</div><hr>';
}
?>
