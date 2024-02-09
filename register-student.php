<?php
require_once "includes/validate-not-logged-in.php";

$formNotFilledOut = "<p class='error'>Fyll ut alle feltene</p>";
$invalidEmail = "<p class='error'>Ugyldig epost</p>";
$success = "<p class='success'>Studentregistrering vellykket</p>";
$error = "<p class='error'>En feil oppstod under registreringen</p>";

$message = "";

$name = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require "includes/db-connection.php";

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $message = $formNotFilledOut;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = $invalidEmail;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES (?, ?, ?, 2)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $message = $success;
        } else {
            $message = $error . "<br>" . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrer student</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php
require_once "modules/header.php"
?>

<main>
    <div class="module-wrapper">
        <div class="register-student-module">
            <h2 class="module-header">Studentregistrering</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="register-student-form">
                <?php if (!empty($message)) echo "<div class='center'>" . $message . "</div>" ?>
                <div class="register-form-name">
                    <label for="name">Navn</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name) ?>">
                </div>
                <div class="register-form-email">
                    <label for="email">E-post</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email) ?>">
                </div>
                <div class="register-form-password">
                    <label for="password">Passord</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="form-submit">
                    <button>Registrer</button>
                </div>
            </form>
            <div class="register-student-other-options center">
                <div class="register-student-option-other-signup center">
                    <p><a href="/register-lecturer.php">Opprett en foreleserkonto</a></p>
                </div>
                <div class="register-student-option-login">
                    <p>Har du en konto? <a href="/login.php">Logg inn</a></p>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>
