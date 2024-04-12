<?php

session_start();

if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] === 1) {
        require_once "foreleser.php";
        exit;
    } elseif ($_SESSION["role"] === 2) {
        require_once "student.php";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasjon</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "inc/modules/header.php"; ?>

<main>
    <div class="wrapper">
        <article>
            <h2>Forside</h2>
            
        </article>
    </div>
</main>

<?php require_once "inc/modules/footer.php"; ?>

</body>

</html>
