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
            $products = array();
            $images = array();
            $categories = array();

            $filterByCategories = "";
            $orderBy = "";
            if ( isset($_POST['category']) ) $filterByCategories = " WHERE category IN (\"".implode("\", \"",$_POST['category'])."\")";
            if ( isset($_POST['priceSort']) ) $orderBy = " ORDER BY price ".$_POST['priceSort'];

            //================================================= RETRIEVING PRODUCTS
            $query = "SELECT product_id,category.category,name,price FROM `product` JOIN category on ( product.category_id = category.category_id )".$filterByCategories.$orderBy;
            
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

            $categories = array();

            //============================================== RETRIEVING CATEGORIES
            $query = "SELECT * FROM category";
            if ( $response = @$connection->query($query) ) {
                fetchAllToArray($categories, $response);
                $response->free();
            }
            //================================================

            $connection->close();
        }

    } catch ( Exception $e ) {
        echo "SRAKA";
    }
    

    #TO DO:
    # - error handling
    # - documentation
    # - cart popup
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep</title>
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/hamburger.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class='hamburger-container scroll-minimize'>
        <input type='checkbox'>
        <div class='hamburger'>
            <div></div>
        </div>
    </div>

    <div class="menu-container hidden">
        <form action="#" method="POST">
            <div class="menu-header">
                <label for="kategoria" class="clean-label"><p class="menu-bold">Kategoria:</p></label>
                <div class="dropdown-container">
                    <input type="checkbox" class="dropdown-checkbox" autocomplete="off" id="kategoria" <?php if ( isset($_POST['category']) ) echo "checked";?>>
                    <img src="gfx/dropdown.svg" class="dropdown-icon">
                </div>
            </div>
            <div class="menu-options dropdown-content <?php if ( !isset($_POST['category']) ) echo "hidden";?>">

                <?php

                    //======================================== PROCEDURAL CATEGORY MENU GENERATING WITH CATEGORIES IN DATABASE
                    foreach ( $categories as $category ) {
                        echo "<label><input type='checkbox' name='category[]' value='".$category['category']."'";
                        if(isset($_POST['category']) && in_array($category['category'],$_POST['category'])) echo "checked";
                        echo "> ".$category['category']."</label>";
                    }
                    //=================================================

                    #TO DO:
                    # - display number of items currently in cart

                ?>

            </div>

            <div class="menu-header">
                <label for="sortuj" class="clean-label"><p class="menu-bold">Sortuj:</p></label>
                <div class="dropdown-container">
                    <input type="checkbox" class="dropdown-checkbox" autocomplete="off" id="sortuj" <?php if ( isset($_POST['priceSort']) ) echo "checked";?>>
                    <img src="gfx/dropdown.svg" class="dropdown-icon">
                </div>
            </div>
            <div class="menu-options dropdown-content <?php if ( !isset($_POST['priceSort']) ) echo "hidden";?>">
                <label><input type="radio" name="priceSort" value="ASC" <?php if(isset($_POST['priceSort']) && $_POST['priceSort'] == "ASC") echo "checked";?>> Cena rosnąco</label>
                <label><input type="radio" name="priceSort" value="DESC" <?php if(isset($_POST['priceSort']) && $_POST['priceSort'] == "DESC") echo "checked";?>> Cena malejąco</label>
            </div>

            <input type="submit" value="Filtruj">
        </form>
        <a href="cart.php" class="menu-bold">Koszyk</a>
        <?php
            if ( isset($_SESSION['loggedin_id']) ) {
                echo "<a href='user.php' class='menu-bold'>Konto</a>";
                echo "<a href='loginout.php' class='menu-bold'>Wyloguj</a>";
            } else {
                echo "<a href='login.php' class='menu-bold'>Zaloguj</a>";
            }
            
        ?>
    </div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold">Koszyk</a>
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>
        <?php

            foreach ( $products as $product ) {
                echo
                    "<section class='product-container'>
                        <a href='productPage.php?product_id=".$product['product_id']."'>
                            <img src='".$product['img_path']."' class='product-img'>
                            <div class='product-description'>
                                <h5 class='product-title'>".$product['name']."</h5>
                                <p class='product-price'>".$product['price']." zł</p>
                                <p class='product-category'>".$product['category']."</p>
                            </div>
                        </a>
                        <button class='cart-btn' value='".$product['product_id']."'>
                            <img src='gfx/cart.svg'>
                        </button>
                    </section>";
            }

        ?>
    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/addtocart.js"></script>
</html>