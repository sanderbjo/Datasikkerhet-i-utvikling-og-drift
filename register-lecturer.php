<?php
// Definer databaseforbindelsesdetaljer
require "includes/db-connection.php"; 

// Håndter registreringsskjemaet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Enkel validering
    if (empty($name) || empty($email) || empty($password)) {
        echo "Fyll ut alle feltene.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
    } else {
        // Opplasting av bilde til webserveren
        $targetDir = "uploads/";
        $originalFileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $originalFileName;

        // Legg til foreleser i databasen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlInsertLecturer = "INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES ('$name', '$email', '$hashedPassword', 1)";

        if ($conn->query($sqlInsertLecturer) === TRUE) {
            $lastInsertedId = $conn->insert_id;
            echo "Foreleserregistrering vellykket!";

            // Oppdater bildets filnavn til brukerens ID
            $newFileName = $lastInsertedId . "." . pathinfo($originalFileName, PATHINFO_EXTENSION);
            $newFilePath = $targetDir . $newFileName;

            // Flytt og gi nytt filnavn til bildet
            if (rename($targetFile, $newFilePath)) {
                echo "Bildet har blitt lastet opp".;
            } else {
                echo "Feil under flytting av bildet.";
            }

        } else {
            echo "Feil under registrering av foreleser: " . $conn->error;
        }
    }
}

// Lukk databaseforbindelsen
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foreleserregistrering</title>
</head>
<body>

    <h2>Foreleserregistrering</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
        Navn: <input type="text" name="name"><br>
        E-post: <input type="text" name="email"><br>
        Passord: <input type="password" name="password"><br>
        Bilde: <input type="file" name="image"><br>
        Emnenavn: <input type="text" name="subject_name"><br>
        Emnekode: <input type="text" name="subject_code"><br>
        Emnepinkode: <input type="text" name="pin_code" maxlength="4"><br>
        <input type="submit" value="Registrer">
    </form>

</body>
</html>