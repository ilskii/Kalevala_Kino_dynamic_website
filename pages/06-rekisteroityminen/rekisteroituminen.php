<?php
require_once "connection.php"; // Yhdistetään tietokantaan

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim(htmlspecialchars($_POST["FirstName"])); // Suojataan syötteet
    $lastname = trim(htmlspecialchars($_POST["LastName"]));
    $email = trim(htmlspecialchars($_POST["Email"]));
    $password = password_hash($_POST["Password"], PASSWORD_DEFAULT); // Hashataan salasana

    // Tarkistetaan, onko sähköposti jo käytössä
    $checkStmt = $conn->prepare("SELECT UserID FROM users WHERE Email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $message = "<p class='error'>Sähköposti on jo käytössä. Käytä toista osoitetta.</p>";
    } else {
        // Uuden käyttäjän lisääminen
        $stmt = $conn->prepare("INSERT INTO users (FirstName, LastName, Email, PasswordHash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

        if ($stmt->execute()) {
            header("Location: kirjautuminen.php"); // Ohjataan kirjautumissivulle
            exit();
        } else {
            $message = "<p class='error'>Virhe tallennuksessa: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $checkStmt->close();
}

$conn->close();
?>
