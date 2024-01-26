<?php
session_start();

$wrongEmailPassword = "Feil e-post eller passord";

$loginError = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"])) {
        $loginError = $wrongEmailPassword;
    } else {
        $email = $_POST["email"];
        $email = trim($email);
        $email = htmlspecialchars($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loginError = $wrongEmailPassword;
        } else {
            if (empty($_POST["password"])) {
                $loginError = $wrongEmailPassword;
            }
        }
    }

    if (strcmp($loginError, "") === 0) {
        # TODO: Hente data fra database og sammenlign med input
        $userID = "HER SKAL BRUKERID BLI HENTET FRA DATABASE";

        # TODO: Hvis epost/passord er riktig, lag en session og redirect
        $_SESSION["user"] = $userID;
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

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
                    <p><a href="/anon-login.php">Anonym innlogging med kode</a></p>
                </div>
                <div class="login-option-signup center">
                    <p><a href="/signup">Opprett en konto</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>

</html>