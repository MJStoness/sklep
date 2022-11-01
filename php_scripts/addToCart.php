<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    if ( !$connection->connect_errno ) {
        if ( !isset($_SESSION['loggedIn']) ) {
            //=========================================================== CART CREATION
            if ( !isset($_SESSION['guest']) ) { 
                //================================== GUEST CREATION
                $query = "SELECT MAX(guest) FROM cart";

                if ( $response = @$connection->query($query) ) {
                    $lastGuest = $response->fetch_assoc()['MAX(guest)'];
                }

                $_SESSION['guest'] = intval($lastGuest)+1;

                //========================================
                $query = "INSERT INTO cart (`user_id`, `guest`, `create_date`) VALUES (NULL, '".$_SESSION['guest']."', current_timestamp())";

                if ( !$connection->query($query) ) {
                    echo "ERROR";
                }
            }
            //=====================================================================

            $query = "SELECT cart_id FROM cart WHERE guest=".$_SESSION['guest'];
            if ( $response = $connection->query($query) ) {
                $cartId = $response->fetch_assoc()['cart_id'];
            }

            $query = "INSERT INTO cart_entry (`cart_id`, `product_id`, `quantity`) VALUES (".$cartId.", ".$_POST['product_id'].", 1)";
            $connection->query($query);

        }

        $connection->close();
    }