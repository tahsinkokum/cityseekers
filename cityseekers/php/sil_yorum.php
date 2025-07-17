<?php
session_start();
require_once 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Geçersiz ID";
    exit;
}

$id = intval($_GET['id']);
$isim = $_SESSION['isim'] ?? '';

if ($isim === '') {
    echo "Yetkisiz işlem.";
    exit;
}

$query = "DELETE FROM yorumlar WHERE id = $1 AND isim = $2";
$result = pg_query_params($db, $query, [$id, $isim]);

if ($result) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo "Silme işlemi başarısız.";
}
?>
