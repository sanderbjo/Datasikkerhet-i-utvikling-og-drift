<?php
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: /login.php");
    exit;
}

# Sender deg til forsiden hvis du er student
if ($_SESSION["role"] === 2) {
    header("Location: /student.php");
    exit;
}