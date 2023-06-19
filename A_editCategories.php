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

            $categories = array();

            //============================================== RETRIEVING CATEGORIES
            $query = "SELECT * FROM category";
            if ( $response = @$connection->query($query) ) {
                fetchAllToArray($categories, $response);
                $response->free();
            }
            //================================================

            //============================================== RETRIEVING PRODUCT LIST
            foreach ($categories as $key => $category  ) {
                $query = "SELECT product_id, name FROM `product` WHERE category_id = ".$category['category_id'];
                $products = array();
                if ( $response = @$connection->query($query) ) {
                    fetchAllToArray($products, $response);
                    $response->free();
                    $categories[$key]['products'] = $products;
                }
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
        <h2>Czy napewno chcesz usunąć tą kategorie i wszytkie produkty?:</h2>
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
                foreach ( $categories as $category ) {
                    echo "<div class='category-edit-entrie' data-id='".$category['category_id']."'>
                            <h5>".$category['category']."</h5>

                            <ul>";
                                foreach ($category['products'] as $product) {
                                    echo "<li><a href='A_editProduct?id=".$product['product_id']."'>".$product['name']."</a></li>";
                                }
                            echo "</ul>
                            <div class='category-edti-controlls-container'>
                                <a data-toggle='no'>
                                    <i class='fa-solid fa-pen-to-square fa-xl'></i>
                                </a>
                                <span class='delete-product' data-category-id='".$category['category_id']."' data-category-name='".$category['category']."'>
                                    <i class='fa-solid fa-trash fa-xl'></i>
                                </span>
                            </div> 
                        </div>";
                }
            ?>
            <div class="new-category-settings">
                <i class="fa-solid fa-plus" id="add-category"></i>
                <i class="fa-solid fa-xmark" id="cancel-category"></i>
            </div>
            
            <div id="new-category" data-toggle="no">
                <input type="text">
            </div>
        </div>
        

    </main>

</body>
<script src="scripts/popup.js"></script>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/textCutoff.js"></script>
<script src="scripts/editCategory.js"></script>
</html>