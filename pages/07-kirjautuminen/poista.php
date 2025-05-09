<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: kirjautuminen.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "kalevalakino";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_destroy();
    header("Location: kirjautuminen.html");
    exit();
} else {
    echo "Virhe tilin poistamisessa: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
