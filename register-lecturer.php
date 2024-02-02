<?php
// Definer databaseforbindelsesdetaljer
// TODO: Opprett en uploads mappe samme sted som denne, evt endre path.
require "includes/db-connection.php"; 


// Håndter registreringsskjemaet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $subjectName = $_POST['subject_name'];
    $subjectCode = $_POST['subject_code'];

    // Enkel validering
    if (empty($name) || empty($email) || empty($password) || empty($subjectName) || empty($subjectCode)) {
        echo "Fyll ut alle feltene.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
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
                $sql = "INSERT INTO lecturers (name, email, password, image_path, subject_name, subject_code) VALUES ('$name', '$email', '$hashedPassword', '$targetFile', '$subjectName', '$subjectCode')";

                if ($conn->query($sql) === TRUE) {
                    echo "Registrering vellykket!";
                } else {
                    echo "Feil under registrering: " . $conn->error;
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
        Emnepinkode: <input type="text" name="subject_code"><br>
        <input type="submit" value="Registrer">
    </form>

</body>
</html>
