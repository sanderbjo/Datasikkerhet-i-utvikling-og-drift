<?php
include "validate.php";

/* Foreløpig eksempelkode, skal endres i forhold til databasen
include "db_connection.php";
// Sjekk om emnekode er angitt i URL
if (isset($_GET['emnekode'])) {
    $emnekode = htmlspecialchars($_GET['emnekode']);

    // Hent informasjon om emnet fra databasen
    $query = "SELECT e.navn AS emnenavn, f.navn AS forelesernavn, f.bilde AS foreleserbilde
              FROM emner e
              JOIN forelesere f ON e.foreleser_id = f.id
              WHERE e.emnekode = '$emnekode'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $emneinfo = mysqli_fetch_assoc($result);
    } else {
        // Håndter feil
        echo "Emnet ble ikke funnet.";
        exit();
    }

    // Hent alle meldinger og svar for emnet fra databasen
    $query_meldinger = "SELECT m.id, m.melding, m.opprettet_tid
                        FROM meldinger m
                        WHERE m.emnekode = '$emnekode'";
    $result_meldinger = mysqli_query($conn, $query_meldinger);

    if ($result_meldinger && mysqli_num_rows($result_meldinger) > 0) {
        $meldinger = mysqli_fetch_all($result_meldinger, MYSQLI_ASSOC);
    } else {
        $meldinger = [];
    }
} else {
    // Håndter feil
    echo "Emnekode mangler.";
    exit();
}*/
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $emneinfo['emnenavn']; ?> - Emneside</title>
</head>

<body>

<?php include "header.html"; ?>

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
