<?php
    session_start();

    /* if ( !isset($_POST['token']) ) {
        header("Location: cart.php");
    } */

    require_once "config.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);
        
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            
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
    <title>Sklep - Zamówienie</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/order.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold">Koszyk</a>
        <a href="login.php" class="menu-bold">Zaloguj</a>
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

        <h3>ZAMÓWIENIE ZOSTAŁO ZŁOŻONE</h3>
    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/createToken.js"></script>
</html>