<header>
    <div>
        <h1>Gruppe 4</h1>
        <nav>
            <ul>
                <?php
                if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
                    if ($_SESSION["role"] === 1)
                        require_once "header-modules/nav-prof.php";
                    else
                        require_once "header-modules/nav-student.php";
                }
                ?>
                <li><a href="/documentation.php">Dokumentasjon</a></li>
            </ul>
        </nav>
    </div>
    <div class="user-options">
        <?php
        if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true)
            require_once "header-modules/user-options-not-logged-in.php";
        else
            require_once "header-modules/user-options-logged-in.php";
        ?>
    </div>
</header>