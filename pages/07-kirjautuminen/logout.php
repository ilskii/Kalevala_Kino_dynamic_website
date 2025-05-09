<?php
session_start();
session_destroy();
header("Location: kirjautuminen.html");
exit();
