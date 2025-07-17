<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Yetkisiz erişim!";
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID eksik!";
    exit;
}

$id = $_GET['id'];

// Önce görselin yolunu çek
$query = "SELECT resim_yolu FROM sehir_yemekleri WHERE id = $1";
$result = pg_query_params($db, $query, [$id]);
$data = pg_fetch_assoc($result);

if ($data && !empty($data['resim_yolu'])) {
    $resimYolu = '../' . $data['resim_yolu'];

    // Dosya varsa sil
    if (file_exists($resimYolu)) {
        unlink($resimYolu);
    }
}

// Sonra veritabanından sil
$queryDelete = "DELETE FROM sehir_yemekleri WHERE id = $1";
pg_query_params($db, $queryDelete, [$id]);

header("Location: admin_yemek_listele.php");
exit;
?>
