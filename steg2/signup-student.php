<?php

require_once "inc/validation/session-validation.php";
require_once "inc/password/password.php";
# TODO: Databasefil
# require "DATABASEFIL";


notLoggedInOrRedirect();


define("FORM_NOT_FILLED",               "<p class='error'>Alle feltene må være fylt ut</p>");
define("INVALID_EMAIL",                 "<p class='error'>Ugyldig e-post</p>");
define("EMAIL_ALLREADY_IN_USE",         "<p class='error'>E-posten er i bruk</p>");
define("PASSWORD_TOO_SHORT",            "<p class='error'>Passordet er for kort. Minst " . PV_CON_MINIMUM_PASSWORD_LENGTH . " tegn</p>");
define("PASSWORD_CONFIRMATION_FAIL",    "<p class='error'>Passordet matcher ikke med bekreftende passordet</p>");
define("DATABASE_ERROR",                "<p class='error'>Feil i database</p>");
define("GENERIC_ERROR",                 "<p class='error'>En feil har oppstått. Prøv igjen</p>");



$signupError = "";

$name = $email = $password = $passwordConfirmation = "";
$id = -1;
# 2 = Student
$role = 2;

if (!isset($_SESSION["csrf-signup"]))
    $_SESSION["csrf-signup"] = generateAuthToken();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (strcmp($_POST["auth-token"], $_SESSION["csrf-signup"]) !== 0) {
        $signupError = GENERIC_ERROR;
    } else {
        $_SESSION["csrf-signup"] = generateAuthToken();
        if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST["password-confirmation"])) {
            $signupError = FORM_NOT_FILLED;
        } else {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirmation = $_POST['password-confirmation'];

            if (validateEmail($email) !== IV_ERR_OK) 
                $signupError = INVALID_EMAIL;
            elseif (strcmp($password, $passwordConfirmation) !== 0)
                $signupError = PASSWORD_CONFIRMATION_FAIL;
            elseif (checkIfEmailExists($conn, $email))
                $signupError = EMAIL_ALLREADY_IN_USE;
        }
    }
    if (empty($signupError)) {
        
        if (addUser($conn, $email, $password, $name, $role)) {
            # $id = getId($conn, $email);
            $id = $conn->insert_id;
            $loginData = [];
            $loginData["id"] = $id;
            $loginData["email"] = $email;
            $loginData["name"] = $name;
            $loginData["role"] = $role;
            login($loginData);

            header("Location: /");
            exit;
        } else {
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
    <title>Registrer student</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "inc/modules/header.php"; ?>

<main>
    <div class="module-wrapper">
        <div class="signup-student-module">
            <h2 class="module-header">Studentregistrering</h2>
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="signup-student-form">
                <?php if (!empty($signupError)) echo "<div class='center'>$signupError</div>"; ?>
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
                <div class="form-submit">
                    <button>Registrer</button>
                </div>
            </form>
            <div class="signup-student-other-options center">
                <div class="signup-student-option-other-signups center">
                    <p><a href="/signup-lecturer.php">Opprett en foreleserkonto</a></p>
                </div>
                <div class="signup-student-option-login">
                    <p>Har du en konto? <a href="/login.php">Logg inn</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "inc/modules/footer.php"; ?>

</body>

</html>
