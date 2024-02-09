<?php
session_start();

require_once "includes/db-connection.php";

// Sjekk om brukeren er innlogget som student
if (isset($_SESSION["bruker"])) {
    // Hent studentens emner fra databasen
    $query = "SELECT emnekode, navn FROM emne";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Vis alle emner for studenten
        echo "<h2>Velg Emne</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<a href='subject-details.php?emnekode={$row['emnekode']}'>{$row['navn']}</a> <br>";
        }
    } else {
        echo "Ingen emner tilgjengelig.";
    }
} elseif ($_SESSION["bruker"] === 0) {
    // Hent PIN-koden fra session
    $pin = isset($_SESSION["subject"]) ? $_SESSION["subject"] : "";

    // Sjekk om brukeren har angitt en gyldig PIN-kode
    if (!empty($pin) && is_numeric($pin) && strlen($pin) === 4) {
        // Spørring for å hente emner basert på PIN-koden
        $query = "SELECT * FROM emne WHERE pin = '$pin'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Viser alle emner som er tilknyttet den gitte PIN-koden
            echo "<h2>Emner</h2>";
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li><a href='subject-details.php?emnekode={$row['emnekode']}'>{$row['navn']}</a></li>";
            }
            echo "</ul>";
        } else {
            echo "Ingen emner tilgjengelig for den angitte PIN-koden.";
        }
    } else {
        echo "Ugyldig PIN-kode.";
    }
} else {
    // Hvis brukeren ikke er logget inn
    echo "Ingen tilgang.";
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
$query_meldinger = "SELECT m.id, m.innhold, m.foreleser_svar
                    FROM melding m
                    LEFT JOIN bruker b ON m.bruker_id = b.id
                    WHERE m.emne_emnekode = '$emnekode'";
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
                echo "<p>{$melding['innhold']}</p>";

                // Vis foreleseren sitt svar om det finnes
                if (!empty($melding['foreleser_svar'])) {
                    echo "<p>Foreleserens svar:</p>";
                    echo "<p>{$melding['foreleser_svar']}</p>";
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
