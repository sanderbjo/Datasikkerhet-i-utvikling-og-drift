<?php
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: login.php");
    exit;
}

# TODO: ???
if (strcmp($_SESSION["role"], "professor") !== 0) {
    header("Location: index.php");
    exit;
}