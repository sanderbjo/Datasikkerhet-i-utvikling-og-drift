<header>
    <h1>Gruppe 4</h1>
    <nav>
        <ul>
            <li><a href="/">Forside</a></li>
            <li><a href="/documentation.php">Dokumentasjon</a></li>
            <?php

            if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
                echo "<li class'nav-right'><a href='/user-settings.php'>Instillinger</a></li>";
                echo "<li class'nav-right'>";
                require $_SERVER['DOCUMENT_ROOT'] . "../inc/logout-button.php";
                echo "</li>";
            }

            ?>
        </ul>
    </nav>
</header>
