<?php

require_once "/inc/validate/session-validate.php";

notLoggedInOrRedirect();

define("WRONG_EMAIL_OR_PASSWORD",   "<p class'error'>Feil e-post eller passord</p");
define("DATABASE_ERROR",            "<p class'error'>Feil i database</p");
define("CSRF_ATTEMPT",              "<p class'error'>En feil har oppstått. Prøv igjen</p");

$loginError = "";
$email = $password = "";

if (!isset($_SESSION["csrf-login"]))
    $_SESSION["csrf-login"] = generateAuthToken();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (strcmp($_POST["auth-token"], $_SESSION["csrf-login"]) !== 0) {
        $loginError = CSRF_ATTEMPT;
    } else {
        $_SESSION["csrf-login"] = generateAuthToken();

        if (empty($_POST["email"]) || empty($_POST["password"]))
            $loginError = WRONG_EMAIL_OR_PASSWORD;
        else {
            $email = $_POST["email"];
            $password = $_POST["password"];
        
            $email = trim($email);
            if (validateEmail($email))
                $loginError = WRONG_EMAIL_OR_PASSWORD;
        }
    }

    if (empty($loginError)) {
        # TODO: Databasefil
        require "DATABASEFIL";
        require_once "/inc/db/queries/user-management.php";
        require_once "/inc/password/password.php";

        $queryResult = loginQuery($conn, $email);
        if (!empty($queryResult) && password_verify(pepperPassword($password), $resultPassword)) {
            $_SESSION["loggedIn"] = true;
            $_SESSION["id"] = $queryResult["id"];
            $_SESSION["email"] = $queryResult["email"];
            $_SESSION["name"] = $queryResult["name"];
            $_SESSION["role"] = $queryResult["role"];

            header("Location: /");
            exit;
        } else {
            $loginError = WRONG_EMAIL_OR_PASSWORD;
        }
    } else {
        $loginError = WRONG_EMAIL_OR_PASSWORD;
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

<?php require_once "/inc/modules/header.php"; ?>

<main>
    <div class="module-wrapper">
        <div class="login-module">
            <h2 class="module-header">Logg inn</h2>
            <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="login-form">
                <input type="hidden" name="auth-token" value="<?= $_SESSION["csrf-login"]; ?>">
                <?php if (!empty($loginError)) echo "<div class='center'>$loginError></div>"; ?>
                <div class="login-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email); ?>">
                </div>
                <div class="login-form-password">
                    <label for="password">Passord</label>
                    <input type="password" name="password" id="password">
                    <a href="/forgot-password.php">Glemt passord?</a>
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
                    <p><a href="/register-student.php">Opprett en studentkonto</a></p>
                    <p><a href="/register-lecturer.php">Opprett en foreleserkonto</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "/inc/modules/footer.php"; ?>

</body>

</html>