<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            if ( isset($_SESSION['admin']) ) {
                
                if ( empty($_POST['images']) || empty($_POST['productTitle']) || empty($_POST['productCategory']) || empty($_POST['productPrice1']) || empty($_POST['productPrice2']) || empty($_POST['productDescription']) ) {
                    $popup = array("message" => "Nie wszystkie pola zostały wypełnione!", "color" => "red");
                    throw new Exception();
                }

            } else {
                header("Location: ../index");
            }
        }

        $connection->close();

    } catch ( Exception $e ) {
        echo "SRAKA!";
    }