<?php
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

<main>
    <div class="wrapper">
        <article>
            <h2>Dokumentasjon-API</h2>
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
                    Det er lagd 4 endepunkter både ved bruk av GET og POST metode. siden vi ikke har noen app har vi satt det opp sånn at get request skal komme opp på siden når man 
                    er på endepunktet som json format, i tilleg til at man kan bruke postman til å kjøre endepunktene. POST metodene har vi lagd sånn at det skal være mulig å kjøre de ved bruk av postman om man sender endepunktet og legger til 
                    verdiene i body.
                </p>
                <ul>
                    <li>GET "http://158.39.188.206/api/emne.php"</li>
                    <li>GET "http://158.39.188.206/api/emne.php?emnekode={emnekode}"</li>
                    <li>POST "http://158.39.188.206/api/login.php"</li>
                    <li>POST "http://158.39.188.206/api/signup.php"</li>
                </ul>
                <p>
                    De 2 GET requestene vil hente data fra databasen. Den første vil være en tenkt samleside for alle emner og henter da alle emnene.
                    Den andre vil være inne på ett emne og vil da hente inn alle meldingene som tilhører det emne, derfor må man sende med emnekoden som parameter.
                    Også har vi 2 POST metoder som vil være å logge inn og det å registrere en ny bruker.
                    Den første vil ta utgangspunkt i at du har sendt med epost og passord også vil det sammenligne om det er riktig. Her vil det ikke komme noe json format men om man bruker postman vil man kunne se om det er riktig og får beskjed om at man er logget inn ellers får man beskjed om at det er feil passord
                    Den andre er å registre ny bruker, dette vil også trenge at man sender inn de feltene som er nødvendig(name, email, password) også vil den si ifra om registreringen er velykket
                </p>
            </section>
        </article>
    </div>
</main>

</body>

</html>