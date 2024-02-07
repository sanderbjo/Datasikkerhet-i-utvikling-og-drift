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

            $sql = "SELECT emnekode, navn FROM emne;";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='" . $row["emnekode"]. ".php'>" . $row["navn"]. "</a></li>";
                }
                echo "</ul>";
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

