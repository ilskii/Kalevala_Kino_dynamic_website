<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "kalevalakino";

// Luodaan yhteys MySQL-palvelimeen
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkistetaan yhteys
if ($conn->connect_error) {
    die("❌ Yhteyden muodostaminen epäonnistui: " . $conn->connect_error);
}

// Asetetaan UTF-8, jotta merkistöt toimivat oikein
$conn->set_charset("utf8");

// Ajetaan taulujen luonti joka kerta
require_once "create_table.php";

?>
