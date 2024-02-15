<?php
require_once "includes/validate-not-logged-in.php";

$formNotFilled = "Alle feltene må være fylt ut";
$wrongPin = "Ugyldig kode";
$databaseError0 = "Feil i database #0";

$loginError = "";

$subject = $pin = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    if (empty($_POST["subject"]))
        $loginError = $formNotFilled;
    else {
        $subject = $_POST["subject"];
        if (empty($_POST["pin"]))
            $loginError = $formNotFilled;
        else {
            $pin = $_POST["pin"];
            if (strlen($pin) !== 4 || !is_numeric($pin))
                $loginError = $wrongPin;
        }
    }

    if (empty($loginError)) {
        require "includes/db-connection.php";

        # TODO: Emne-pin burde være en string i database. Noen burde endre det til varchar(4)
        $stmt = $conn->prepare("SELECT emnekode FROM emne WHERE emnekode = ? AND pin = ?"); 
        $stmt->bind_param("ss", $subject, $pin);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $resultSubjectCode = "";
            $stmt->bind_result($resultSubjectCode);
            $stmt->fetch();
            $_SESSION["anon"] = true;
            $_SESSION["anon-subject"] = $resultSubjectCode;
            header("Location: emneside"); #TODO: Hvor skal man sendt til?
            exit;
        } elseif ($stmt->num_rows !== 0)
            $loginError = $databaseError0;
        else
            $loginError = $wrongPin;
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

<?php
require_once "modules/header.php";
?>

<main>
    <div class="module-wrapper">
        <div class="anon-login-module">
            <h2 class="module-header">Logg inn anonymt</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="login-form">
                <?php if (!empty($loginError)) echo "<div class='center'><p class='error'>$loginError</p></div>" ?>
                <div class="login-form-subject">
                    <label for="subject">Emne</label>
                    <select name="subject" id="subject" autocomplete required>
                        <option value="">--Emne--</option>
                        <?php
                        require "includes/db-connection.php";
                        $query = "SELECT emnekode FROM emne";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0)
                            while ($row = $result->fetch_assoc()) {
                                $selected = (!empty($subject) && $subject === $row["emnekode"]) ? " selected" : "";
                                echo "<option value=\"" . htmlspecialchars($row["emnekode"]) . "\"$selected>" . htmlspecialchars($row["emnekode"]) . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="login-form-code">
                    <label for="pin">Kode (Fire sifre)</label>
                    <input type="text" name="pin" id="pin" inputmode="numeric" minlength="4" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div class="form-submit">
                    <button>Logg inn</button>
                </div>
            </form>
            <div class="login-other-options center">
                <div class="login-option-account">
                    <p><a href="/login.php">Logg inn med konto</a></p>
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