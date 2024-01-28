<?php
session_start();
?>
<header>
    <nav>
        <ul>
            <li><a href="#">Forside</a></li>
        </ul>
    </nav>
    <div class="user-options">
        <?php
        if ($_SESSION["loggedIn"] === true && $_SESSION["anon"] === false) {
            include "header-modules/user-options-logged-in.php";
        } else {
            include "header-modules/user-options-not-logged-in.php";
        }
        ?>
    </div>
</header>