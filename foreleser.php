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

            // Hent meldinger og deres tilknyttede svar
            $stmt = $conn->prepare("
                SELECT m.id AS melding_id, m.innhold AS melding_innhold, m.emne_emnekode,
                       s.id AS svar_id, s.innhold AS svar_innhold
                FROM melding AS m
                LEFT JOIN svar AS s ON m.id = s.melding_id
                WHERE m.emne_emnekode = ?
            ");
            $stmt->bind_param("s", $emnekode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "Meldings id: " . htmlspecialchars($row["melding_id"]) . "<br>Melding: " . htmlspecialchars($row["melding_innhold"]) . "<br>Emnekode: " . $row["emne_emnekode"] . "<br>";

                    // Hvis det finnes et tilknyttet svar, vis det
                    if (!empty($row["svar_id"])) {
                        echo "Svar: " . htmlspecialchars($row["svar_innhold"]);
                    }
                    echo "<form action='svar.php' method='post'>
                        Svar:
                        <input type='text' name='svar'><br>
                        <input type='hidden' name='emnekode' value='" . $row["emne_emnekode"] . "'>
                        <input type='hidden' name='id' value='" . $row["melding_id"] . "'>
                        <input type='submit' value='Send inn ditt svar!'>
                    </form>";
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
