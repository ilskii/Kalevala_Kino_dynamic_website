<?php
session_start(); // Mahdollistaa istunnon käytön

require_once "connection.php"; // Yhdistetään tietokantaan

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(htmlspecialchars($_POST["email"])); // Suojataan syötteet
    $password = $_POST["password"];

    // Etsitään käyttäjä sähköpostilla
    $stmt = $conn->prepare("SELECT UserID, FirstName, PasswordHash FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $firstname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) { // Tarkistetaan salasana
            $_SESSION["user_id"] = $id;
            $_SESSION["firstname"] = $firstname;

            header("Location: profiili.php"); // Ohjataan käyttäjän profiiliin
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
