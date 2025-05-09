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

$firstname = htmlspecialchars($_POST["firstname"]);
$lastname = htmlspecialchars($_POST["lastname"]);
$email = htmlspecialchars($_POST["email"]);
$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
$stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);

if ($stmt->execute()) {
    $_SESSION["firstname"] = $firstname; // Päivitetään istunnon nimi
    header("Location: profiili.php");
    exit();
} else {
    echo "Virhe päivityksessä: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
