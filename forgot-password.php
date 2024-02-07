<?php
require "includes/validate.php";
require "includes/db-connection.php";

$email = $emailError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"])) {
        $emailError = "E-post er påkrevd";
    } else {
        $email = $_POST["email"];

        // Sjekk om e-postadressen tilhører en foreleser
        $check_foreleser_query = "SELECT id FROM bruker WHERE epost = '$email' AND rolle_id = '1'";
        $check_foreleser_result = mysqli_query($conn, $check_foreleser_query);

        if ($check_foreleser_result && mysqli_num_rows($check_foreleser_result) > 0) {
            $user_info = mysqli_fetch_assoc($check_foreleser_result);
            $user_id = $user_info['id'];

            // Generer en unik token for tilbakestilling
            $reset_token = bin2hex(random_bytes(10));

            // Oppdater brukerens passord til den den tilfeldige koden
            $update_password_query = "UPDATE bruker SET passord = '$random_token' WHERE id = '$user_id'";
            $update_password_result = mysqli_query($conn, $update_password_query);

            if ($reset_insert_result) {
                // Send e-post med tilfeldig generert passord
                $subject = "Temporary Password";
                $message = "Dit midlertidige passord er: $random_token, husk å endre passord etter du har logget deg inn!";
                mail($email, $subject, $message);

                // Gi tilbakemelding til foreleseren
                $success_message = "En e-post med instruksjoner er sendt til $email.";
            } else {
                // Håndter feil ved lagring av tilbakestillingsforespørsel i databasen
                $error_message = "Feil ved behandling av forespørsel. Prøv igjen senere.";
            }
        } else {
            // E-postadressen tilhører ikke en foreleser
            $emailError = "E-postadressen tilhører ikke en foreleser i systemet.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glemt Passord (Foreleser)</title>
</head>

<body>

<?php include "header.php"; ?>

<main>
    <div class="module-wrapper">
        <h2>Glemt Passord (Foreleser)</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="forgot-password-form">
            <div class="forgot-password-form-email">
                <label for="email">E-post:</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>">
                <?php if (!empty($emailError)) echo "<p class='error'>$emailError</p>" ?>
            </div>
            <div class="form-submit">
                <button type="submit">Klikk for å få midlertidig passord </button>
            </div>
        </form>
        <?php
        if (isset($success_message)) {
            echo "<p class='success'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p class='error'>$error_message</p>";
        }
        ?>
    </div>
</main>

</body>

</html>
