<?php

    session_start();

    require_once "config.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    if ( isset($_SESSION['loggedin_id']) ) {
        header("Location: user");
    }

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);
        
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            if ( isset($_POST['submit']) ) {
                if ( empty($_POST['login']) ) $loginError = EMPTY_FIELD_ERROR;
                $response = $connection->query("SELECT * FROM user WHERE `login`='".mysqli_real_escape_string($connection, htmlentities($_POST['login'], ENT_QUOTES, "UTF-8"))."' OR `email`='".mysqli_real_escape_string($connection, htmlentities($_POST['login'], ENT_QUOTES, "UTF-8"))."'");
                if ( !$userRow =$response->fetch_assoc() ) $loginError = 'Konto o podanym loginie lub emailu nie istnieje!';

                if ( !password_verify($_POST['passwd'], $userRow['passwd']) ) $passwdError = 'Nieprawidłowe hasło!';

                if ( !isset($loginError)&&!isset($passwdError) ) {
                    if ( $userRow['admin'] == 1 ) { 
                        $_SESSION['admin'] = true;
                    } else { 
                        unset($_SESSION['admin']); 
                    }
                    $_SESSION['loggedin_id'] = $userRow['user_id'];
                    header('Location: .');
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
    <link rel="stylesheet" href="css/secondary.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <a href="." class="menu-bold"><i class="fa-solid fa-house"></i></a>
        <a href="cart" class="menu-bold"><i class='fa-solid fa-cart-shopping'></i></a>
        <a href='login' class='menu-bold'><i class='fa-solid fa-right-to-bracket'></i></a>
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
        <h3 class="title">Logowanie</h3>
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
            <span class='passwd-container'><input type='password' class='sans <?php if ( isset($passwdError) ) echo 'error'; ?>' id='form-passwd' data-highlight='yes' name='passwd'
                <?php
                    if ( isset($_POST['passwd']) ) echo "value='".$_POST['passwd']."'";
                ?>
            ><i class="fa-solid fa-eye-low-vision" data-toggled="untoggled"></i></span>
            <p class='error'>
                <?php
                    if ( isset($passwdError) ) echo $passwdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <input type="submit" value="Zaloguj" name='submit' class='big-btn'>
        </form>
        <a href="signup" class='a-btn'>Zarejestruj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/passwdControll.js"></script>
</html>