<header>
    <nav>
        <?php
        if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
            if ($_SESSION["role"] === 1)
                require_once "header-modules/nav-prof.php";
            else
                require_once "header-modules/nav-student.php";
        }
        ?>
    </nav>
    <div class="user-options">
        <?php
        if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true)
            require_once "header-modules/user-options-not-logged-in.php";
        else
            require_once "header-modules/user-options-logged-in.php";
        ?>
    </div>
</header>