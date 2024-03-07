<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["csrf-logout"]) && $_SESSION["csrf-logout"] === $_POST["auth-token"]) {
        session_destroy();
        header("Location: /login.php");
    } else {
        #TODO: Logge csrf token mismatch
    }
} else {
    header("Location: /");
}
exit;
