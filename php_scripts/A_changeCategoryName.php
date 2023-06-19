<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $query = "UPDATE category SET category='".$_POST['name']."' WHERE category_id='".$_POST['id']."' ";
            if ( !$response = $connection->query($query) ) {
                throw new Exception();
            } else {
                $popup = array("message" => "Zmieniono nazwę!", "color" => "green");
                echo json_encode($popup);
            }

            $connection->close();
        }
    } catch ( Exception $e ) {
        $popup = array("message" => "Zmiana nie powiodło się!", "color" => "red");
        echo json_encode($popup);
    }