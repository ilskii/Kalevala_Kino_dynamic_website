<?php
// Määritetään lokitiedoston sijainti (voit vaihtaa tiedostonimeä tähän)
$logFile = __DIR__ . "/lokitiedosto.log";

$servername = "localhost";    // MySQL-palvelimen osoite
$username   = "root";         // Käyttäjätunnus
$password   = "";             // Salasana
$dbname     = "kalevalakino";   // Tietokannan nimi

// Luodaan yhteys MySQL-palvelimeen ilman tietokantaa
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    error_log(date("Y-m-d H:i:s") . " - Yhteyden muodostaminen epäonnistui: " . $conn->connect_error . "\n", 3, $logFile);
    die("Tietokantayhteyden muodostaminen epäonnistui.");
}

// Luodaan tietokanta, jos sitä ei ole olemassa
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci";
if ($conn->query($sql) === TRUE) {
    error_log(date("Y-m-d H:i:s") . " - Tietokanta '$dbname' luotiin onnistuneesti (tai se on jo olemassa)!\n", 3, $logFile);
} else {
    error_log(date("Y-m-d H:i:s") . " - Tietokannan luonti epäonnistui: " . $conn->error . "\n", 3, $logFile);
    die("Tietokannan luonti epäonnistui.");
}

// Valitaan juuri luotu tietokanta (ei tarvita yhteyden uudelleenavauksia)
$conn->select_db($dbname);

// Asetetaan UTF-8, jotta merkistö toimii oikein
$conn->set_charset("utf8");

// Ajetaan taulujen luonti käyttäen samaa yhteysobjektia.
require_once "create_table.php";
?>
