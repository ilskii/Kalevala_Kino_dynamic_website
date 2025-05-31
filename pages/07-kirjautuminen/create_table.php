<?php

require_once "connection.php"; // Yhdistetään tietokantaan

// Määritetään lokitiedoston sijainti
$logFile = __DIR__ . "/lokitiedosto.log";

// Luodaan users-taulukko
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,  -- Käyttäjän yksilöllinen ID
    FirstName VARCHAR(100) NOT NULL,         -- Etunimi
    LastName VARCHAR(100) NOT NULL,          -- Sukunimi
    Email VARCHAR(255) NOT NULL UNIQUE,      -- Sähköposti (oltava uniikki)
    PasswordHash VARCHAR(255) NOT NULL,      -- Hashattu salasana
    ProfileImage VARCHAR(255) DEFAULT NULL,  -- Profiilikuvan polku
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Käyttäjän luontiaika
)";

// Suoritetaan kysely ja lokitetaan tulos aikaleimalla
if ($conn->query($sql_users) === TRUE) {
    error_log(date("Y-m-d H:i:s") . " - Users-taulukko luotiin onnistuneesti!\n", 3, $logFile);
} else {
    error_log(date("Y-m-d H:i:s") . " - Virhe users-taulukon luonnissa: " . $conn->error . "\n", 3, $logFile);
}

// Luodaan user_sessions-taulukko
$sql_sessions = "CREATE TABLE IF NOT EXISTS user_sessions (
    SessionID INT AUTO_INCREMENT PRIMARY KEY,  -- Istunnon ID
    UserID INT NOT NULL,                        -- Käyttäjän ID (viittaus users-tauluun)
    SessionToken VARCHAR(255) NOT NULL UNIQUE,  -- Istunnon yksilöllinen token
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Istunnon luontiaika
    FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE -- Viittaus users-tauluun
)";

// Suoritetaan kysely ja lokitetaan tulos aikaleimalla
if ($conn->query($sql_sessions) === TRUE) {
    error_log(date("Y-m-d H:i:s") . " - user_sessions-taulukko luotiin onnistuneesti!\n", 3, $logFile);
} else {
    error_log(date("Y-m-d H:i:s") . " - Virhe user_sessions-taulukon luonnissa: " . $conn->error . "\n", 3, $logFile);
}

?>
