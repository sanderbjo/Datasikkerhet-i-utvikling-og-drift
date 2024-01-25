<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bytt passord</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include "header.html" ?>

<?php
$fullName = "NAME";
$minimumPasswordLength = 6;

$wrongPassword = "Passordet er feil";
$newPasswordCantBeNull = "Passordet kan ikke være blankt";
$newPasswordConfirmationFail = "Passordet matcher ikke";

$oldPasswordError = $newPasswordError = $newPasswordConfirmationError = "";
$oldPassword = $newPassword = $newPasswordConfirmation = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["old-password"])) {
        $oldPasswordError = $wrongPassword;
    } else {
        $oldPassword = $_POST["old-password"];
    }
    if (empty($_POST["new-password"])) {
        $newPasswordError = $newPasswordCantBeNull;
    } elseif (empty($_POST["new-password-confirmation"])) {
        $newPasswordConfirmationError = $newPasswordConfirmationFail;
    } else {
        $newPassword = $_POST["new-password"];
        $newPasswordConfirmation = $_POST["new-password-confirmation"];
        if (strlen($newPassword) < $minimumPasswordLength) {
            $newPasswordError = "Passordet er for kort. Minst $minimumPasswordLength tegn";
        } elseif (strcmp($newPassword, $newPasswordConfirmation) != 0) {
            $newPasswordConfirmationError = $newPasswordConfirmationFail;
        }
    }

}

# TODO: Hent session og så navn. Hvis ingen session finnes, redirect
# TODO: Sjekk at passord matcher. og at nytt passord matcher med bekreftene passord
# TODO: Deretter oppdater passord og gi tilbakemelding til bruker

?>

<main>
    <div class="module-wrapper">
        <div class="change-password-module">
            <h2 class="module-header">Bytt passord for <?php echo $fullName ?></h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="change-password-form">
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