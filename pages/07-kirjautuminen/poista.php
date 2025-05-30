<?php
session_start(); // Käynnistetään istunto

// Tarkistetaan, onko käyttäjä kirjautunut
if (!isset($_SESSION["user_id"])) {
    header("Location: kirjautuminen.html");
    exit();
}

require_once "connection.php"; // Yhdistetään tietokantaan

$user_id = $_SESSION["user_id"];

// Valmistellaan SQL-kysely käyttäjän poistamiseksi
$stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Tarkistetaan, että poisto onnistui
    if ($stmt->affected_rows > 0) {
        $_SESSION = []; // Tyhjennetään istuntotiedot
        session_destroy(); // Tuhotaan istunto
        header("Location: kirjautuminen.html");
        exit();
    } else {
        echo "❌ Käyttäjää ei löytynyt tai tilin poistaminen epäonnistui.";
    }
} else {
    echo "❌ Virhe tilin poistamisessa: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
