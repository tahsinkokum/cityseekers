<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo "Yetkisiz erişim!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sil = pg_query_params($db, "DELETE FROM sehir_bilgileri WHERE id = $1", [$id]);

    if ($sil) {
        header("Location: admin_bilgi_ekle.php");
        exit;
    } else {
        echo "Silme hatası: " . pg_last_error($db);
    }
} else {
    echo "Geçersiz istek.";
}
