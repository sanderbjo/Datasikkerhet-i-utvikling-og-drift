<?php
// Definer databaseforbindelsesdetaljer
require "includes/db-connection.php"; 


// Sjekk tilkoblingsstatus
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    } elseif (strlen($password) < 6) {
        echo "Passordet må være minst 6 tegn langt.";
    } else {
        // Legg til brukeren i databasen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            echo "Registrering vellykket!";
        } else {
            echo "Feil under registrering: " . $conn->error;
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
    <title>Registreringsskjema</title>
</head>
<body>

    <h2>Registreringsskjema</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Navn: <input type="text" name="name"><br>
        E-post: <input type="text" name="email"><br>
        Passord: <input type="password" name="password"><br>
        <input type="submit" value="Registrer">
    </form>

</body>
</html>
