<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php
$wrongEmailPassword = "Feil e-post eller passord";

$loginError = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $loginError = $wrongEmailPassword;
    } else {
        $email = $_POST["email"];
        $email = trim($email);
        $email = htmlspecialchars($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = $wrongEmailPassword;
        }
    }

    if (empty($_POST["password"])) {
        $passwordError = $wrongEmailPassword;
    }
}

# TODO: Hente data fra database og sammenlign med input
# TODO: Hvis epost/passord er riktig, lag en session og redirect

?>

<main>
    <div class="module-wrapper">
        <div class="login-module">
            <h2 class="module-header">Logg inn</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login-form">
                <?php if (!empty($loginError)) echo "<div class='error center'><p>$loginError</p></div>" ?>
                <div class="login-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" value="<?php echo $email;?>">
                </div>
                <div class="login-form-password">
                    <label for="password">Passord</label>
                    <input type="password" name="password" id="password">
                    <a href="/forgot-password">Glemt passord?</a>
                </div>
                <div class="form-submit">
                    <button>Logg inn</button>
                </div>
            </form>
            <div class="login-other-options center">
                <div class="login-option-code">
                    <p><a href="/login-code">Anonym innlogging med kode</a></p>
                </div>
                <div class="login-option-signup center">
                    <p>Ny? <a href="/signup">Opprett en konto</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>

</html>