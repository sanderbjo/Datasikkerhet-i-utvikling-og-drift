<?php
require_once "includes/validate.php";
?>
<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php
require_once "modules/header.php"
?>

<main>
    <div class="wrapper">
        <section>
            <h2>Emner</h2>
            <?php
            require "includes/db-connection.php";

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (!empty($_POST["emnekode"]) && !empty($_POST["melding"])) {
                    $emnekode = $_POST["emnekode"];
                    $melding = $_POST["melding"];
                    $bruker_id = $_SESSION["id"];

                    $stmt = $conn->prepare("INSERT INTO melding (innhold, bruker_id, emne_emnekode) VALUES (?, ?, ?)");
                    $stmt->bind_param("sis", $melding, $bruker_id, $emnekode);
                    if ($stmt->execute()) {
                        echo "Melding sendt suksessfullt!";
                    } else {
                        echo "Feil under sending av melding: " . $conn->error;
                    }
                } else {
                    echo "Vennligst fyll ut alle feltene.";
                }
            }

            $sql = "SELECT emnekode, navn FROM emne;";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                echo "<select name='emnekode'>";
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["emnekode"]. "'>" . $row["navn"]. "</option>";
                }
                echo "</select>";
                echo "<textarea name='melding' placeholder='Skriv inn melding...'></textarea>";
                echo "<input type='submit' value='Send melding'>";
                echo "</form>";
            } else {
                echo "Ingen tilgjengelige emner.";
            }
            $conn->close();
            ?>
        </section>
    </div>
</main>

</body>

</html>
