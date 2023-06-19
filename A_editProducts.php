<?php
    session_start();

    require_once "config.php";
    
    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    //define("FAILURE_TO_ADD_PRODUCT_POPUP", "onload = \"pop('Nie udało się dodać produktu!', 'red', '1.5s')\"");
    //define("SUCCESS_TO_ADD_PRODUCT_POPUP", "onload = \"pop('Produkt dodano poprawnie!', 'green', '1.5s')\"");

    if ( !isset($_SESSION['admin']) || $_SESSION['admin'] != true ) {
        header("Location: index");
    }

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);

        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {

            $products = array();
            $images = array();
            $categories = array();

            //================================================= RETRIEVING PRODUCTS
            $query = "SELECT product_id,category.category,name,price,description FROM `product` JOIN category on ( product.category_id = category.category_id )";
            
            if ( $response = $connection->query($query) ) {
                fetchAllToArray($products, $response);
                $response->free();
            } else {
                throw new Exception();
            }
            //==================================================

            //================================================ RETRIEVING PHOTOS
            $query = "SELECT * FROM image GROUP BY product_id HAVING product_id IN(".colToString($products, 'product_id', ', ').")";
            if ( $response = @$connection->query($query) ) {
                fetchAllToArray($images, $response);
                $response->free();
            } else {
                throw new Exception();
            }
            //================================================

            unionArraysByCommonIndex($products, $images, 'img_path', 'product_id', 'path');

            //============================================== RETRIEVING CATEGORIES
            $query = "SELECT * FROM category";
            if ( $response = @$connection->query($query) ) {
                fetchAllToArray($categories, $response);
                $response->free();
            }
            //================================================

            $connection->close();
        } 
    } catch( Exception $e ) {
        echo "SRAKA";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Zarządzanie Produktami</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/display.css" >
    <link rel="stylesheet" href="css/secondary.css" >
    <link rel="stylesheet" href="css/popup.css" >
    <link rel="stylesheet" href="css/admin.css" >
    <link rel="stylesheet" href="css/cutomSelect.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body <?php if ( isset($bodyOnload) ) echo $bodyOnload; ?>>
    <div class="alert">
        <i class="fa-solid fa-xmark"></i>
        <h2>Czy napewno chcesz usunąć te produkty?:</h2>
        <ul>
        </ul>
        <button class="alert-confirm big-btn">usuń</button>
    </div>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <a href="." class="menu-bold"><i class="fa-solid fa-house"></i></a>
        <a href="cart" class="menu-bold"><i class='fa-solid fa-cart-shopping'></i></a>
        <?php
            if ( isset($_SESSION['loggedin_id']) ) {
                echo "<a href='user' class='menu-bold'><i class='fa-solid fa-user'></i></a>";
                echo "<a href='logout' class='menu-bold'><i class='fa-solid fa-right-from-bracket'></i></a>";
            } else {
                echo "<a href='login' class='menu-bold'><i class='fa-solid fa-right-to-bracket'></i></a>";
            }
            if ( isset($_SESSION['admin']) && $_SESSION['admin'] == true ) {
                echo "<a href='A_adminPanel' class='menu-bold admin'><i class='fa-solid fa-hammer'></i></a>";
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
        <h1 class="scroll-minimize"><img src='gfx/logo.png' class='logo'></h1>
    </header>

    <main>
        <div id="delete-selected-container">
            <button class="big-btn">
                Usuń <span id="product-count"></span> produktów
            </button>
        </div>

        <div class='product-edit-entries'>
            <?php
                foreach ( $products as $product ) {
                    echo "<div class='product-edit-entrie'>
                            <a href='productPage?product_id=".$product['product_id']."'>
                                <div class='product-edti-details-container'>
                                    <span>
                                        <img src='".$product['img_path']."'>
                                        <span>
                                            <h2>".$product['name']."</h2>
                                            <p class='price'>".$product['price']." zł</p>
                                        </span>
                                    </span>
                                    <p class='desc textCutoff'>".$product['description']."</p>
                                </div>
                            </a>
                            <div class='product-edti-controlls-container'>
                                <div class='custom-checkbox'>
                                    <input type='checkbox' name='checkedProduct[]' value='".$product['product_id']."' class='delete-product-checkbox'>
                                    <div><i class='fa-solid fa-check fa-l'></i></div>
                                </div>
                                <a href='A_editProduct?id=".$product['product_id']."'>
                                    <i class='fa-solid fa-pen-to-square fa-xl'></i>
                                </a>
                                <span class='delete-product' data-product-id='".$product['product_id']."'>
                                    <i class='fa-solid fa-trash fa-xl'></i>
                                </span>
                            </div>
                        </div>";
                }
            ?>
        </div>

    </main>

</body>
<script src="scripts/popup.js"></script>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/textCutoff.js"></script>
<script src="scripts/editProducts.js"></script>
</html>