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
            if ( isset($_POST['submit']) ) {
                if ( empty($_POST['login']) ) $loginError = EMPTY_FIELD_ERROR;
                $response = $connection->query("SELECT * FROM user WHERE `login`='".$_POST['login']."' OR `email`='".$_POST['login']."'");
                if ( !$userRow =$response->fetch_assoc() ) $loginError = 'Konto o podanym loginie lub emailu nie istnieje!';

                if ( !password_verify($_POST['passwd'], $userRow['passwd']) ) $passwdError = 'Nieprawidłowe hasło!';

                if ( !isset($loginError)&&!isset($passwdError) ) {
                    $_SESSION['loggedin_id'] = $userRow['user_id'];
                    header('Location: index.php');
                }
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
    <title>Sklep - Logowanie</title>
    <link rel="stylesheet" href="css/main.css" ><link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/login.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold">Koszyk</a>
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
        <h2>Logowanie</h2>
        <form class="standard-form-container" method="POST">
            <label class='sans <?php if ( isset($loginError) ) echo 'error'; ?>' for='form-login' data-highlight='yes'>Login lub email: </label>
            <input type='text' class='sans <?php if ( isset($loginError) ) echo 'error'; ?>' id='form-login' data-highlight='yes' name='login'
                <?php
                    if ( isset($_POST['login']) ) echo "value='".$_POST['login']."'";
                ?>
            >
            <p class='error'>
                <?php
                    if ( isset($loginError) ) echo $loginError;
                    else echo '&nbsp;'
                ?>
            </p>
            
            <label class='sans <?php if ( isset($passwdError) ) echo 'error'; ?>' for='form-passwd' data-highlight='yes'>Hasło: </label>
            <input type='password' class='sans <?php if ( isset($passwdError) ) echo 'error'; ?>' id='form-passwd' data-highlight='yes' name='passwd'
                <?php
                    if ( isset($_POST['passwd']) ) echo "value='".$_POST['passwd']."'";
                ?>
            >
            <p class='error'>
                <?php
                    if ( isset($passwdError) ) echo $passwdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <input type="submit" value="Zaloguj" name='submit' class='big-btn'>
        </form>
        <a href="signup.php" class='a-btn'>Zarejestruj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
</html>