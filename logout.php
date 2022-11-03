<?php

    session_start();

    unset($_SESSION['loggedin_id']);
    header("Location: index.php");

?>
