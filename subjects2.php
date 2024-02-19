<?php
session_start();

if (!isset($_SESSION["anon"]) || $_SESSION["anon"] !== true) {
    header("Location: anon-login.php");
    exit;
}

$formNotFilled = "Alle feltene må være fylt ut";
$databaseError0 = "Feil i database #0";

$emnekode = $_GET["emnekode"] ?? "";
if (empty($emnekode)) {
    echo "Emnekode mangler.";
    exit;
}

require_once "includes/db-connection.php";

$stmt = $conn->prepare("SELECT emnekode, navn, bruker_id FROM emne WHERE emnekode = ?");
$stmt->bind_param("s", $emnekode);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 1) {
    $stmt->bind_result($resultEmnekode, $resultNavn, $resultBrukerId);
    $stmt->fetch();
} else {
    echo "Emne ikke funnet.";
    exit;
}

$stmt->close();

$stmt = $conn->prepare("SELECT navn FROM bruker WHERE id = ?");
$stmt->bind_param("i", $resultBrukerId);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($resultBrukerNavn);
$stmt->fetch();
$stmt->close();

// Hent bildet av foreleseren
$foreleserBilde = "uploads/$resultBrukerId.jpg";
if (!file_exists($foreleserBilde)) {
    $foreleserBilde = "uploads/$resultBrukerId.png";
}

// Hent alle meldinger for emnet, inkludert de som ikke har svar
$stmt = $conn->prepare("
    SELECT m.id, m.innhold, m.bruker_id, s.innhold AS svar_innhold, s.bruker_id AS svar_bruker_id
    FROM melding AS m
    LEFT JOIN svar AS s ON m.id = s.melding_id
    WHERE emne_emnekode = ?
");
$stmt->bind_param("s", $emnekode);
$stmt->execute();
$result = $stmt->get_result();

$meldinger = array();
while ($row = $result->fetch_assoc()) {
    $meldingId = $row['id'];
    $meldinger[$meldingId]['innhold'] = $row['innhold'];
    $meldinger[$meldingId]['bruker_id'] = $row['bruker_id'];
    $meldinger[$meldingId]['svar'][] = array(
        'innhold' => $row['svar_innhold'],
        'bruker_id' => $row['svar_bruker_id']
    );

    // Hent de anonyme kommentarene for denne meldingen
    $stmtKommentar = $conn->prepare("
        SELECT melding
        FROM anonyme_kommentarer
        WHERE melding_id = ?
    ");
    $stmtKommentar->bind_param("i", $meldingId);
    $stmtKommentar->execute();
    $resultKommentar = $stmtKommentar->get_result();

    $anonymeKommentarer = array();
    while ($rowKommentar = $resultKommentar->fetch_assoc()) {
        $anonymeKommentarer[] = $rowKommentar['melding'];
    }

    $meldinger[$meldingId]['anonyme_kommentarer'] = $anonymeKommentarer;

    $stmtKommentar->close();
}

$stmt->close();

// Håndter lagring av anonyme kommentarer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["melding"]) && isset($_POST["melding_id"])) {
        $kommentar = $_POST["melding"];
        $meldingId = $_POST["melding_id"];

        // Lagre kommentaren i databasen
        $stmtLagreKommentar = $conn->prepare("INSERT INTO anonyme_kommentarer (melding_id, melding) VALUES (?, ?)");
        $stmtLagreKommentar->bind_param("is", $meldingId, $kommentar);
        $stmtLagreKommentar->execute();
        $stmtLagreKommentar->close();

        // Omdiriger tilbake til samme side etter lagring
        header("Location: subjects2.php?emnekode=" . urlencode($emnekode));
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emneside</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "modules/header.php"; ?>

<main>
    <div class="module-wrapper">
        <div class="subject-module">
            <h2 class="module-header"><?php echo htmlspecialchars($resultEmnekode) . " - " . htmlspecialchars($resultNavn); ?></h2>
            <p>Foreleser: <?php echo htmlspecialchars($resultBrukerNavn); ?></p>
            <img src="<?php echo $foreleserBilde; ?>" alt="Bilde av foreleser" width="200">
            <div class="messages-wrapper">
                <?php foreach ($meldinger as $meldingId => $melding) : ?>
                    <div class="message">
                        <p>Melding: <?php echo htmlspecialchars($melding['innhold']); ?></p>
                        <div class="replies">
                            <?php foreach ($melding['svar'] as $svar) : ?>
                                <div class="reply">
                                    <p>Svar: <?php echo htmlspecialchars($svar['innhold']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Vis anonyme kommentarer -->
                        <div class="anonyme-kommentarer">
                            <?php foreach ($melding['anonyme_kommentarer'] as $kommentar) : ?>
                                <div class="kommentar">
                                    <p>Anonym kommentar: <?php echo htmlspecialchars($kommentar); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Legg til anonym kommentar skjema -->
                        <p>Emnekode: <?php echo htmlspecialchars($emnekode); ?></p>


                        <form action="subjects2.php?emnekode=<?php echo $emnekode; ?>" method="post">
                            <input type="hidden" name="melding_id" value="<?php echo $meldingId; ?>">
                            <textarea name="melding" rows="3" placeholder="Skriv din anonyme kommentar her"></textarea>
                            <button type="submit">Legg til kommentar</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

</body>

</html>
