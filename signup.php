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
            $passwd = password_hash("gówno", PASSWORD_DEFAULT);

            if ( isset($_POST['submit']) ) {
                if ( empty($_POST['email']) ) $emailError = EMPTY_FIELD_ERROR;
                if ( empty($_POST['login']) ) $loginError = EMPTY_FIELD_ERROR;
                if ( empty($_POST['passwd']) ) $passwdError = EMPTY_FIELD_ERROR;
                if ( empty($_POST['repasswd']) ) $repasswdError = EMPTY_FIELD_ERROR;
            }
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
        <form class="login-container" method="POST">
            <p class="label">email</p>
            <input type="text" name='email'>
            <p class='error'>
                <?php
                    if ( isset($emailError) ) echo $emailError;
                    else echo '&nbsp;'
                ?>
            </p>

            <p class="label">login</p>
            <input type="text" name='login'>
            <p class='error'>
                <?php
                    if ( isset($loginError) ) echo $loginError;
                    else echo '&nbsp;'
                ?>
            </p>

            <p class="label">hasło</p>
            <input type="password" name='passwd'>
            <p class='error'>
                <?php
                    if ( isset($passwdError) ) echo $passwdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <p class="label">powtórz hasło</p>
            <input type="password" name='repasswd'>
            <p class='error'>
                <?php
                    if ( isset($repasswdError) ) echo $repasswdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <input type="submit" value="Zarejestruj" name='submit' class='big-btn'>
        </form>
        <a href="login.php">Zaloguj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
</html>