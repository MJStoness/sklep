<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $query = "DELETE FROM product WHERE product_id IN (".implode(",", $_POST['product_id']).")";
            if ( !$response = $connection->query($query) ) {
                throw new Exception();
            } else {
                $popup = array("message" => "Usuwanięto ".count($_POST['product_id'])." produktów!", "color" => "green");
                echo json_encode($popup);
            }

            $connection->close();
        }
    } catch ( Exception $e ) {
        $popup = array("message" => "Usuwanie nie powiodło się!", "color" => "red");
        echo json_encode($popup);
    }