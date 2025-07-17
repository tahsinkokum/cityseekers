<?php
session_start();
include("connection.php");

if (isset($_SESSION["reset_mail"])) {
    $mail = $_SESSION["reset_mail"];
    $sifre = $_POST["sifre"];
    $sifre_tekrar = $_POST["sifre_tekrar"];

    if ($sifre === $sifre_tekrar) {
        $query = "UPDATE kullanicilar SET sifre='$sifre' WHERE mail='$mail'";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "Şifreniz başarıyla güncellendi. <a href='../index.php'>Giriş yap</a>";
            session_destroy();
        } else {
            echo "Şifre güncellenemedi.";
        }
    } else {
        echo "Şifreler uyuşmuyor.";
    }
} else {
    echo "İzin yok.";
}
?>
