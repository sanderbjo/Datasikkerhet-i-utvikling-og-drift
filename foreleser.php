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

            # $urlq = $_SERVER['QUERY_STRING'];
            # parse_str($urlq, $queryArray);

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
                    $bildeSti = "uploads/" . $foreleserId . ".jpg";
                
                    // Sjekk om bilde finnes
                    if (file_exists($bildeSti)) {
                        echo "<img src='" . $bildeSti . "' width='300' height='200' alt='Bilde av foreleser'><br>";
                    } else {
                        echo "Bilde ikke tilgjengelig";
                    }
                }
                
            } else {
                echo "Ingen emner funnet for denne brukeren.";
            }

            $stmt = $conn->prepare("SELECT id, innhold, emne_emnekode FROM melding WHERE emne_emnekode = ?");
            $stmt->bind_param("s", $emnekode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "meldings id: " . htmlspecialchars($row["id"]) . "<br>Melding: " . htmlspecialchars($row["innhold"]) . "<br>emnekode: " . $row["emne_emnekode"] . "<br>
                    <form action='svar.php' method='post'>
                    Svar: <input type='text' name='svar'><br>
                    <input type='hidden' name='bruker_id' value='" . $id . "'>
                    <input type='hidden' name='emnekode' value='" . $row["emne_emnekode"] . "'>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
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