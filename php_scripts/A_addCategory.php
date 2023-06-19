<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $query = "INSERT INTO category (category) VALUES ('".$_POST['name']."')";
            if ( !$response = $connection->query($query) ) {
                throw new Exception();
            } else {
                $popup = array("message" => "Dodano kategorię!", "color" => "green");
                echo json_encode($popup);
            }

            $connection->close();
        }
    } catch ( Exception $e ) {
        $popup = array("message" => "Dodawanie nie powiodło się!", "color" => "red");
        echo json_encode($popup);
    }