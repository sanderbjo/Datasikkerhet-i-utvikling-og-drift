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

            $sql = "SELECT bruker_id,emnekode FROM emne WHERE bruker_id = " . $id;
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $emnekode = $row["emnekode"];
                }
            } else {
                echo "0 results";
            }

            $stmt = $conn->prepare("SELECT id,innhold,emne_emnekode FROM melding WHERE emne_emnekode = ?");
            $stmt->bind_param("s", $emnekode);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "meldings id: " . htmlspecialchars($row["id"]) . "<br>Melding: " . htmlspecialchars($row["innhold"])."<br>emnekode: ".$row["emne_emnekode"]. "<br>
           <form action='svar.php' method='post'>
           Svar: <input type='text' name='svar'><br>
           <input type='hidden' name='bruker_id' value='".$id."'>
           <input type='hidden' name='emnekode' value='".$row["emne_emnekode"]."'>
           <input type='hidden' name='id' value='".$row["id"]."'>
           <input type='submit' value='Send inn ditt svar!'>
           </form>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </section>
    </div>
</main>

</body>
</html>