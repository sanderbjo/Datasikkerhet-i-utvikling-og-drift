<header>
    <nav>
        <ul>
            <li><a href="#">Forside</a></li>
        </ul>
    </nav>
    <div class="user-options">
        <?php
        if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] === false) {
            include "header-modules/user-options-not-logged-in.php";
        } else {
            include "header-modules/user-options-logged-in.php";
        }
        ?>
    </div>
</header>