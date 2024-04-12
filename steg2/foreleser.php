<?php
require_once "inc/validation/session-validation.php";
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
require_once "inc/modules/header.php"
?>

<main>
    <div class="wrapper">
        <section>
            <h2>Foreleser</h2>
            <?php
            require "inc/db/conn/db.php";
            // Hent emner tilknyttet foreleseren
            $id = $_SESSION["id"];
            $sql = "SELECT user_id, code, name FROM subject WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $emnekode = $row["code"];

                    // Her kan du utføre handlinger som krever $emnekode
                    echo "Emnekode: " . htmlspecialchars($emnekode) . "<br>";
                    echo "Emnenavn: " . htmlspecialchars($row["name"]) . "<br>";

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
            $stmt = $conn->prepare("SELECT m.id, m.content, m.subject_id, COUNT(r.message_id) AS antall_rapporteringer
                                    FROM message AS m
                                    LEFT JOIN report AS r ON m.id = r.message_id
                                    WHERE m.subject_id = ?
                                    GROUP BY m.id");
            $stmt->bind_param("s", $emnekode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Melding: " . htmlspecialchars($row["content"]) . "<br>";
                    echo "Emnekode: " . htmlspecialchars($row["subject_id"]) . "<br>";
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