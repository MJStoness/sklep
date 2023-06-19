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
            $orderOverview = array();
            $query = "SELECT * FROM order_overview WHERE identifier = '".$_GET['id']."'";
            if ( $response = $connection->query($query) ) {
                fetchAllToArray($orderOverview, $response);
                
            } else {
                throw new Exception();
            }
            if ( mysqli_num_rows($response) == 0 ) {
                $_SESSION['errorMessage'] = "Wybrane zamówienie nie istnieje!";
                header('Location: errorPage');
            }

            $response->free();

            $orderEntries = array();

            $query = "SELECT cart_entry_id,cart_entry.cart_id,product.product_id,quantity,price,`name`,cart.guest,cart.user_id FROM `cart_entry` JOIN product on ( cart_entry.product_id = product.product_id ) JOIN cart on ( cart.cart_id = cart_entry.cart_id ) WHERE cart_entry.cart_id=".$_POST['cart_id'];
            if ( $response = $connection->query($query) ) {
                fetchAllToArray($orderEntries, $response);
                $response->free();
            } else {
                throw new Exception();
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
    <title>Sklep - Zamówienie</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/order.css" >
    <link rel="stylesheet" href="css/secondary.css" >
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
        <h1 class="scroll-minimize"><img src='gfx/logo.png' class='logo'></h1>
    </header>

    <main>

        <h3 class="title">Wybierz metodę płatności:</h3>

        <div class="payment-options">
            <!-- <a href="orderFinal?id=<?php //echo $_GET['id']; ?>"><div class="payment-option">Przelew online <i class="fa-solid fa-building-columns"></i></div></a>
            <a href="orderFinal?id=<?php //echo $_GET['id']; ?>"><div class="payment-option">Kartą <i class="fa-solid fa-credit-card"></i></div></a>
            <a href="orderFinal?id=<?php //echo $_GET['id']; ?>"><div class="payment-option"><img src="gfx/blik.png" alt="BLIK"></div></a> -->

            <form action="orderFinal">

                <div class="payment-option">Przelew online <i class="fa-solid fa-building-columns"></i><input type="radio" name="payment" value="transfer"></div>
                <div class="payment-option">Kartą <i class="fa-solid fa-credit-card"></i><input type="radio" name="payment" value="card"></div>
                <div class="payment-option"><img src="gfx/blik.png" alt="BLIK"><input type="radio" name="payment" value="blik"></div>
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

                <input type="submit" value="Zapłać!" class="big-btn">
            </form>

        </div>
    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/highlight.js"></script>
</html>