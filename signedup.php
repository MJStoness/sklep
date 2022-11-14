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
        <a href="index.php" class="menu-bold"><i class="fa-solid fa-house"></i></a>
        <a href="cart.php" class="menu-bold"><i class='fa-solid fa-cart-shopping'></i></a>
        <?php
            if ( isset($_SESSION['loggedin_id']) ) {
                echo "<a href='user.php' class='menu-bold'><i class='fa-solid fa-user'></i></a>";
                echo "<a href='logout.php' class='menu-bold'><i class='fa-solid fa-right-from-bracket'></i></a>";
            } else {
                echo "<a href='login.php' class='menu-bold'><i class='fa-solid fa-right-to-bracket'></i></a>";
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
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>

        <br><br><br>

        <h3>REJESTRACJA PRZEBIEGŁA POMYŚLNIE!</h3>

        <br><br><br><br><br><br><br><br><br>
            
        <a href="login.php" class='a-btn'>Zaloguj się</a>
    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/createToken.js"></script>
</html>