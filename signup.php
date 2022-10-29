<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Rejestracja</title>
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/login.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold">Koszyk</a>
    </div>

    <div class="hamburger-container scroll-minimize">
        <input type="checkbox" id="hamburger-checkbox" autocomplete="off"> 
        <img src="gfx/hamburger.svg" class="hamburger-icon">
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>
        <h2>Rejestracja</h2>
        <form class="login-container">
            <p class="label">email</p>
            <input type="text">

            <p class="label">login</p>
            <input type="text">

            <p class="label">hasło</p>
            <input type="password">

            <p class="label">powtórz hasło</p>
            <input type="password">

            <input type="submit" value="zarejestruj">
        </form>
        <a href="login.php">Zaloguj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
</html>