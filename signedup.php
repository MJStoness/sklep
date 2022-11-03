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
        <a href="cart.php" class="menu-bold"><img src="gfx/cart.svg" alt="koszyk"></a>
        <?php
            if ( isset($_SESSION['loggedin_id']) ) {
                echo "<a href='user.php' class='menu-bold'><img src='gfx/user.svg' alt='konto'></a>";
                echo "<a href='logout.php' class='menu-bold'><img src='gfx/logout.svg' alt='wyloguj'></a>";
            } else {
                echo "<a href='login.php' class='menu-bold'><img src='gfx/login.svg' alt='zaloguj'></a>";
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