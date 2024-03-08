<?php

session_start();

?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasjon</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php require_once "inc/modules/header.php"; ?>

<main>
    <div class="wrapper">
        <article>
            <h2>Dokumentasjon</h2>
            <section>
                <h3>Gruppemedlemmer</h3>
                <ul class="square">
                    <li><span><span>Sander Bjørkevoll</span> <a href="mailto:sanderbj@hiof.no">sanderbj@hiof.no</a></span></li>
                    <li><span><span>Bjørnar Guttormsen</span> <a href="mailto:bjorngu@hiof.no">bjorngu@hiof.no</a></span></li>
                    <li><span><span>Sindre Bless Horn</span> <a href="mailto:sindrbh@hiof.no">sindrbh@hiof.no</a></span></li>
                    <li><span><span>Tobias Jensen</span> <a href="mailto:tobiasje@hiof.no">tobiasje@hiof.no</a></span></li>
                    <li><span><span>Marius Bjørnstad Johansen</span> <a href="mailto:mariubj@hiof.no">mariubj@hiof.no</a></span></li>
                </ul>
            </section>
            <section>
                <h3>Steg 1 - Hva som er gjort</h3>
                <p>
                    I steg 1 av prosjektet har vi opprettet en nettside som er bygd på PHP. Vi startet med å lage et
                    basic HTML-layout av sidene, og satte opp en enkel ER-modell i MySQL Workbench. Etter litt research
                    på både Youtube og Google satte vi opp Apache webserver og phpmyadmin for å drifte nettsiden vår.
                    Etter dette kunne vi begynne å implementere siden «live» og teste blant annet tilkobling til
                    database med både sending og henting av data. Databasen ble senere nødt til å videreutvikles litt,
                    og dette ble gjort via SQL i phpmyadmin. Vi har brukt minimalt med javascript og kun tatt i bruk
                    enkel CSS for utseendets skyld. Koden vi har skrevet har vært en blanding av egen research, ting
                    vi har funnet på nett, egen kode og ChatGPT.
                </p>
            </section>
            <section>
                <h3>Steg 2</h3>
                <p>
                    Tekst.
                </p>
            </section>
        </article>
    </div>
</main>

<?php require_once "inc/modules/footer.php"; ?>

</body>

</html>