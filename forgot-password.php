<?php
session_start();
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
require_once 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$emailCantBeNull = "<p class='error'>Blank epost</p>";
$invalidEmail = "<p class='error'>Ugyldig epost</p>";
$success = "<p class='success'>Hvis det er en konto knyttet til 'epost' har en mail blitt sendt</p>";
$databaseError0 = "<p class='error'>Feil i database #0</p>";
$databaseError1 = "<p class='error'>Feil i database #1</p>";

$msg = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"])) {
        $msg = $emailCantBeNull;
    } else {
        $email = $_POST["email"];
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = $invalidEmail;
        }
    }

    $success = "<p class='success'>Hvis det er en konto knyttet til " . $email . " har en mail blitt sendt</p>";

    if (empty($msg)) {
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
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP(); // Send using SMTP
                    $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                    $mail->SMTPAuth = true;
                    $mail->Username = 'mail';
                    $mail->Password = 'password';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;


                    //Recipients
                    $mail->setFrom('mail', 'Password reset');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Temporary Password';
                    $mail->Body = "Dit midlertidige passord er: $randomToken, husk å endre passord etter du har logget deg inn!";

                    $mail->send();
                    $msg = $success;
                } catch (Exception $e) {
                    $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } elseif ($stmtUpdatePassword->affected_rows === -1) {
                $msg = $databaseError0;
            } else {
                $msg = $databaseError1;
            }
        } else {
            $msg = $success;
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
                <?php if (!empty($msg)) echo "<div class='center'>" . $msg . "</div>"; ?>
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
