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
            if ( isset($_POST['images']) ) {
                print_r($_POST['images']);
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

        <form action="" method="POST" class="product-add-container">
        <div class="add-photo-container">
            <div class="input-file-container">
                <label for="addPhoto">
                <i class="fa-solid fa-upload"></i>
                <p>Przeciagnij lub upusc</p>
                </label>
                <input type="file" name="images[]" id="addPhoto" multiple>
            </div>
            <p class="add-photo-title">Dodaj<br>zdjecie</p>
        </div>
            <input type="submit">
        </form>

    </main>

</body>
<script src="scripts/popup.js"></script>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/addtocart.js"></script>
</html>