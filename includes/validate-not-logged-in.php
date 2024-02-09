<?php
session_start();

if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
    if ($_SESSION["role"] === 1)
        header("Location: /foreleser.php");
    else
        header("Location: /student.php");
    exit;
}
$_SESSION["loggedIn"] = false;
