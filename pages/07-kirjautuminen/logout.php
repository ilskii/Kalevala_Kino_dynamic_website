<?php
session_start(); // Käynnistetään istunto

// Varmistetaan, että istunto on olemassa ennen sen tuhoamista
if (isset($_SESSION["user_id"])) {
    $_SESSION = []; // Tyhjennetään kaikki istuntotiedot
    session_destroy(); // Tuhotaan istunto
}

// Ohjataan käyttäjä kirjautumissivulle
header("Location: kirjautuminen.html");
exit();
?>
