<?php

require_once "inc/validation/session-validation.php";
require_once "inc/pw/password.php";

loggedInOrRedirect();


define("PASSWORD_CHANGE_SUCCESS",       "<p class='success'>Passordet har blitt oppdatert</p>");

define("FORM_NOT_FILLED",               "<p class='error'>Alle feltene må være fylt ut</p>");
define("PASSWORD_TOO_SHORT",            "<p class='error'>Nytt passord er for kort. Minst " . PV_CON_MINIMUM_PASSWORD_LENGTH . " tegn</p>");
define("PASSWORD_CONFIRMATION_FAIL",    "<p class='error'>Nytt Passord matcher ikke med bekreftende passord</p>");
define("WRONG_PASSWORD",                "<p class='error'>Gammelt passord er feil</p>");
define("DATABASE_ERROR",                "<p class='error'>Feil i database</p>");
define("GENERIC_ERROR",                 "<p class='error'>En feil har oppstått. Prøv igjen</p>");



$id = $_SESSION["id"];
$name = $_SESSION["name"];

$changePasswordError = "";
$oldPassword = $newPassword = $newPasswordConfirmation = "";
$passwordValidation = -1;


if (!isset($_SESSION["csrf-changePassword"]))
    $_SESSION["csrf-changePassword"] = generateAuthToken();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (strcmp($_POST["auth-token"], $_SESSION["csrf-changePassword"]) !== 0) {
        $changePasswordError = GENERIC_ERROR;
    } else {
        $_SESSION["csrf-changePassword"] = generateAuthToken();

        if (empty($_POST["old-password"]) || empty($_POST["new-password"]) || empty($_POST["new-password-confirmation"])) {
            $changePasswordError = FORM_NOT_FILLED;
        } else {
            $oldPassword = $_POST["old-password"];
            $newPassword = $_POST["new-password"];
            $newPasswordConfirmation = $_POST["new-password-confirmation"];

            if (strcmp($newPassword, $newPasswordConfirmation) !== 0)
                $changePasswordError = PASSWORD_CONFIRMATION_FAIL;

            $passwordValidation = validatePassword($newPassword);
            switch ($passwordValidation) {
                case PV_ERR_OK:
                    break;
                case PV_ERR_PASSWORD_TOO_SHORT:
                    $changePasswordError = PASSWORD_TOO_SHORT;
                    break;
                default:
                    $changePasswordError = "Bruh";
            }
        }
    }

    if (empty($changePasswordError)) {
        require "inc/db/conn/db.php";
        require_once "inc/db/queries/user-management.php";

        $resultPassword = getPassword($conn, $id);

        if (password_verify(pepperPassword($oldPassword), $resultPassword)) {
            if (setPassword($conn, $id, $newPassword)) {
                $changePasswordError = PASSWORD_CHANGE_SUCCESS;
            } else {
                $changePasswordError = DATABASE_ERROR;
            }
        } else {
            $changePasswordError = WRONG_PASSWORD;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bytt passord</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "inc/modules/header.php" ?>

<main>
    <div class="module-wrapper">
        <div class="change-password-module">
            <h2 class="module-header">Bytt passord for <?= htmlspecialchars($name); ?></h2>
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="change-password-form">
                <input type="hidden" name="auth-token" value="<?= $_SESSION["csrf-changePassword"]; ?>">
                <?php if (!empty($changePasswordError)) echo "<div class='center'>$changePasswordError</div>"; ?>
                <div class="change-password-form-old-password">
                    <label for="old-password">Gammelt passord</label>
                    <input type="password" name="old-password" id="old-password" required>
                </div>
                <div class="change-password-form-new-password">
                    <label for="new-password">Nytt passord</label>
                    <input type="password" name="new-password" id="new-password" required>
                </div>
                <div class="change-password-form-new-password-confirmation">
                    <label for="new-password-confirmation">Bekreft nytt passord</label>
                    <input type="password" name="new-password-confirmation" id="new-password-confirmation" required>
                </div>
                <div class="form-submit">
                    <button>Endre passord</button>
                </div>
            </form>
            <div class="change-password-other-options center">
                <div class="login-option-code">
                    <p><a href="/forgot-password.php">Glemt Passord?</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "inc/modules/footer.php" ?>

</body>

</html>
