<?php

require_once "inc/validate/session-validate.php";
require_once "inc/password/password.php";
# TODO: Databasefil
require "DATABASEFIL";


notLoggedInOrRedirect();


define("FORM_NOT_FILLED",               "<p class='error'>Alle feltene må være fylt ut</p>");
define("INVALID_EMAIL",                 "<p class='error'>Ugyldig e-post</p>");
define("INVALID_SUBJECT_PIN",           "<p class='error'>Ugyldig pinkode for emne</p>");
define("INVALID_IMAGE",                 "<p class='error'>Ugyldig bilde</p>");
define("EMAIL_ALLREADY_IN_USE",         "<p class='error'>E-posten er i bruk</p>");
define("PASSWORD_TOO_SHORT",            "<p class='error'>Nytt passord er for kort. Minst " . PV_CON_MINIMUM_PASSWORD_LENGTH . " tegn</p>");
define("PASSWORD_CONFIRMATION_FAIL",    "<p class='error'>Nytt Passord matcher ikke med bekreftende passord</p>");
define("DATABASE_ERROR",                "<p class='error'>Feil i database</p>");
define("GENERIC_ERROR",                 "<p class='error'>En feil har oppstått. Prøv igjen</p>");
define("SUBJECT_CODE_ALLREADY_IN_USE",  "<p class='error'>Emnekoden er allerede i bruk</p>");



$signupError = "";
$name = $email = $password = $passwordConfirmation = $subjectName = $subjectCode = $subjectPin = "";

if (!isset($_SESSION["csrf-signup"]))
    $_SESSION["csrf-signup"] = generateAuthToken();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (strcmp($_POST["auth-token"], $_SESSION["csrf-signup"]) !== 0) {
        $signupError = GENERIC_ERROR;
    } else {
        $_SESSION["csrf-signup"] = generateAuthToken();
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST["password-confirmation"]) || empty($_POST['subject-name']) || empty($_POST['subject-code']) || empty($_POST['subject-pin'])) {
            $signupError = FORM_NOT_FILLED;
        } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password-confirmation'];
            $subjectName = $_POST['subject-name'];
            $subjectCode = $_POST['subject-code'];
            $subjectPin = $_POST['subject-pin'];

            if (validateEmail($email) !== IV_ERR_OK) 
                $signupError = INVALID_EMAIL;
            elseif (strcmp($password, $passwordConfirmation) !== 0)
                $signupError = PASSWORD_CONFIRMATION_FAIL;
            elseif (validateSubjectPin($subjectPin) !== IV_ERR_OK)
                $signupError = INVALID_SUBJECT_PIN;
            elseif ($_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE && validateImage($_FILES["image"]) !== IV_ERR_OK)
                $signupError = INVALID_IMAGE;
            elseif (checkIfEmailExists($conn, $email))
                $signupError = EMAIL_ALLREADY_IN_USE;
            elseif (checkIfSubjectExists($conn, $subjectCode))
                $signupError = SUBJECT_CODE_ALLREADY_IN_USE;
        }
    }
    if (empty($signupError)) {
        $conn->begin_transaction();
        if (addUser($conn, $email, $password, $name, 1) &&
                addSubject($conn, $subjectName, $subjectCode, $subjectPin)) {
            $conn->commit();
            
            $id = getId($conn, $email);

            if ($_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
                $imageId = uniqid("", true);
                setImage($conn, $id, $imageId);
                $uploadDirectory = "/uploads/";
                $filePath = $uploadDirectory . $imageId . ".jpg";
                move_uploaded_file($_FILES["image"]["tmp_name"], $filePath);

                if (exif_imagetype($filePath) === IMAGETYPE_PNG)
                    convertPngToJpg($filePath, $filePath);
            }

            $loginData = [];
            $loginData["id"] = $id;
            $loginData["email"] = $email;
            $loginData["name"] = $name;
            $loginData["role"] = 1;
            login($loginData);

            header("Location: /");
            exit;
        } else {
            $conn->rollback();
            $signupError = DATABASE_ERROR;
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

<?php require_once "inc/modules/header.php"; ?>

<main>
    <div class="module-wrapper">
        <div class="signup-lecturer-module">
            <h2 class="module-header">Foreleserregistrering</h2>
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                  class="signup-lecturer-form" enctype="multipart/form-data">
                <?php if (!empty($signupError)) echo "<div class='center'>$signupError</div>"; ?>
                <h3>Brukerkonto</h3>
                <div class="signup-form-name">
                    <label for="name">Navn</label>
                    <input type="text" name="name" id="name" required value="<?= htmlspecialchars($name); ?>">
                </div>
                <div class="signup-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" required value="<?= htmlspecialchars($email); ?>">
                </div>
                <div class="signup-form-password">
                    <label for="password">Passord</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="signup-form-password-confirmation">
                    <label for="password-confirmation">Bekreft passord</label>
                    <input type="password" name="password-confirmation" id="password-confirmation" required>
                </div>
                <div class="signup-form-picture">
                    <label for="image">Bilde (Gyldig filtyper: jpg, png)</label>
                    <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                </div>
                <h3>Emne</h3>
                <div class="signup-form-subject-name">
                    <label for="subject-name">Emnenavn</label>
                    <input type="text" name="subject-name" id="subject-name" required value="<?= htmlspecialchars($subjectName); ?>">
                </div>
                <div class="signup-form-subject-code">
                    <label for="subject-code">Emnekode</label>
                    <input type="text" name="subject-code" id="subject-code" required value="<?= htmlspecialchars($subjectCode); ?>">
                </div>
                <div class="signup-form-subject-pin">
                    <label for="subject-pin">Emnepinkode</label>
                    <input type="text" name="subject-pin" id="subject-pin" required value="<?= htmlspecialchars($subjectPin); ?>" inputmode="numeric"
                           minlength="4" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="form-submit">
                    <button>Registrer</button>
                </div>
            </form>
            <div class="signup-lecturer-other-options center">
                <div class="signup-lecturer-option-other-signups center">
                    <p><a href="/signup-student.php">Opprett en studentkonto</a></p>
                </div>
                <div class="signup-lecturer-option-login">
                    <p>Har du en konto? <a href="/login.php">Logg inn</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "inc/modules/footer.php"; ?>

</body>

</html>
