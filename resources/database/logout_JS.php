<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Kirjaudutaan ulos...</title>
    <script>
        // Poistetaan kirjautumistiedot localStorageista
        localStorage.removeItem("loggedIn"); 
        setTimeout(() => {
            window.location.href = "kirjautuminen.html"; // Ohjataan kirjautumissivulle
        }, 500); // Odotetaan 0,5 sekuntia ennen uudelleenohjausta
    </script>
</head>
<body>
    <p>Kirjaudutaan ulos, odota...</p>
</body>
</html>
