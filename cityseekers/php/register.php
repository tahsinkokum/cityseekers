<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantısı
$host = 'localhost';
$port = '5432';
$db = 'cityseekers';
$user = 'postgres';
$pass = 'hamza1357';

$conn = pg_connect("host=$host port=$port dbname=$db user=$user password=$pass");

if (!$conn) {
    echo "<script>alert('Veritabanına bağlanılamadı.');</script>";
    exit;
}

// Formdan gelen veriler
$isim = $_POST['isim'];
$soyad = $_POST['soyad'];
$mail = $_POST['mail'];
$dogum_tarihi = $_POST['dogum_tarihi'];
$sifre = $_POST['sifre'];
$sifre_tekrar = $_POST['sifre_tekrar'];

// Şifre kontrolü
if ($sifre !== $sifre_tekrar) {
    echo "<script>alert('Şifreler uyuşmuyor!'); window.location.href = '../kayit.html';</script>";
    exit;
}

// Şifreyi hash'le
$hashli_sifre = password_hash($sifre, PASSWORD_DEFAULT);

// SQL sorgusu
$sql = "INSERT INTO oneriler (isim, soyad, mail, dogum_tarihi, sifre) VALUES ($1, $2, $3, $4, $5)";
$result = pg_query_params($conn, $sql, array($isim, $soyad, $mail, $dogum_tarihi, $hashli_sifre));

pg_close($conn);

// HTML çıktısı ile bildirim ve yönlendirme
if ($result) {
    echo "
    <!DOCTYPE html>
    <html lang='tr'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                toast: true,
                position: 'bottom-end',
                icon: 'success',
                title: 'Kayıt başarıyla oluşturuldu!',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            setTimeout(() => {
                window.location.href = '../index.php';
            }, 2000);
        </script>
    </body>
    </html>
    ";
} else {
    echo "
    <!DOCTYPE html>
    <html lang='tr'>
    <head>
        <meta charset='UTF-8'>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Kayıt sırasında bir hata oluştu!',
                text: 'Lütfen bilgileri kontrol edin.',
                confirmButtonText: 'Tamam'
            }).then(() => {
                window.location.href = '../kayit.html';
            });
        </script>
    </body>
    </html>
    ";
}
?>
