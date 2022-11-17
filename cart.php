<?php
    session_start();

    require_once "config.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);
        
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $cartEntries = array();
            $images = array();

            if ( isset($_SESSION['loggedin_id']) ) {

                $query = "SELECT cart_id FROM cart WHERE `user_id`=".$_SESSION['loggedin_id'];
                if ( $response = $connection->query($query) ) {
                    $cartId = $response->fetch_assoc()['cart_id'];
                } else {
                    throw new Exception();
                }

            }
            else if ( !isset($_SESSION['loggedin_id']) && isset($_SESSION['guest']) ) {

                $query = "SELECT cart_id FROM cart WHERE guest=".$_SESSION['guest'];
                if ( $response = $connection->query($query) ) {
                    $cartId = $response->fetch_assoc()['cart_id'];
                } else {
                    throw new Exception();
                }
                
            }
            else {
                $cartId = null;
            }

            if ( $cartId != null ) {
                $query = "SELECT cart_entry_id,cart_id,product.product_id,quantity,price,`name` FROM `cart_entry` JOIN product on ( cart_entry.product_id = product.product_id ) WHERE cart_id=".$cartId;
                    if ( $response = $connection->query($query) ) {
                        fetchAllToArray($cartEntries, $response);
                        $response->free();
                    } else {
                        throw new Exception();
                    }

                    //================================================ RETRIEVING PHOTOS
                    if ( count($cartEntries) ) {
                        $query = "SELECT * FROM image GROUP BY product_id HAVING product_id IN(".colToString($cartEntries, 'product_id', ', ').")";
                        if ( $response = $connection->query($query) ) {
                            fetchAllToArray($images, $response);
                            $response->free();
                        } else {
                            throw new Exception();
                        }
                    }
                    //================================================

                    unionArraysByCommonIndex($cartEntries, $images, 'img_path', 'product_id', 'path');

                if ( isset($_POST['delete_id']) ) {
                    $query = "DELETE FROM `cart_entry` WHERE `cart_entry_id`=".$_POST['delete_id'];
                    if ( !$connection->query($query) ) {
                        throw new Exception();
                    }
                    header("Refresh:0");
                }
            }

            $connection->close();
        }
    } catch ( Exception $e ) {
        echo "SRAKA";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Koszyk</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/cart.css" >
    <link rel="stylesheet" href="css/secondary.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <a href="." class="menu-bold"><i class="fa-solid fa-house"></i></a>
        <?php
            if ( isset($_SESSION['loggedin_id']) ) {
                echo "<a href='user' class='menu-bold'><i class='fa-solid fa-user'></i></a>";
                echo "<a href='logout' class='menu-bold'><i class='fa-solid fa-right-from-bracket'></i></a>";
            } else {
                echo "<a href='login' class='menu-bold'><i class='fa-solid fa-right-to-bracket'></i></a>";
            }
            
        ?>
    </div>

    <div class='hamburger-container scroll-minimize'>
        <input type='checkbox'>
        <div class='hamburger'>
            <div></div>
        </div>
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh</h1>
    </header>

    <main>

        <h3 class='title'>KOSZYK</h3>

        <?php
        
            if ( count($cartEntries) == 0 ) { 
                echo "<p>KOSZYK JEST PUSTY!</p>";    
            }
            else {
                echo "<section class='cart-entry-container'>";
                foreach ( $cartEntries as $cartEntry ) {
                    echo 
                        "<section class='cart-entry'>
                            <a href='productPage?product_id=".$cartEntry['product_id']."'>
                                <img src='".$cartEntry['img_path']."' class='cart-img'>
                                <div class='cart-entry-details'>
                                    <h5 class='cart-entry-title'>".$cartEntry['name']."</h5>
                                    <p class='cart-entry-price'>".$cartEntry['price']." zł</p>
                                </div>
                            </a>
                            <div class='cart-entry-quantity'>
                                <button class='cart-entry-quantity-up' entry_id='".$cartEntry['cart_entry_id']."'>
                                    <img src='gfx/dropdown.svg'>
                                </button>
                                <input type='number' value='".$cartEntry['quantity']."' min='1' max='999' name='".$cartEntry['cart_entry_id']."'>
                                <button class='cart-entry-quantity-down' entry_id='".$cartEntry['cart_entry_id']."'>
                                    <img src='gfx/dropdown.svg'>
                                </button>
                            </div>
                            <form class='delete-form' action='#' method='POST'>
                                <input type='submit' value=''>
                                <input type='hidden' name='delete_id' value=".$cartEntry['cart_entry_id'].">
                                <i class='fa-solid fa-trash fa-xl'></i>
                            </form>
                        </section>";
                }
                echo "</section>";
                echo 
                    "
                    <section class='cart-summary'>
                        <p class='small-title'>Suma: </p>
                        <p><span class='cart-summary-price'>".cartSum($cartEntries)." PLN</span></p>
                        <button class='off small-btn gray' id='recalculate'>
                            <i class='fa-solid fa-arrows-rotate'></i>
                            PRZELICZ
                        </button>
                    
                        <section class='order-container'>
                            <form method='POST' action='order?cart_id=".$cartId."'>
                                <input type='submit' value='ZŁÓŻ ZAMÓWIENIE' class='big-btn'>
                                <input type='hidden' name='token' value='true'>
                            </form>
                        </section>
                    </section>";
            }

        ?>

    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/quantityControll.js"></script>
<script src="scripts/createToken.js"></script>
</html>