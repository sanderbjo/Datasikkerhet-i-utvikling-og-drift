<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["csrf-logout"]) && strcmp($_SESSION["csrf-logout"], $_POST["auth-token"]) === 0) {
        session_destroy();
        header("Location: /login.php");
    } else {
        #TODO: Logge csrf token mismatch
    }
} else {
    header("Location: /");
}
exit;
