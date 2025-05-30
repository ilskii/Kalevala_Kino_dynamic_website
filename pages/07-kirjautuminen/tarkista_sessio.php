<?php
session_start();
header("Content-Type: application/json");

// Tarkistetaan, onko käyttäjä kirjautunut
$response = [
    "loggedIn" => isset($_SESSION["user_id"]),
    "user_id" => $_SESSION["user_id"] ?? null // Lisätty käyttäjän ID palautukseen
];

echo json_encode($response);
exit();
?>
