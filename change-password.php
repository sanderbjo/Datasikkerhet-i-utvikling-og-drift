<?php
require "includes/validate.php";

$name = htmlspecialchars($_SESSION["name"]);

$minimumPasswordLength = 1;

$formNotFilled = "<p class='error'>Alle feltene må være fylt ut</p>";
$passwordTooShort = "<p class='error'>Nytt passord er for kort. Minst $minimumPasswordLength tegn</p>";
$passwordConfirmationFail = "<p class='error'>Nytt Passord matcher ikke med bekreftende passord</p>";
$wrongPassword = "<p class='error'>Gammelt passord er feil</p>";
$databaseError0 = "<p class='error'>Feil i database #0</p>";
$databaseError1 = "<p class='error'>Feil i database #1</p>";

$passwordChangeSuccess = "<p class='success'>Passordet har blitt oppdatert</p>";

$message = "";

$oldPassword = $newPassword = $newPasswordConfirmation = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["old-password"]) ||
        empty($_POST["new-password"]) ||
        empty($_POST["new-password-confirmation"])
    )
        $message = $formNotFilled;
    else {
        $oldPassword = $_POST["old-password"];
        $newPassword = $_POST["new-password"];
        $newPasswordConfirmation = $_POST["new-password-confirmation"];

        if (strlen($newPassword) < $minimumPasswordLength)
            $message = $passwordTooShort;
        elseif (strcmp($newPassword, $newPasswordConfirmation) !== 0)
            $message = $passwordConfirmationFail;
    }
    if (empty($message)) {
        require "includes/db-connection.php";
        $id = $_SESSION["id"];
        $stmt = $conn->prepare("SELECT passord FROM bruker WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $resultPassword ="";
            $stmt->bind_result($resultPassword);
            $stmt->fetch();
            if (password_verify($oldPassword, $resultPassword)) {
                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $stmtUpdatePassword = $conn->prepare("UPDATE bruker SET passord = ? WHERE id = ?");
                $stmtUpdatePassword->bind_param("si", $newPassword, $id);
                $stmtUpdatePassword->execute();
                if ($stmtUpdatePassword->affected_rows === 1)
                    $message = $passwordChangeSuccess;
                elseif($stmtUpdatePassword->affected_rows === -1)
                    $message = $databaseError1;
                else
                    $message = $databaseError0;

            } else {
                $message = $wrongPassword;
            }
        } elseif($stmt->num_rows === -1) {
            $message = $databaseError1;
        } else {
            $message = $databaseError0;
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

<?php require_once "modules/header.php" ?>

<main>
    <div class="module-wrapper">
        <div class="change-password-module">
            <h2 class="module-header">Bytt passord for <?php echo $name ?></h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="change-password-form">
                <?php if (!empty($message)) echo "<div class='center'>" . $message . "</div>" ?>
                <div class="change-password-form-old-password">
                    <label for="old-password">Gammelt passord</label>
                    <input type="password" name="old-password" id="old-password">
                </div>
                <div class="change-password-form-new-password">
                    <label for="new-password">Nytt passord</label>
                    <input type="password" name="new-password" id="new-password">
                </div>
                <div class="change-password-form-new-password">
                    <label for="new-password-confirmation">Bekreft nytt passord</label>
                    <input type="password" name="new-password-confirmation" id="new-password-confirmation">
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

</body>

</html>