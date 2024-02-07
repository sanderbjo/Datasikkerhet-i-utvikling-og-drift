<?php
session_start();

// Sjekk om skjemaet er sendt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $pin_code = $_POST['pin_code'];

    // Sjekk om noen av feltene er tomme
    if (empty($name) || empty($email) || empty($password) || empty($subject_name) || empty($subject_code) || empty($pin_code)) {
        echo "Fyll ut alle feltene.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
    } else {
        // Sjekk om bildet ble lastet opp uten feil
        if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
            // Fortsett opplastingsprosessen
            // Opplasting av bilde til webserveren
            $targetDir = "uploads/";
            $originalFileName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $originalFileName;

            // Legg til foreleser i databasen
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            require "includes/db-connection.php";
            $sqlInsertLecturer = "INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES ('$name', '$email', '$hashedPassword', 1)";

            if ($conn->query($sqlInsertLecturer) === TRUE) {
                $lastInsertedId = $conn->insert_id;
                echo "Foreleserregistrering vellykket!";

                // Opprett emne
                $sqlInsertSubject = "INSERT INTO emne (emnekode, navn, pin, bruker_id) VALUES ('$subject_code', '$subject_name', '$pin_code', '$lastInsertedId')";
                if ($conn->query($sqlInsertSubject) === TRUE) {
                    echo "Emne registrert vellykket!";
                } else {
                    echo "Feil under registrering av emne: " . $conn->error;
                }

                // Oppdater bildets filnavn til brukerens ID
                $newFileName = $lastInsertedId . "." . pathinfo($originalFileName, PATHINFO_EXTENSION);
                $newFilePath = $targetDir . $newFileName;

                // Flytt og gi nytt filnavn til bildet
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $newFilePath)) {
                    echo "Bildet har blitt lastet opp.";
                } else {
                    echo "Feil under opplasting av bildet.";
                }

            } else {
                echo "Feil under registrering av foreleser: " . $conn->error;
            }

            // Lukk databaseforbindelsen
            $conn->close();
        } else {
            echo "Feil under opplasting av bildet: " . $_FILES["image"]["error"];
        }
    }
}
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
