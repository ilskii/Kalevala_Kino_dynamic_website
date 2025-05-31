<?php
session_start();

require_once "connection.php"; // Yhdistetään tietokantaan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Suojataan syötteet
    $email = trim(htmlspecialchars($_POST["email"]));
    $password = $_POST["password"];

    // Haetaan käyttäjä sähköpostilla
    $stmt = $conn->prepare("SELECT UserID, FirstName, PasswordHash FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    $error = "";
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $firstname, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Kirjautuminen onnistui
            $_SESSION["user_id"] = $id;
            $_SESSION["firstname"] = $firstname;
            header("Location: profiili.php");
            exit();
        } else {
            // Käyttäjä löytyy, mutta salasana on väärä – säilytetään syötetty sähköposti
            $error = "Väärä salasana.";
            header("Location: kirjautuminen.html?error=" . urlencode($error) . "&email=" . urlencode($email));
            exit();
        }
    } else {
        // Käyttäjää ei löydy – jätetään kentät tyhjiksi
        $error = "Käyttäjää ei löytynyt.";
        header("Location: kirjautuminen.html?error=" . urlencode($error));
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
