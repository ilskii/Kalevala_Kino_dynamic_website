<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: kirjautuminen.html");
    exit();
}

require_once "connection.php"; // Yhdistetään tietokantaan

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profile_image"])) {
    $targetDir = "../../uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true); // Luo kansio jos ei ole
    }

    $fileName = basename($_FILES["profile_image"]["name"]);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowedTypes)) {
        $newFileName = "profile_" . $userId . "." . $fileExt;
        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $relativePath = "uploads/" . $newFileName;

            // Päivitetään profiilikuva tietokannassa
            $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
            $stmt->bind_param("si", $relativePath, $userId);

            if ($stmt->execute()) {
                header("Location: profiili.php");
                exit();
            } else {
                echo "❌ Virhe profiilikuvan päivittämisessä: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "❌ Virhe ladattaessa tiedostoa.";
        }
    } else {
        echo "❌ Vain kuvatiedostot (JPG, PNG, GIF) sallitaan.";
    }

    $conn->close();
}
?>
