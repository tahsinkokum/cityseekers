<?php
require_once '../php/db.php'; // Veritabanı bağlantısı

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sayıya çevir

    $query = "DELETE FROM yorumlar WHERE id = $1";
    $result = pg_query_params($db, $query, [$id]);

    if ($result) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: $redirect");
        exit;
    } else {
        echo "Yorum silinemedi.";
    }
} else {
    echo "Geçerli bir ID gönderilmedi.";
}
?>
