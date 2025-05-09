<?php
$servername = "localhost";
$username = "root";
$password = "root"; // MAMP:in oletussalasana
$dbname = "kalevalakino";

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST["FirstName"]);
    $lastname = htmlspecialchars($_POST["LastName"]);
    $email = htmlspecialchars($_POST["Email"]);
    $password = password_hash($_POST["Password"], PASSWORD_DEFAULT);

    // Tarkistetaan onko sähköposti jo käytössä
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $message = "<p class='error'>Sähköposti on jo käytössä. Käytä toista osoitetta.</p>";
    } else {
        // Uuden käyttäjän lisääminen
        $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstname, $lastname, $email, $password);

        if ($stmt->execute()) {
            // ✅ Ohjataan kirjautumissivulle
            header("Location: ../../pages/07-kirjautuminen/kirjautuminen.html");
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
