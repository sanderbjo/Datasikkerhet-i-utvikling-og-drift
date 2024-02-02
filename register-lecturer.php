<?php
// Definer databaseforbindelsesdetaljer
require "includes/db-connection.php"; 

// Håndter registreringsskjemaet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $subjectName = $_POST['subject_name'];
    $subjectCode = $_POST['subject_code'];
    $pinCode = $_POST['pin_code'];

    // Enkel validering
    if (empty($name) || empty($email) || empty($password) || empty($subjectName) || empty($subjectCode) || empty($pinCode)) {
        echo "Fyll ut alle feltene.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
    } elseif (!is_numeric($pinCode) || strlen($pinCode) !== 4) {
        echo "Ugyldig PIN-kode. Den må være et 4-sifret tall.";
    } else {
        // Opplasting av bilde til webserveren
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Sjekk om filen allerede eksisterer
        if (file_exists($targetFile)) {
            echo "Beklager, filen eksisterer allerede.";
            $uploadOk = 0;
        }

        // Sjekk filtypen
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Bare JPG, JPEG, PNG og GIF filformater er tillatt.";
            $uploadOk = 0;
        }

        // Sjekk om $uploadOk er satt til 0 av en feil
        if ($uploadOk == 0) {
            echo "Beklager, bildet ble ikke lastet opp.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "Bildet " . basename($_FILES["image"]["name"]) . " har blitt lastet opp.";
                // Legg til foreleser i databasen
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sqlInsertLecturer = "INSERT INTO bruker (navn, epost, passord, rolle_id, bilde) VALUES ('$name', '$email', '$hashedPassword', 1, '$targetFile')";

                if ($conn->query($sqlInsertLecturer) === TRUE) {
                    echo "Foreleserregistrering vellykket!";

                    // Hent IDen til den nyregistrerte foreleseren
                    $lecturerId = $conn->insert_id;

                    // Legg til emne i databasen
                    $sqlInsertSubject = "INSERT INTO emne (bruker_id, emnekode, navn, pin) VALUES ($lecturerId, '$subjectCode', '$subjectName', '$pinCode')";

                    if ($conn->query($sqlInsertSubject) === TRUE) {
                        echo "Emneregistrering vellykket!";
                    } else {
                        echo "Feil under emneregistrering: " . $conn->error;
                    }
                } else {
                    echo "Feil under foreleserregistrering: " . $conn->error;
                }
            } else {
                echo "Beklager, det oppstod en feil ved opplasting av bildet.";
            }
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
