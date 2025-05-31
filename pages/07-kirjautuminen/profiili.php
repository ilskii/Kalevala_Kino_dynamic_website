<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: kirjautuminen.html");
    exit();
}

require_once "connection.php"; // Yhdistetään tietokantaan

$user_id = $_SESSION["user_id"];
$sql = "SELECT FirstName, LastName, Email, ProfileImage FROM users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elokuvakerho</title>
    <link rel="stylesheet" href="../../pages/07-kirjautuminen/profiili.css">
    <link rel="stylesheet" href="../../resources/css/styles.css">
    <!--Google Fonts: Pirata One ja Roboto-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!--Favicon-->
    <link rel="apple-touch-icon" sizes="180x180" href="../../resources/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../resources/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../resources/favicon-16x16.png">
    <link rel="manifest" href="../../resources/site.webmanifest">
</head>
<body>
    <header class="main-header">

        <!--Side-navi-->
        <nav class="side-nav">
            <button class="dropbtn"><i class="menu-icon"></i></button>
            <div class="side-nav-content">
                <a href="../../pages/01-etusivu/etusivu.html">Etusivu</a>
                <a href="../../pages/02-elokuvat/varaus.html">Elokuvat</a>
                <a href="../../pages/03-elokuvawiki/elokuvawiki.html">Elokuvawiki</a>
                <a href="../../pages/04-elokuvakerho/elokuvakerho.html">Elokuvakerho</a>
                <a href="../../pages/05-tietoameista/tietoameista.html">Tietoa meistä</a>
                <span class="divider">|</span>
                <a id="profile-link" href="../07-kirjautuminen/profiili.php" class="nav-link">Profiili</a>
                <a id="login-link" href="../07-kirjautuminen/kirjautuminen.html" class="nav-link">Kirjaudu</a>
                <a id="logout-link" href="../07-kirjautuminen/logout.php" class="nav-link">Kirjaudu ulos</a>
                <a id="register-link" href="../06-rekisteroityminen/rekisteroityminen.html" class="nav-link">Rekisteröidy</a>
            </div>
        </nav>

        <!--Logo ja otsikko-->
        <a href="../../index.html">
            <div class="container-logo-header">
                <i class="logo-header"></i>
            </div>
        </a>

        <!--Top-navi-->
        <nav class="top-nav">
            <div class="top-nav-left">
                <a href="../../pages/01-etusivu/etusivu.html" class="nav-link">Etusivu</a>
                <a href="../../pages/02-elokuvat/varaus.html" class="nav-link">Elokuvat</a>
                <a href="../../pages/03-elokuvawiki/elokuvawiki.html" class="nav-link">Elokuvawiki</a>
                <a href="../../pages/04-elokuvakerho/elokuvakerho.html" class="nav-link">Elokuvakerho</a>
                <a href="../../pages/05-tietoameista/tietoameista.html" class="nav-link">Tietoa meistä</a>
            </div>
            <div class="top-nav-right">
              <input class="nav-searchbar" type="text" id="nav-searchBar" placeholder="Haku sivulta..">                <ul id="resultsList"></ul>
                <a id="profile-link" href="profiili.php" class="nav-link">Profiili</a>
                <a id="login-link" href="kirjautuminen.html" class="nav-link">Kirjaudu</a>
                <a id="logout-link" href="logout.php" class="nav-link">Kirjaudu ulos</a>
                <a id="register-link" href="../06-rekisteroityminen/rekisteroityminen.html" class="nav-link">Rekisteröidy</a>
          </div>
        </nav>
    </header>
    <main>
        <div class="otsikko" id="otsikko">
            <h1>Kalevala profiili</h1>
        </div>

        <div class="profile-container" id="register-container">
            <section class="profile-section">
                <h2>Tervetuloa, <?php echo htmlspecialchars($user["FirstName"]); ?>!</h2>
            </section>

            <section class="profile-section">
                <h2>Lataa profiilikuva</h2>
                <form action="upload_image.php" method="POST" enctype="multipart/form-data">
                    <input class="profile-input" type="file" name="profile_image" accept="image/*" required>
                    <button class="button" type="submit">Lataa kuva</button>
                </form>

                <?php if (!empty($user['ProfileImage'])): ?>
                    <p>Kuvan polku: <?php echo htmlspecialchars($user['ProfileImage']); ?></p>
                    <img class="profile-picture" src="../../<?php echo htmlspecialchars($user['ProfileImage']); ?>" alt="Profiilikuva">
                <?php endif; ?>
            </section>

            <section class="profile-section">
                <p><strong>Nimi:</strong> <?php echo htmlspecialchars($user["FirstName"] . " " . $user["LastName"]); ?></p>
                <p><strong>Sähköposti:</strong> <?php echo htmlspecialchars($user["Email"]); ?></p>
            </section>

            <section class="profile-section">
                <h2>Päivitä tietosi</h2>
                <form action="paivita.php" method="POST">
                    <div class="user-details">
                        <div class="input-box">
                            <input class="profile-input" type="text" name="firstname" value="<?php echo htmlspecialchars($user["FirstName"]); ?>" required>
                            <input class="profile-input" type="text" name="lastname" value="<?php echo htmlspecialchars($user["LastName"]); ?>" required>
                            <input class="profile-input" type="email" name="email" value="<?php echo htmlspecialchars($user["Email"]); ?>" required>
                        </div>
                    </div>
                    <button class="button" type="submit">Päivitä tiedot</button>
                </form>
            </section>

            <section class="profile-section">
                <h2>Kirjaudu ulos</h2>
                <form action="logout.php" method="POST">
                    <button class="button" type="submit">Kirjaudu ulos</button>
                </form>
                <h2>Poista käyttäjätili</h2>
                <form action="poista.php" method="POST" onsubmit="return confirm('Haluatko varmasti poistaa tilisi?');">
                    <button class="button" type="submit">Poista tili pysyvästi</button>
                </form>
            </section>
        </div>
    </main>

    <script src="../../resources/javascript/javascript-shared.js" defer></script>
</body>
</html>
