<?php
require_once "includes/validate-not-logged-in.php";

$message = "";

$name = $email = "";

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
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrer foreleser</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php
require_once "modules/header.php";
?>

<main>
    <div class="module-wrapper">
        <div class="register-lecturer-module">
            <h2 class="module-header">Foreleserregistrering</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>"
                  class="register-lecturer-form" enctype="multipart/form-data" class="register-lecturer-form">
                <?php if (!empty($message)) echo "<div class='center'>" . $message . "</div>" ?>
                <h3>Brukerkonto</h3>
                <div class="register-form-name">
                    <label for="name">Navn</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name) ?>">
                </div>
                <div class="register-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email) ?>">
                </div>
                <div class="register-form-password">
                    <label for="password">Passord</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="register-form-picture">
                    <label for="image">Bilde</label>
                    <input type="file" name="image" id="image">
                </div>
                <h3>Emne</h3>
                <div class="register-form-subject-name">
                    <label for="subject-name">Emnenavn</label>
                    <input type="text" name="subject_name" id="subject-name">
                </div>
                <div class="register-form-subject-code">
                    <label for="subject-code">Emnekode</label>
                    <input type="text" name="subject_code" id="subject-code">
                </div>
                <div class="register-form-subject-pin">
                    <label for="subject-pin">Emnepinkode</label>
                    <input type="text" name="pin_code" id="subject-pin" inputmode="numeric"
                           minlength="4" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="form-submit">
                    <button>Registrer</button>
                </div>
            </form>
            <div class="register-lecturer-other-options center">
                <div class="register-lecturer-option-other-signup center">
                    <p><a href="/register-student.php">Opprett en studentkonto</a></p>
                </div>
                <div class="register-lecturer-option-login">
                    <p>Har du en konto? <a href="/login.php">Logg inn</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
