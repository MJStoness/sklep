<?php
    session_start();

    require_once "../config.php";


    $connection = new mysqli($servername,$username,$passwd,$dbname);

    if ( !$connection->connect_errno ) {
        $entryId = $_POST['cart_entry_id'];
        $quantity = $_POST['quantity'];

        $query = "UPDATE cart_entry SET quantity=$quantity WHERE cart_entry_id=$entryId";
        if ( !$connection->query($query) ) {
            echo "ERROR";
        }
        $connection->close();
    }
    

