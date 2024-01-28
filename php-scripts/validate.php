<?php
# Denne filen burde bli inkludert i alle php-filer som ikke angir login
session_start();

if (!isset($_SESSION["user"]) || strcmp($_SESSION["user"], "")) {
    header("Location: login.php");
    exit;
}

if (strcmp($_SESSION["user"], "anon")) {
    # TODO: Implementer en sjekk om $_SESSION["subject"] matcher emne som vises, hvis ikke, redirect
    if ($_SESSION["subject"] !== "") {
    header("Location: #");
    exit;
    }
}

