<?php
$servername = "localhost";
$username = "root";
$password = "root"; // MAMPin oletussalasana on yleensä "root"
$dbname = "kalevalakino"; // Vaihda jos käytät eri nimeä

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkista yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
} else {
    echo "Yhteys onnistui tietokantaan!";
}

$conn->close();
?>
