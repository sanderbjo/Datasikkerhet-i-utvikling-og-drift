<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sjekk om melding_id og begrunnelse er sendt med POST
    if (isset($_POST["melding_id"]) && isset($_POST["begrunnelse"])) {
        $meldingId = $_POST["melding_id"];
        $begrunnelse = $_POST["begrunnelse"];

        // Validere data om nødvendig

        // Legg til rapportering i databasen
        require_once "includes/db-connection.php";
        $stmt = $conn->prepare("INSERT INTO rapporterte_meldinger (melding_id, begrunnelse) VALUES (?, ?)");
        $stmt->bind_param("is", $meldingId, $begrunnelse);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        // Tilbakemelding til brukeren om at rapporteringen var vellykket
        echo "Meldingen er rapportert. Takk for at du bidrar til et tryggere miljø.";
    } else {
        // Tilbakemelding til brukeren om manglende data
        echo "Noe gikk galt. Vennligst prøv igjen.";
    }
} else {
    // Tilbakemelding til brukeren om feil request metode
    echo "Ugyldig forespørselsmetode.";
}
?>
