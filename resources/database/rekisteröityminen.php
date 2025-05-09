<?php
    require_once "../../connection.php"; // Tietokantayhteyden aktivointi
    include "rekisteroityminen.html"; // Lomake näyttää HTML-pohjan

    // Varmistetaan, että tiedot tulevat POST-metodilla
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Suodatetaan ja haetaan lomaketiedot
        $firstName = htmlspecialchars($_POST["FirstName"]);
        $lastName = htmlspecialchars($_POST["LastName"]);
        $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
        $password = $_POST["Password"];

        // Salataan salasana
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Valmisteltu SQL-kysely turvallisuutta varten
        $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Email, PasswordHash) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $passwordHash);

        // Suoritetaan kysely ja tarkistetaan virheet
        if ($stmt->execute()) {
            echo "Rekisteröinti onnistui!<br>";
        } else {
            echo "Virhe rekisteröinnissä: " . $stmt->error . "<br>";
        }

        // Suljetaan yhteys
        $stmt->close();
        $conn->close();

        // Ohjataan käyttäjä kirjautumiseen
        header('Location: kirjautuminen.php');
        exit();
    } else {
        echo "Virhe: Lomake ei lähetetty oikein.";
    }
?>