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
                echo "LOGGEDIN CART";
            }
            if ( isset($_SESSION['guest']) ) {
                $query = "SELECT cart_id FROM cart WHERE guest=".$_SESSION['guest'];
                if ( $response = $connection->query($query) ) {
                    $cartId = $response->fetch_assoc()['cart_id'];
                } else {
                    throw new Exception();
                }

                $query = "SELECT cart_entry_id,cart_id,product.product_id,quantity,price,name FROM `cart_entry` JOIN product on ( cart_entry.product_id = product.product_id ) WHERE cart_id=".$cartId;
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
            }

            if ( isset($_POST['delete_id']) ) {
                $query = "DELETE FROM `cart_entry` WHERE `cart_entry_id`=".$_POST['delete_id'];
                if ( !$connection->query($query) ) {
                    throw new Exception();
                }
                header("Refresh:0");
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
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/cart.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="login.php" class="menu-bold">Zaloguj</a>
    </div>

    <div class="hamburger-container scroll-minimize">
        <input type="checkbox" id="hamburger-checkbox" autocomplete="off"> 
        <img src="gfx/hamburger.svg" class="hamburger-icon">
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>

        <h3>KOSZYK</h3>

        <?php
        
            if ( count($cartEntries) == 0 ) { 
                echo "<p>KOSZYK JEST PUSTY!</p>";    
            }
            else {
                echo "<br><section class='cart-entry-container'>";
                foreach ( $cartEntries as $cartEntry ) {
                    echo 
                        "<section class='cart-entry'>
                            <a href='productPage.php?product_id=".$cartEntry['product_id']."'>
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
                                <img src='gfx/delete.svg'>
                            </form>
                        </section>";
                }
                echo "</section>";
                echo 
                    "
                    <section class='cart-summary'>
                        <p class='small-title'>Suma: </p><p><span class='cart-summary-price'>".cartSum($cartEntries)."</span> zł</p>
                        <hr>
                        <button class='off btn' id='recalculate'>PRZELICZ</button>
                    </section>
                    <section class='order-container'>
                        <form method='POST' action='order.php?cart_id=".$cartId."'>
                            <input type='submit' value='ZŁÓŻ ZAMÓWIENIE' class='big-btn'>
                            <input type='hidden' name='token' value='true'>
                        </form>
                    </section>";
            }

            #TO DO:

        ?>

    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/quantityControll.js"></script>
<script src="scripts/createToken.js"></script>
</html>