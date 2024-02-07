<?php
require "includes/validate.php";
require "includes/db-connection.php";

//TODO: Gjestebrukere skal ha adgang til emner de har 4-sifret kode til

// Sjekk om emnekode er angitt i URL
if (isset($_GET['emnekode'])) {
    $emnekode = htmlspecialchars($_GET['emnekode']);

    // Hent informasjon om emnet fra databasen
    $query = "SELECT e.navn AS emnenavn, b.navn AS forelesernavn, b.bruker_id AS foreleser_id, bilde_path AS foreleserbilde
              FROM emne e
              JOIN bruker b ON e.bruker_id = b.id
              WHERE e.emnekode = '$emnekode'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $emneinfo = mysqli_fetch_assoc($result);
        $foreleserbildePath = $emneinfo['foreleserbilde']; // Path til foreleser bildert
    } else {
        // Håndter feil
        echo "Emnet ble ikke funnet.";
        exit();
    }
    
    
}

    // Sjekk om formen for å legge ut melding er utfylt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['melding_innhold'])) {
    // Valider inputdataen
    $melding_innhold = mysqli_real_escape_string($conn, $_POST['melding_innhold']);

    // Legg til meldingen i databasen
    $insert_query = "INSERT INTO melding (innhold, bruker_id, emne_emnekode) VALUES ('$melding_innhold', NULL, '$emnekode')";
    $insert_result = mysqli_query($conn, $insert_query);

    if (!$insert_result) {
        // Handle error
        echo "Feil ved innsending av melding.";
        exit();
    }
}

// Hent alle meldinger og svar for emnet fra databasen
$query_meldinger = "SELECT id, innhold FROM melding WHERE bruker_id IS NULL";
$result_meldinger = mysqli_query($conn, $query_meldinger);

if ($result_meldinger && mysqli_num_rows($result_meldinger) > 0) {
    $meldinger = mysqli_fetch_all($result_meldinger, MYSQLI_ASSOC);
} else {
    $meldinger = [];
}


?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $emneinfo['emnenavn']; ?> - Emneside</title>
</head>

<body>

<?php include "modules/header.php"; ?>

<main>
<div class="module-wrapper">
        <h2><?php echo $emneinfo['emnenavn']; ?> - Emneside</h2>

        <div class="foreleser-info">
            <p>Foreleser: <?php echo $emneinfo['forelesernavn']; ?></p>
            <img src="<?php echo $emneinfo['foreleserbilde']; ?>" alt="Foreleserbilde">
        </div>

        <h3>Meldinger fra Studenter</h3>
        <?php

        // Vis alle meldinger og svar for emnet
        if (!empty($meldinger)) {
            foreach ($meldinger as $melding) {
                echo "<div class='melding'>";
                echo "<p>Anonym skrev:</p>";
                echo "<p>{$melding['melding']}</p>";
                echo "<p>Tid: {$melding['opprettet_tid']}</p>";

                // Vis foreleseren sitt svar om det finnes
                if (!empty($melding['foreleser_svar'])) {
                    echo "<p>Foreleserens svar:</p>";
                    echo "<p>{$melding['foreleser_svar']}</p>";
                }

                // Legg til svarskjema for foreleseren
                if (empty($melding['foreleser_svar'])) {
                    echo "<form method='post' action='{$_SERVER["PHP_SELF"]}'>";
                    echo "<input type='hidden' name='melding_id' value='{$melding["id"]}'>";
                    echo "<label for='foreleser_svar'>Svar:</label>";
                    echo "<textarea name='foreleser_svar' id='foreleser_svar' rows='4' cols='50'></textarea>";
                    echo "<button type='submit'>Send svar</button>";
                    echo "</form>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>Ingen meldinger tilgjengelig for dette emnet.</p>";
        }
        ?>
    </div>
</main>

</body>

</html>
