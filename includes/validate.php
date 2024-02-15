<?php
# Denne filen burde bli inkludert i alle php-filer som en
# bruker som ikke er logget inn ikke skal ha tilgang til
session_start();

if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
    header("Location: /login.php");
    exit;
}

# TODO: ???
#if (strcmp($_SESSION["user"], "")) {
#    # TODO: Implementer en sjekk om $_SESSION["subject"] matcher emne som vises, hvis ikke, redirect
#    if ($_SESSION["subject"] !== "") {
#    header("Location: #");
#    exit;
#    }
#}

