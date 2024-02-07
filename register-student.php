<?php
// Definer databaseforbindelsesdetaljer
require "includes/db-connection.php"; 

// Håndter registreringsskjemaet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Enkel validering
    if (empty($name) || empty($email) || empty($password)) {
        echo "Fyll ut alle feltene.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
    } else {
        // Legg til student i databasen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlInsertStudent = "INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES ('$name', '$email', '$hashedPassword', 2)";

        if ($conn->query($sqlInsertStudent) === TRUE) {
            echo "Studentregistrering vellykket!";
        } else {
            echo "Feil under registrering av student: " . $conn->error;
        }
    }
}

// Lukk databaseforbindelsen
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studentregistrering</title>
</head>
<body>

<?php
require_once "modules/header.php"
?>
    <h2>Studentregistrering</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Navn: <input type="text" name="name"><br>
        E-post: <input type="text" name="email"><br>
        Passord: <input type="password" name="password"><br>
        <input type="submit" value="Registrer">
    </form>

</body>
</html>
