<?php

session_start();

function isLoggedIn() {
    if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true)
        return true;
    return false;
}

function loggedInOrRedirect() {
    if (isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }
}

function notLoggedInOrRedirect() {
    if (!isLoggedIn()) {
        header("Location: /");
        exit;
    }
}

function generateAuthToken() {
    return bin2hex(random_bytes(32));
}