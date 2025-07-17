<?php
$db = pg_connect("host=localhost dbname=cityseekers user=postgres password=hamza1357");
if (!$db) {
    die("Veritabanına bağlanılamadı.");
}
?>