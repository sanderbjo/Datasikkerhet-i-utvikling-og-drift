<?php
require_once "includes/prof-validate.php";
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreleser</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php
require_once "modules/header.php"
?>

<main>
    <div class="wrapper">
        <section>
            <h2>Foreleser</h2>
            <?php
            require "includes/db-connection.php";

            // Hent emner tilknyttet foreleseren
            $id = $_SESSION["id"];
            $sql = "SELECT bruker_id, emnekode, navn FROM emne WHERE bruker_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $emnekode = $row["emnekode"];

                    // Her kan du utføre handlinger som krever $emnekode
                    echo "Emnekode: " . htmlspecialchars($emnekode) . "<br>";
                    echo "Emnenavn: " . htmlspecialchars($row["navn"]) . "<br>";

                    // Hent bilde basert på foreleserens ID
                    $foreleserId = $_SESSION["id"];
                    $bildeStiJPG = "uploads/" . $foreleserId . ".jpg";
                    $bildeStiPNG = "uploads/" . $foreleserId . ".png";
                    $bildeStiGIF = "uploads/" . $foreleserId . ".gif";

                    // Sjekk om bilde finnes
                    if (file_exists($bildeStiJPG)) {
                        echo "<img src='" . $bildeStiJPG . "' width='300' height='200' alt='Bilde av foreleser'><br>";
                    } elseif (file_exists($bildeStiPNGGIF)){
                        echo "<img src='" . $bildeStiPNG . "' width='300' height='200' alt='Bilde av foreleser'><br>";
                    } elseif (file_exists($bildeStiGIF)) {
                        echo "<img src='" . $bildeStiGIF . "' width='300' height='200' alt='Bilde av foreleser'><br>";
                    } else {
                        echo "Bilde ikke tilgjengelig<br>";
                    }
                }

            } else {
                echo "Ingen emner funnet for denne brukeren.";
            }

            // Hent meldinger tilknyttet emnet
            $stmt = $conn->prepare("SELECT m.id, m.innhold, m.emne_emnekode, COUNT(r.melding_id) AS antall_rapporteringer
                                    FROM melding AS m
                                    LEFT JOIN rapporterte_meldinger AS r ON m.id = r.melding_id
                                    WHERE m.emne_emnekode = ?
                                    GROUP BY m.id");
            $stmt->bind_param("s", $emnekode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Melding: " . htmlspecialchars($row["innhold"]) . "<br>";
                    echo "Emnekode: " . htmlspecialchars($row["emne_emnekode"]) . "<br>";
                    echo "Antall rapporteringer: " . htmlspecialchars($row["antall_rapporteringer"]) . "<br>";

                    // Vis en visuell indikator hvis meldingen er rapportert
                    if ($row["antall_rapporteringer"] > 0) {
                        echo "<p style='color: red;'>Denne meldingen er rapportert.</p>";
                    }

                    // Legg til logikk for å vise andre detaljer om meldingen om nødvendig
                }
            } else {
                echo "Ingen meldinger funnet for dette emnet.";
            }

            $conn->close();
            ?>
        </section>
    </div>
</main>

</body>
</html>
