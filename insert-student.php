<?php
require "includes/db-connection.php";

// Håndter skjemaet når det er sendt
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Verdien av den valgte rollen

    // Enkel validering
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        echo "Fyll ut alle feltene.";
    } else {
        // Sett inn data i bruker-tabellen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sqlInsertUser = "INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES ('$name', '$email', '$hashedPassword', $role)";

        if ($conn->query($sqlInsertUser) === TRUE) {
            echo "Ny bruker er lagt til i databasen.";
        } else {
            echo "Feil under innsetting i databasen: " . $conn->error;
        }
    }
}

// Lukk tilkoblingen
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legg til bruker</title>
</head>
<body>

    <h2>Legg til bruker</h2>

    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Navn: <input type="text" name="name"><br>
        E-post: <input type="text" name="email"><br>
        Passord: <input type="password" name="password"><br>
        Rolle:
        <select name="role">
            <option value="1">Foreleser</option>
            <option value="2">Student</option>
        </select><br>
        <input type="submit" value="Legg til bruker">
    </form>

</body>
</html>
