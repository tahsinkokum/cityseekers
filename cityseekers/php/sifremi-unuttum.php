<?php
session_start();
include("connection.php");

if (!$conn) {
    die("Veritabanı bağlantı hatası: " . pg_last_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isim = $_POST["isim"];
    $soyad = $_POST["soyad"];
    $mail = $_POST["mail"];

    $query = "SELECT * FROM oneriler WHERE isim=$1 AND soyad=$2 AND mail=$3";
    $params = array($isim, $soyad, $mail);
    $result = pg_query_params($conn, $query, $params);

    if ($result && pg_num_rows($result) >= 1) {
        $_SESSION["reset_mail"] = $mail;
        header("Location: ../sifre-yenile.html");
        exit;
    } else {
        echo "Bilgiler hatalı, lütfen tekrar deneyin.";
    }
} else {
    echo "Bu sayfa doğrudan erişilemez.";
}
?>