<?php
require "includes/db-connection.php"; // Koble til databasen

$email = $emailError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"])) {
        $emailError = "E-post er påkrevd";
    } else {
        $email = $_POST["email"];

        // Sjekk om e-postadressen tilhører en foreleser
        $check_foreleser_query = "SELECT id FROM brukere WHERE email = '$email' AND rolle = 'foreleser'";
        $check_foreleser_result = mysqli_query($conn, $check_foreleser_query);

        if ($check_foreleser_result && mysqli_num_rows($check_foreleser_result) > 0) {
            $user_info = mysqli_fetch_assoc($check_foreleser_result);
            $user_id = $user_info['id'];

            // Generer en unik token for tilbakestilling
            $reset_token = bin2hex(random_bytes(32));

            // Lagre token i databasen sammen med foreleserens e-post og tidspunkt for forespørsel
            $reset_insert_query = "INSERT INTO foreleser_reset_tokens (user_id, token, opprettet_tid) VALUES ('$user_id', '$reset_token', NOW())";
            $reset_insert_result = mysqli_query($conn, $reset_insert_query);

            if ($reset_insert_result) {
                // Send e-post med tilbakestillingsslenke
                $reset_link = "http://158.39.188.206//reset_password.php?token=$reset_token";
                $subject = "Tilbakestill Passord";
                $message = "Klikk på følgende lenke for å tilbakestille passordet ditt: $reset_link";
                mail($email, $subject, $message);

                // Gi tilbakemelding til foreleseren
                $success_message = "En e-post med instruksjoner for tilbakestilling er sendt til $email.";
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
    <!-- Legg til eventuelle CSS-stiler her -->
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
                <button type="submit">Send Tilbakestillingslenke</button>
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
