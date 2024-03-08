<header>
    <h1>Gruppe 4</h1>
    <nav>
        <ul>
            <li><a href="/">Forside</a></li>
            <li><a href="/documentation.php">Dokumentasjon</a></li>
            <?php

            if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
                echo "<li class'nav-right'><a href='/user-settings.php'>Instillinger</a></li>";
                echo "<li class'nav-right'>"; require "logout-button.php"; echo "</li>";
            } else {
                echo "<li class'nav-right'><a href='/signup-student.php'>Registrer student</a></li>";
                echo "<li class'nav-right'><a href='/signup-lecturer.php'>Registrer foreleser</a></li>";
                echo "<li class'nav-right'><a href='/login.php'>Logg inn</a></li>";
            }

            ?>
        </ul>
    </nav>
</header>
