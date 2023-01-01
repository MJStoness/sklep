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

            $categories = array();

            //============================================== RETRIEVING CATEGORIES
            $query = "SELECT * FROM category";
            if ( $response = @$connection->query($query) ) {
                fetchAllToArray($categories, $response);
                $response->free();
            }
            //================================================

            if ( empty($_POST['images']) || empty($_POST['productTitle']) || empty($_POST['productCategory']) || empty($_POST['productPrice1']) || empty($_POST['productPrice2']) || empty($_POST['productDescription']) ) {
                
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
    <title>Sklep - Logowanie</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/display.css" >
    <link rel="stylesheet" href="css/secondary.css" >
    <link rel="stylesheet" href="css/popup.css" >
    <link rel="stylesheet" href="css/admin.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body>
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
        <h1 class="scroll-minimize">Waltuh</h1>
    </header>

    <main>

        <form action="php_scripts/A_insertProduct" method="POST" class="product-add-container">
            <div class="add-photo-container">
                <div class="input-file-container">
                    <label for="addPhoto">
                    <i class="fa-solid fa-upload"></i>
                    <p>Przeciagnij i<br> upusc</p>
                    </label>
                    <input type="file" name="images[]" id="addPhoto" multiple disabled>
                </div>
                <p class="add-photo-title">Dodaj<br>zdjecie</p>
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
                <label for="productTitle">
                    <h2 class="form-title">
                        Wybierz kategorię
                    </h2>
                </label>
                <select name="productCategory" id="productCategory">
                    <?php
                        foreach ( $categories as $category ) {
                            echo "<option value=".$category["category"].">".$category["category"]."</option>";
                        }
                    ?>
                </select>
            </div>
            <a href="A_manageCategories"><i class="fa-solid fa-plus"></i> Dodaj kategorię</a>

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
                        <input type="number" min="0" max="9999" step="1" value="1" id="productPrice2" name="productPrice2">
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
</html>