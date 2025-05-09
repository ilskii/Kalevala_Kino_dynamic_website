<?php
    session_start();

    // Yhteyden aktivointi tietokantaan
    require_once "../../connection.php";
    include "kirjautuminen.html";

    // Syötteiden vastaanotto ja suodatus
    $Email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $Password = $_POST["password"];

    // Valmisteltu SQL-kysely
    $stmt = $conn->prepare("SELECT PasswordHash FROM users WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $stmt->store_result();

    // Tarkistetaan, löytyykö käyttäjä
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($PasswordHash);
        $stmt->fetch();

        // Tarkistetaan salasanan oikeellisuus
        if (password_verify($Password, $PasswordHash)) {
            $_SESSION["user"] = $Email;
            // Ohjataan käyttäjä profiilisivulle
            header("Location: ../08-profiili/profiili.html");
            exit();
        } else {
            echo "Virheellinen salasana.";
        }
    } else {
        echo "Käyttäjää ei löytynyt.";
    }

    // Suljetaan yhteys
    $stmt->close();
    $conn->close();
?>
