<?php
session_start();

$emailCantBeNull = "<p class='error'>Blank epost</p>";
$invalidEmail = "<p class='error'>Ugyldig epost</p>";
# Success blir oppdatert med epost senere i koden
$success = "<p class='success'>Hvis det er en konto knyttet til 'epost' har en mail blitt sendt</p>";
$databaseError0 = "<p class='error'>Feil i database #0</p>";
$databaseError1 = "<p class='error'>Feil i database #1</p>";

$message = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"]))
        $message = $emailCantBeNull;
    else {
        $email = $_POST["email"];
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $message = $invalidEmail;
    }

    $success = "<p class='success'>Hvis det er en konto knyttet til " . $email . " har en mail blitt sendt</p>";

    if (empty($message)) {
        require "includes/db-connection.php";
        $stmt = $conn->prepare("SELECT id FROM bruker WHERE epost = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $resultId = -1;
            $stmt->bind_result($resultId);
            $stmt->fetch();

            $id = $resultId;
            try {
                $randomToken = bin2hex(random_bytes(10));
            } catch (\Random\RandomException $e) {
                echo $e;
                exit;
            }
            $newPassword = password_hash($randomToken, PASSWORD_DEFAULT);

            $stmtUpdatePassword = $conn->prepare("UPDATE bruker SET passord = ? WHERE id = ?");
            $stmtUpdatePassword->bind_param("si", $newPassword, $id);
            $stmtUpdatePassword->execute();
            if ($stmtUpdatePassword->affected_rows === 1) {
                $emailSubject = "Temporary Password";
                $emailMessage = "Dit midlertidige passord er: $randomToken, husk å endre passord etter du har logget deg inn!";
                mail($email, $emailSubject, $emailMessage);
                $message = $success;
                $message = $message . "<br>" . $randomToken; # TODO: Denne linjen må fjernes før vi leverer
            } elseif($stmtUpdatePassword->affected_rows === -1)
                $message = $databaseError0;
            else
                $message = $databaseError1;
        } else {
            $message = $success;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glemt passord</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "modules/header.php"; ?>

<main>
    <div class="module-wrapper">
        <div class="forgot-password-module">
            <h2 class="module-header">Glemt Passord</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="forgot-password-form">
                <?php if (!empty($message)) echo "<div class='center'>" . $message . "</div>" ?>
                <div class="forgot-password-form-email">
                    <label for="email">E-post:</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="form-submit">
                    <button type="submit">Klikk for å få et midlertidig passord </button>
                </div>
            </form>
        </div>
    </div>
</main>

</body>

</html>
