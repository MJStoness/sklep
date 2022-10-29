<?php

    session_start();

    if ( !isset($_SESSION['loggedin']) ) header("Location: login.php");
    function zmiana() {
        if ( isset($_GET['zmiana']) ) return true;
        else return false;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Konto</title>
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/login.css" >
    <link rel="stylesheet" href="css/user.css" >
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
        <section class="user-settings-container">
            <h3>Moje konto:<h3>
            <form>
            <table class="user-settings">
                <tr>
                    <td><p class="user-settings-title">Email: </p></td>
                    <td>
                        <?php
                            if ( zmiana() ) {
                                echo '<input type="text" name="email"></input>';
                            } else {
                                echo '<p class="user-settings-field">methdealer420@onet.pl</p>';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><p class="user-settings-title">Nazwa: </p></td>
                    <td>
                    <?php
                            if ( zmiana() ) {
                                echo '<input type="text" name="username"></input>';
                            } else {
                                echo '<p class="user-settings-field">Xx_Heisenberg_xX</p>';
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td><p class="user-settings-title">Hasło: </p></td>
                    <td>
                    <?php
                            if ( zmiana() ) {
                                echo '<input type="password" name="passwd"></input>';
                            } else {
                                echo '<p class="user-settings-field">***************</p>';
                            }
                        ?>
                    </td>
                </tr>
                <?php
                    if ( zmiana() ) {
                        echo '<tr><td></td><td><input type="password" name="passwd"></input></td></tr>';
                    }
                ?>
            </table>
            <?php
                if ( zmiana() ) {
                    echo '<input type="submit"';
                } else {
                    echo '<a href="?zmiana=yes">Zatwierdź</a>';
                }
            ?>
            </form>
        </section>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
</html>