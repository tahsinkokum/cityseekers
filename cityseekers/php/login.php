<?php
session_start();

$host = 'localhost';
$port = '5432';
$db = 'cityseekers';
$user = 'postgres';
$pass = 'hamza1357';

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass options='--client_encoding=UTF8'");

if (!$conn) {
    echo "Veritabanına bağlanılamadı.";
    exit;
}

// Giriş bilgilerini al
$mail = $_POST['mail'] ?? '';
$sifre = $_POST['sifre'] ?? '';

// Eğer admin girişi yapıldıysa doğrudan kontrol et
if ($mail === 'admin' && $sifre === 'admin123') {
    $_SESSION['admin'] = true;
    header("Location: ../php/admin_panel.php");
    exit;
}

// Normal kullanıcı kontrolü
$sql = "SELECT * FROM oneriler WHERE mail = $1";
$result = pg_query_params($conn, $sql, array($mail));

if ($row = pg_fetch_assoc($result)) {
    if (password_verify($sifre, $row['sifre'])) {
        $_SESSION['isim'] = $row['isim'];
        $_SESSION['soyad'] = $row['soyad'];
        $_SESSION['mail'] = $row['mail'];
        $_SESSION['dogum_tarihi'] = $row['dogum_tarihi'];

        header("Location: ../anasayfa.html");
        exit;
    } else {
        echo "❌ Şifre yanlış.";
    }
} else {
    echo "❌ Kullanıcı bulunamadı.";
}

pg_close($conn);
?>
