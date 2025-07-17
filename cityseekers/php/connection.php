<?php
$host = 'localhost';
$port = '5432';
$dbname = 'cityseekers';
$user = 'postgres';
$password = 'hamza1357';

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Veritabanına bağlanılamadı.");
}
?>
