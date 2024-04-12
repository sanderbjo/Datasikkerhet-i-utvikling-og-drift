<?php
require_once "inc/validation/session-validation.php";
# TODO: Skal svar være en egen side?
# Akuratt nå må brukeren trykke på en link for å gå tilbake men vi kan bare
# sende dem tilbake etter at vi har registrert svaret dems.
# - Tobias
?>
<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Svar</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php
require_once "inc/modules/header.php";
?>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "inc/db/conn/db.php";

    $sql = "INSERT INTO `svar` (`id`, `innhold`, `melding_id`, `bruker_id`) VALUES (?, ?, ?, ?)";
    
    try {
        if (!empty($_POST["id"]) && ! empty($_POST["svar"])) {
            $stmt = $conn->prepare($sql);
            # TODO: Feil i database?
            # Vet ikke hvordan databasen ser ut nå men i oppsets-scriptet på
            # discord er ikke "melding_id" en foreign key, noe det burde være.
            # I tillegg settes "id" og "melding_id" til samme verdi i koden under,
            # så trenger vi da begge kolonnene? På canvas står det at det skal kun
            # være et svar per melding noe som betyr at svar og melding skal ha et
            # one-to-one forhold, så da gir det vel mest mening å sette id til en
            # foreign key og slette melding_id.
            # Tanker???
            # - Tobias
            $stmt->bind_param("ssii", $_POST["id"], $_POST["svar"], $_POST["id"], $_SESSION["id"]);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "Svaret ditt har blitt registrert";
            } else {
                echo "Det oppstod en feil";
            }
        } else {
            echo "Et felt er ikke fyllt ut.";
        }
    } catch (mysqli_sql_exception $e) {
        // Check if it's a duplicate entry error
        if ($e->getCode() === 1062) { // 1062 is the MySQL error code for duplicate entry
            echo "Denne meldingen har allerede et svar!";
        } else {
            // Handle other database-related errors
            echo "det skjedde en uforvented database feil!";
        }
    }
       echo "<br><a href='foreleser.php'>tilbake til foreleser siden</a>";
       $conn->close();
}
?>


</body>
</html>