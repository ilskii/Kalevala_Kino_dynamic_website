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

// Luodaan Users-taulukko
$sql_users = "CREATE TABLE IF NOT EXISTS Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL
)";

// Suoritetaan kysely ja tarkistetaan mahdolliset virheet
if ($conn->query($sql_users) === TRUE) {
    echo "Users-taulukko luotiin onnistuneesti!<br>";
} else {
    echo "Virhe Users-taulukon luonnissa: " . $conn->error . "<br>";
}

// Luodaan UserSessions-taulukko
$sql_sessions = "CREATE TABLE IF NOT EXISTS UserSessions (
    SessionID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    SessionToken VARCHAR(255) NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
)";

// Suoritetaan kysely ja tarkistetaan mahdolliset virheet
if ($conn->query($sql_sessions) === TRUE) {
    echo "UserSessions-taulukko luotiin onnistuneesti!<br>";
} else {
    echo "Virhe UserSessions-taulukon luonnissa: " . $conn->error . "<br>";
}

// Suljetaan tietokantayhteys
$conn->close();

?>