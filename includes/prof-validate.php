<?php
session_start();

if (!isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] === false ||
    !strcmp($_SESSION["role"], "")) {
    header("Location: login.php");
    exit;
}