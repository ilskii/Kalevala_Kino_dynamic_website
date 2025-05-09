<?php
session_start(); // Mahdollistaa istunnon käytön (esim. käyttäjän muistiin)

$servername = "localhost";
$username = "root"; //MAMP
$password = "root";  //MAMP
$dbname = "kalevalakino"; //oma local hydocs

// Yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = $_POST["password"];

    // Etsitään käyttäjä sähköpostilla
    $stmt = $conn->prepare("SELECT id, firstname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Tarkistetaan löytyikö käyttäjä
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $firstname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // ✅ Oikea salasana: tallennetaan istuntotiedot
            $_SESSION["user_id"] = $id;
            $_SESSION["firstname"] = $firstname;

            // Ohjataan käyttäjä omalle sivulle
            header("Location: profiili.php");
            exit();
        } else {
            $message = "<p class='error'>Väärä salasana.</p>";
        }
    } else {
        $message = "<p class='error'>Käyttäjää ei löytynyt.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

