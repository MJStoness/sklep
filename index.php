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
    <link rel="stylesheet" href="css/popup.css" >
    <link rel="stylesheet" href="css/dropdown.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
        <form action="#" method="POST" id="category-from">
            <section class="dropdowns-container">
                <div class="xdropdown-container">
                    <div class="xdropdown-header" data-toggle="<?php echo (isset($_POST['category']))?"on":"off"?>">
                        <p class="menu-bold">Kategoria: </p>
                        <div class="dropdown-icon-container">
                            <img src="gfx/dropdown.svg" class="dropdown-icon">
                        </div>
                    </div>
                    <div class="xdropdown-content">
                        <?php

                            //======================================== PROCEDURAL CATEGORY MENU GENERATING WITH CATEGORIES IN DATABASE
                            foreach ( $categories as $category ) {
                                echo "<label>
                                    <div class='custom-checkbox'>
                                        <input type='checkbox' name='category[]' value='".$category['category']."'";
                                            if(isset($_POST['category']) && in_array($category['category'],$_POST['category'])) echo "checked";
                                        echo ">
                                        <div><i class='fa-solid fa-check fa-l'></i></div>
                                    </div>";
                                echo $category['category']."</label>";
                            }
                            //=================================================

                            #TO DO:
                            # - display number of items currently in cart

                        ?>
                    </div>
                </div>

                <div class="xdropdown-container">
                    <div class="xdropdown-header" data-toggle="<?php echo (isset($_POST['priceSort']))?"on":"off"?>">
                        <p class="menu-bold">Sortuj: </p>
                        <div class="dropdown-icon-container">
                            <img src="gfx/dropdown.svg" class="dropdown-icon">
                        </div>
                    </div>
                    <div class="xdropdown-content">
                        <label><div class='custom-radio'>
                            <input type='radio'  name="priceSort" value="ASC" <?php if(isset($_POST['priceSort']) && $_POST['priceSort'] == "ASC") echo "checked";?>>
                            <div></div>
                        </div> Cena rosnąco</label>
                        <label><div class='custom-radio'>
                            <input type='radio'  name="priceSort" value="DESC" <?php if(isset($_POST['priceSort']) && $_POST['priceSort'] == "DESC") echo "checked";?>>
                            <div></div>
                        </div> Cena malejąco</label>
                    </div>
                </div>
            </section>

            <div class="filter-container">
                <i class="fa-solid fa-filter fa-xl"></i>
                <input type="submit" value="Filtruj">
            </div>
        </form>
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

    <header class="scroll-minimize">
        <h1 class="scroll-minimize"><img src='gfx/logo.png' class='logo'></h1>
    </header>

    <main>
        <div class="products-container">
        <?php

            foreach ( $products as $product ) {
                echo
                    "<section class='product-container'>
                        <a href='productPage?product_id=".$product['product_id']."'>
                            <img src='".$product['img_path']."' class='product-img'>
                            <div class='product-description'>
                                <span>
                                    <h5 class='product-title'>".$product['name']."</h5>
                                    <p class='product-price'>".$product['price']." PLN</p>
                                </span>
                                <p class='product-category'>".$product['category']."</p>
                            </div>
                        </a>
                        <button class='cart-btn' value='".$product['product_id']."'>
                            <i class='fa-solid fa-cart-shopping fa-xl'></i>
                        </button>
                    </section>";
            }

        ?>
        </div>
    
    </main>
    
</body>
<script src="scripts/popup.js"></script>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/addtocart.js"></script>
<script src="scripts/dropdown.js"></script>
</html>