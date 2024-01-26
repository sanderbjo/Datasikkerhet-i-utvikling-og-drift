<?php
session_start();

$wrongPin = "Ugyldig kode";

$loginError = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["pin"])) {
        $loginError = $wrongPin;
    } else {
        $pin = $_POST["pin"];
        if (strlen($pin) !== 4) {
            $loginError = $wrongPin;
        }
    }

    if (strcmp($loginError, "") === 0) {
        # TODO: Hvis kode er riktig, lag en set user og subject og redirect
        # Er ikke helt sikker på implementeringen men her settes 'user' til 'anon' og
        # 'subject' til 'emneID'. Så må vi gjøre en validering på alle sider som
        # passer på at en anon-bruker kun får tilgang til emne som er satt i 'subject'
        $_SESSION["user"] = "anon";
        $_SESSION["subject"] = 0; # TODO: emnekode
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
    <title>Anonym innlogging med kode</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<main>
    <div class="module-wrapper">
        <div class="anon-login-module">
            <h2 class="module-header">Logg inn anonymt</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login-form">
                <?php if (!empty($loginError)) echo "<div class='error center'><p>$loginError</p></div>" ?>
                <div class="login-form-email">
                    <label for="pin">Kode (Fire sifre)</label>
                    <input type="text" name="pin" id="pin" inputmode="numeric" minlength="4" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="form-submit">
                    <button>Logg inn</button>
                </div>
            </form>
            <div class="login-other-options center">
                <div class="login-option-code">
                    <p><a href="/login.php">Logg inn med konto</a></p>
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