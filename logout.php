<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
        isset($POST["authentication-token"]) === $_SESSION["authentication-token"]) {
    session_destroy();
    header("Location: login.php");
} else {
    header("Location: index.php");
}
exit;
