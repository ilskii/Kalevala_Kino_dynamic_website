<?php

// Yhdistetään tietokantaan
$servername = "localhost:8000"; // Muista käyttää oikeaa porttia XAMPP:ssä
$username = "root"; // Oletustunnus XAMPP
$password = ""; // Oletussalasana XAMPP
$dbname = "db_kalevalakino"; // Tietokannan nimi

// Luodaan yhteys MySQL-palvelimeen
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkistetaan yhteys
if ($conn->connect_error) {
    die("Yhteyden muodostaminen epäonnistui: " . $conn->connect_error);
}

?>
