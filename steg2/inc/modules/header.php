<header>
    <div>
        <h1>Gruppe 4</h1>
    </div>
    <nav>
        <button id="navbar-toggle">
            <img src="/img/navbar-toggle.svg" alt="navbar toggle">
        </button>
        <script src="/js/navbar-toggle.js" defer></script>
        <ul id="main-navbar">
            <li><a href="/">Forside</a></li>
            <li><a href="/documentation.php">Dokumentasjon</a></li>
            <?php

            if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
                echo "<li class='nav-right'><a href='/user-settings.php'>Instillinger</a></li>";
                echo "<li class=''>"; require __DIR__ . "/logout-button.php"; echo "</li>";
            } else {
                echo "<li class='nav-right'><a href='/signup-student.php'>Registrer student</a></li>";
                echo "<li class=''><a href='/signup-lecturer.php'>Registrer foreleser</a></li>";
                echo "<li class=''><a href='/login.php'>Logg inn</a></li>";
            }

            ?>
        </ul>
    </nav>
</header>
