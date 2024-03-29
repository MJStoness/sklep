<?php
    session_start();

    require_once "config.php";
    
    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    if ( !isset($_SESSION['admin']) || $_SESSION['admin'] != true ) {
        header("Location: index");
    }

    define("FAILURE_TO_ADD_PRODUCT_POPUP", "onload = \"pop('Nie udało się dodać produktu!', 'red', '1.5s')\"");
    define("SUCCESS_TO_ADD_PRODUCT_POPUP", "onload = \"pop('Produkt dodano pomyślnie!', 'green', '1.5s')\"");

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

            if ( isset($_POST['submit']) ) {
                if ( empty($_FILES['images']) || empty($_POST['productTitle']) || empty($_POST['productCategory']) || empty($_POST['productPrice1']) || empty($_POST['productPrice2']) || empty($_POST['productDescription']) ) {
                    $bodyOnload = "onload = \"pop('Nie wszystkie pola zostały wypełnione!', 'red', '1.5s')\"";
                } else {
                    $newProductId;

                    $query = "SELECT MAX(product_id) as id FROM `product`;";
                    if ( $response = $connection->query($query) ) {
                        $newProductId = intval($response->fetch_assoc()['id']) + 1;
                        $response->free();
                    } else {
                        $bodyOnload = FAILURE_TO_ADD_PRODUCT_POPUP;
                    }

                    if ( !empty( $newProductId ) ) {

                        $categoryId = substr($_POST['productCategory'], 3);
                        
                        $price = ( $_POST['productPrice2'] < 10 )?floatval($_POST['productPrice1'].".0".$_POST['productPrice2']):floatval($_POST['productPrice1'].".".$_POST['productPrice2']);

                        $query = "INSERT INTO `product` (`product_id`, `category_id`, `price`, `name`, `description`) VALUES ($newProductId, $categoryId, $price, '".htmlentities($_POST['productTitle'])."', '".htmlentities($_POST['productDescription'])."')";
                        if ( $response = $connection->query($query) ) {
                            $imagesDir = "images";
                            foreach ($_FILES['images']['error'] as $key => $error) {
                                if ($error == UPLOAD_ERR_OK) {
                                    $tmpName = $_FILES['images']['tmp_name'][$key];
                                    $extension = pathinfo($_FILES['images']['name'][$key])['extension'];
                                    $newName = "product_".$newProductId."_".$key.".".$extension;
                                    move_uploaded_file($tmpName, "$imagesDir/$newName");

                                    $query = "INSERT INTO image (path, product_id) VALUES ('images/".$newName."', $newProductId);";
                                    if ( !$response = $connection->query($query) ) {
                                        $bodyOnload = FAILURE_TO_ADD_PRODUCT_POPUP;
                                    } else {
                                        $bodyOnload = SUCCESS_TO_ADD_PRODUCT_POPUP;
                                    }
                                }
                            } 
                        } else {
                            $bodyOnload = FAILURE_TO_ADD_PRODUCT_POPUP;
                        }
                    }

                }
            }
 
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
    <title>Sklep - Dodawanie Produktu</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/display.css" >
    <link rel="stylesheet" href="css/secondary.css" >
    <link rel="stylesheet" href="css/popup.css" >
    <link rel="stylesheet" href="css/admin.css" >
    <link rel="stylesheet" href="css/cutomSelect.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body <?php if ( isset($bodyOnload) ) echo $bodyOnload; ?>>
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

        <form action="" method="POST" class="product-add-container" enctype="multipart/form-data">
            <div class="add-photo-container">
                <div class="input-file-container">
                    <label for="addPhoto">
                    <i class="fa-solid fa-upload"></i>
                    <p>Przeciagnij i<br> upusc</p>
                    </label>
                    <input type="file" name="images[]" id="addPhoto" multiple>
                </div>
                <p class="add-photo-title">Dodaj<br>zdjecia</p>
            </div>
            <div class="file-preview"></div>

            <div class="form-cell">
                <label for="productTitle">
                    <h2 class="form-title">
                        Dodaj tytuł
                    </h2>
                </label>
                <input type="text" name="productTitle" id="productTitle">
            </div>

            <div class="form-cell">
                <label>
                    <h2 class="form-title">
                        Wybierz kategorię
                    </h2>
                </label>
                <div class="custom-select" data-toggle="off">
                    <div class="custom-select-header">
                        <span></span>
                        <i class="fa-solid fa-chevron-up"></i>
                    </div>
                        <div class="custom-select-list">
                        <?php
                            foreach ( $categories as $i => $category ) {
                                echo "<label for='cat".$category["category_id"]."'";
                                echo ($i == 0)?"data-selected='true'":"";
                                echo ">".$category["category"]."</label>";
                            }
                        ?>
                    </div>
                    <div class="custom-select-radio-container">
                        <?php
                            foreach ( $categories as $i => $category ) {
                                echo "<input type='radio' name='productCategory' value='cat".$category["category_id"]."' id='cat".$category["category_id"]."'>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <a href="A_editCategories"><i class="fa-solid fa-plus"></i> Dodaj kategorię</a>

            <div class="form-cell" style="margin-top: 50px;">
                <label for="productTitle">
                    <h2 class="form-title">
                        Dodaj cenę
                    </h2>
                </label>
                <div class="add-price-container">
                    <div class="custom-number-input">
                        <input type="number" min="0" max="9999" step="1" value="1" id="productPrice1" name="productPrice1">
                        <div class="custom-number-input-controlls">
                            <div class="count-up"><i class="fa-solid fa-chevron-up"></i></div>
                            <div class="count-down"><i class="fa-solid fa-chevron-down"></i></div>
                        </div>
                    </div>
                    <h2>zł</h2>

                    <div class="custom-number-input">
                        <input type="number" min="0" max="99" step="1" value="1" id="productPrice2" name="productPrice2">
                        <div class="custom-number-input-controlls">
                            <div class="count-up"><i class="fa-solid fa-chevron-up"></i></div>
                            <div class="count-down"><i class="fa-solid fa-chevron-down"></i></div>
                        </div>
                    </div>
                    <h2>gr</h2>
                </div>
            </div>

            <div class="form-cell">
                <label for="productTitle">
                    <h2 class="form-title">
                        Dodaj opis
                    </h2>
                </label>
                <textarea name="productDescription" id="productDescription"></textarea>
            </div>

            <div class="add-submit-container">
                <input type="submit" value="Dodaj" name="submit" class="big-btn">
                <a href="A_adminPanel" class="a-btn"><i class="fa-solid fa-rectangle-xmark"></i> Anuluj</a>
            </div>
        </form>

    </main>

</body>
<script src="scripts/popup.js"></script>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/addtocart.js"></script>
<script src="scripts/dragAndDrop.js"></script>
<script src="scripts/numberInput.js"></script>
<script src="scripts/customSelect.js"></script>
</html>