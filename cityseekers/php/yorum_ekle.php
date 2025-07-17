<?php
$conn = pg_connect("host=localhost dbname=cityseekers user=postgres password=seninsifren");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici_adi = $_POST['kullanici_adi'];
    $yorum = $_POST['yorum'];

    $query = "INSERT INTO yorumlar (kullanici_adi, yorum) VALUES ($1, $2)";
    pg_query_params($conn, $query, array($kullanici_adi, $yorum));
}

header("Location: ../sehirler/ankara.html");
?>
