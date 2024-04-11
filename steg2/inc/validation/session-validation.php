<?php

session_start();

function isLoggedIn() {
    if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true)
        return true;
    return false;
}

function loggedInOrRedirect() {
    if (!isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }
}

function notLoggedInOrRedirect() {
    if (isLoggedIn()) {
        header("Location: /");
        exit;
    }
}

function generateAuthToken() {
    return bin2hex(random_bytes(32));
}

function login(array $data) {
    if (!isset($data["id"]) || !isset($data["email"]) || !isset($data["name"]) || !isset($data["role"]))
        return false;

    $_SESSION["loggedIn"] = true;
    $_SESSION["id"] = $data["id"];
    $_SESSION["email"] = $data["email"];
    $_SESSION["name"] = $data["name"];
    $_SESSION["role"] = $data["role"];

    return true;
}
