<?php

    session_start();

    unset($_SESSION['loggedin_id']);
    unset($_SESSION['admin']); 
    header("Location: .");

?>
