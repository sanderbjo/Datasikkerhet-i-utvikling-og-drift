<?php

if (!isset($_SESSION["csrf-logout"]))
    $_SESSION["csrf-logout"] = generateAuthToken();
?>

<form method="post" action="/logout.php">
    <input type="hidden" name="auth-token" value="<?= $_SESSION["csrf-logout"]; ?>">
    <button>Logg ut</button>
</form>
