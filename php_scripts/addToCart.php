<?php
    session_start();

    require_once "../config.php";

    $connection = new mysqli($servername,$username,$passwd,$dbname);

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    try {
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            if ( !isset($_SESSION['loggedin_id']) ) {
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

                $query = "SELECT * FROM cart_entry WHERE cart_id=".$cartId." AND product_id=".$_POST['product_id'];
                if ( $response = $connection->query($query) ) {
                    if ($response->fetch_row()) {
                        $popup = array("message" => "Produkt jest już w koszyku!", "color" => "blue", "success" => "no");
                        echo json_encode($popup);
                    } else {
                        $query = "INSERT INTO cart_entry (`cart_id`, `product_id`, `quantity`) VALUES (".$cartId.", ".$_POST['product_id'].", 1)";
                        if ( $connection->query($query) ) {
                            $popup = array("message" => "Dodano do koszyka!", "color" => "green", "success" => "yes");
                            echo json_encode($popup);
                        } else {
                            throw new Exception();
                        }
                    }
                } else {
                    throw new Exception();
                }

            } else {

                $query = "SELECT cart_id FROM cart WHERE `user_id`=".$_SESSION['loggedin_id'];
                if ( $response = $connection->query($query) ) {
                    $cartId = $response->fetch_assoc()['cart_id'];
                }

                $query = "SELECT * FROM cart_entry WHERE cart_id=".$cartId." AND product_id=".$_POST['product_id'];
                if ( $response = $connection->query($query) ) {
                    if ($response->fetch_row()) {
                        $popup = array("message" => "Produkt jest już w koszyku!", "color" => "blue", "success" => "no");
                        echo json_encode($popup);
                    } else {
                        $query = "INSERT INTO cart_entry (`cart_id`, `product_id`, `quantity`) VALUES (".$cartId.", ".$_POST['product_id'].", 1)";
                        if ( $connection->query($query) ) {
                            $popup = array("message" => "Dodano do koszyka!", "color" => "green", "success" => "yes");
                            echo json_encode($popup);
                        } else {
                            throw new Exception();
                        }
                    }
                } else {
                    throw new Exception();
                }
            }

            $connection->close();
        }
    } catch ( Exception $e ) {
        $popup = array("message" => "Dodawanie nie powiodło się!", "color" => "red", "success" => "no");
        echo json_encode($popup);
    }