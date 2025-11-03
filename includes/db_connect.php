<?php

$db_host = 'localhost';
$db_user = 'tugaspabw_2414101039'; 
$db_pass = '@aidilakbar12345';     
$db_name = 'tugaspabw_2414101039';


$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>