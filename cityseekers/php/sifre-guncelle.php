<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_SESSION["reset_mail"] ?? null;
    $yeni_sifre = $_POST["yeni_sifre"];
    $yeni_sifre_tekrar = $_POST["yeni_sifre_tekrar"];

    if (!$mail) {
        echo "Geçersiz oturum.";
        exit;
    }

    if ($yeni_sifre !== $yeni_sifre_tekrar) {
        echo "Şifreler uyuşmuyor!";
        exit;
    }

    // Şifre hashleniyor
    $hashli_sifre = password_hash($yeni_sifre, PASSWORD_DEFAULT);

    // Güncelleme sorgusu
    $query = "UPDATE oneriler SET sifre = $1 WHERE mail = $2";
    $result = pg_query_params($conn, $query, array($hashli_sifre, $mail));

    if ($result) {
        // Oturum temizle
        unset($_SESSION["reset_mail"]);
        // Başarıyla güncellendi mesajını giriş ekranına taşı
        header("Location: ../index.php?sifre_degisti=1");
        exit;
    } else {
        echo "Şifre güncellenemedi.";
    }
}
?>
