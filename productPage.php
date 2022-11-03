<?php
    session_start();

    require_once "config.php";
    
    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);
        $displayedImages = array();

        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $query = "SELECT product_id,category.category,name,price,description FROM product JOIN category on ( product.category_id = category.category_id ) WHERE product_id=".$_GET['product_id'];
            
            if ( $response = $connection->query($query) ) {
                $displayedProduct = $response->fetch_assoc();
            } else {
                throw new Exception();
            }

            $query = "SELECT * FROM image WHERE product_id=".$_GET['product_id'];

            if ( $response = $connection->query($query) ) {
                fetchAllToArray($displayedImages, $response);
                $response->free();
            } else {
                throw new Exception();
            }

            $connection->close();
        } 
    } catch( Exception $e ) {
        echo "SRAKA";
    }

    #TO DO:
    # - photo

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Logowanie</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/display.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="cart.php" class="menu-bold">Koszyk</a>
        <a href="index.php" class="menu-bold">Sklep</a>
    </div>

    <div class='hamburger-container scroll-minimize'>
        <input type='checkbox'>
        <div class='hamburger'>
            <div></div>
        </div>
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>

        <?php
            echo
                "<section class='display-container'>
                    <img src='".@$displayedImages[0]['path']."' class='main-img'>
    
                    <section class='display-content'>
                        <div class='display-header'>
                            <h5 class='display-title'>".$displayedProduct['name']."</h5>
                            <button class='cart-btn display' value='".$displayedProduct['product_id']."'>
                                <img src='gfx/cart.svg'>
                            </button>
                        </div>
                    </button>
                        <hr>
                        <p class='display-price'>".$displayedProduct['price']." zł</p>
                        <p class='display-desc'>".$displayedProduct['description']."</p>
                    </section>
                </section>"
        ?>

    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/addtocart.js"></script>
</html>