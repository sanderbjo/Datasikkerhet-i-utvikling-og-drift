<?php

require_once "inc/validation/login-validation.php";

logginCheckRedirect();

?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innstillinger</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "inc/modules/header.php" ?>

<main>
    <div class="wrapper">
        <section>
            <h2>Brukerinnstillinger</h2>
            <ul>
                <li><a href="/change-password.php">Bytt passord</a></li>
            </ul>
        </section>
    </div>
</main>

<?php require_once "inc/modules/footer.php" ?>

</body>

</html>