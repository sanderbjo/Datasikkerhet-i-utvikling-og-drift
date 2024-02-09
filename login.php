<?php
require_once "includes/validate-not-logged-in.php";

$wrongEmailOrPassword = "Feil e-post eller passord";
$databaseError0 = "Feil i database #0";


$loginError = "";
$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["email"]) || empty($_POST["password"]))
        $loginError = $wrongEmailOrPassword;
    else {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $loginError = $wrongEmailOrPassword;
    }

    if (empty($loginError)) {
        require "includes/db-connection.php";
        $stmt = $conn->prepare("SELECT id, epost, passord, navn, rolle_id FROM bruker WHERE epost = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $resultId = $resultRoleId = -1;
            $resultEmail = $resultPassword = $resultName = $resultRole = "";
            $stmt->bind_result($resultId, $resultEmail, $resultPassword, $resultName, $resultRoleId);
            $stmt->fetch();
            if (password_verify($password, $resultPassword)) {
                $_SESSION["id"] = $resultId;
                $_SESSION["loggedIn"] = true;
                $_SESSION["email"] = $resultEmail;
                $_SESSION["name"] = $resultName;
                $_SESSION["role"] = $resultRoleId;
                if ($_SESSION["role"] === 1)
                    header("Location: /foreleser.php");
                else
                    header("Location: /student.php");
                exit;
            } else {
                $loginError = $wrongEmailOrPassword;
            }
        } elseif ($stmt->num_rows !== 0) {
            $loginError = $databaseError0;
        } else {
            $loginError = $wrongEmailOrPassword;
        }
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

<?php
require_once "modules/header.php";
?>

<main>
    <div class="module-wrapper">
        <div class="login-module">
            <h2 class="module-header">Logg inn</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login-form">
                <?php if (!empty($loginError)) echo "<div class='error center'><p>$loginError</p></div>" ?>
                <div class="login-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email);?>">
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

</body>

</html>