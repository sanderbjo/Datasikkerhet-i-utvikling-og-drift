<?php
require "includes/validate.php";

$name = $_SESSION["name"];
$minimumPasswordLength = 1;

$wrongPassword = "Passordet er feil";
$passwordCantBeNull = "Passordet kan ikke være blankt";
$passwordConfirmationFail = "Passordet matcher ikke";
$passwordTooShort = "Passordet er for kort. Minst $minimumPasswordLength tegn";

$passwordChangeSuccess = "Passordet har blitt oppdatert";

$oldPasswordError = $newPasswordError = $newPasswordConfirmationError = "";
$oldPassword = $newPassword = $newPasswordConfirmation = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["old-password"])) {
        $oldPasswordError = $wrongPassword;
    } else {
        $oldPassword = $_POST["old-password"];
    }
    if (empty($_POST["new-password"])) {
        $newPasswordError = $passwordCantBeNull;
    } elseif (empty($_POST["new-password-confirmation"])) {
        $newPasswordConfirmationError = $passwordConfirmationFail;
    } else {
        $newPassword = $_POST["new-password"];
        $newPasswordConfirmation = $_POST["new-password-confirmation"];
        if (strlen($newPassword) < $minimumPasswordLength) {
            $newPasswordError = $passwordTooShort;
        } elseif (strcmp($newPassword, $newPasswordConfirmation) != 0) {
            $newPasswordConfirmationError = $passwordConfirmationFail;
        }
    }
}
if (empty($oldPasswordError) && empty($newPasswordError) && empty($newPasswordConfirmationError)) {
    require "includes/db-connection.php";
}
# TODO: Sjekk at gammelt passord matcher
# TODO: Deretter oppdater passord og gi tilbakemelding til bruker
$message = $passwordChangeSuccess
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

<?php include "modules/header.php" ?>

<main>
    <div class="module-wrapper">
        <div class="change-password-module">
            <h2 class="module-header">Bytt passord for <?php echo $name ?></h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="change-password-form">
                <?php if (!empty($message)) echo "<div class='success center'><p>$message</p></div>" ?>
                <div class="change-password-form-old-password">
                    <label for="old-password">Gammelt passord</label>
                    <input type="password" name="old-password" id="old-password">
                    <?php if (!empty($oldPasswordError)) echo "<p class='error'>$oldPasswordError</p>" ?>
                </div>
                <div class="change-password-form-new-password">
                    <label for="new-password">Nytt passord</label>
                    <input type="password" name="new-password" id="new-password">
                    <?php if (!empty($newPasswordError)) echo "<p class='error'>$newPasswordError</p>" ?>
                </div>
                <div class="change-password-form-new-password">
                    <label for="new-password-confirmation">Bekreft nytt passord</label>
                    <input type="password" name="new-password-confirmation" id="new-password-confirmation">
                    <?php if (!empty($newPasswordConfirmationError)) echo "<p class='error'>$newPasswordConfirmationError</p>" ?>
                </div>
                <div class="form-submit">
                    <button>Endre passord</button>
                </div>
            </form>
            <div class="change-password-other-options center">
                <div class="login-option-code">
                    <p><a href="/forgot-password">Glemt Passord?</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>

</html>