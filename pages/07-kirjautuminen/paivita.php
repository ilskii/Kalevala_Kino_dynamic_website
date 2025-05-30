<?php
session_start();

// Tarkistetaan, onko käyttäjä kirjautunut
if (!isset($_SESSION["user_id"])) {
    header("Location: kirjautuminen.html");
    exit();
}

require_once "connection.php"; // Yhdistetään tietokantaan

// Varmistetaan, että lomakkeen tiedot on lähetetty
if (isset($_POST["firstname"], $_POST["lastname"], $_POST["email"])) {
    $firstname = trim(htmlspecialchars($_POST["firstname"]));
    $lastname = trim(htmlspecialchars($_POST["lastname"]));
    $email = trim(htmlspecialchars($_POST["email"]));
    $user_id = $_SESSION["user_id"];

    // Päivitetään käyttäjän tiedot
    $stmt = $conn->prepare("UPDATE users SET FirstName = ?, LastName = ?, Email = ? WHERE UserID = ?");
    $stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION["firstname"] = $firstname; // Päivitetään istunnon nimi
        header("Location: profiili.php");
        exit();
    } else {
        echo "❌ Virhe päivityksessä: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
