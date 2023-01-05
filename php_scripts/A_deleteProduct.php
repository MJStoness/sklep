<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            echo $_POST['product_id'];

            $connection->close();
        }
    } catch ( Exception $e ) {
        $popup = array("message" => "Dodawanie nie powiodło się!", "color" => "red");
        echo json_encode($popup);
    }