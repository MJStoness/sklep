<?php

    session_start();

    if ( !isset($_SESSION['loggedin_id']) ) header("Location: login.php");
    function zmiana() {
        if ( isset($_GET['zmiana']) ) return true;
        else return false;
    }

    require_once "config.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);
    
    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);

        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $userData = array();

            $query = "SELECT * FROM user WHERE `user_id`=".$_SESSION['loggedin_id'];
            if ( $response = $connection->query($query) ) {
                $userData = $response->fetch_assoc();
                $response->free();
            } else {
                throw new Exception();
            }

            if ( $_POST['change'] == 'change' ) {
                $login_reg = "/^[A-Za-z\dĄĘĆŻŹŁąęćżźł_]*$/";

                if ( empty($_POST['email']) ) $emailError = EMPTY_FIELD_ERROR;
                else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) $emailError = 'Nieprawidłowy email!';
                $response = $connection->query("SELECT email FROM user WHERE email='".$_POST['email']."'");
                $fetchedEmail = $response->fetch_row()[0];

                if ( $fetchedEmail && $fetchedEmail != $userData['email'] ) $emailError = 'Konto o podanym emailu już istnieje!';
                unset($response);

                if ( empty($_POST['login']) ) $loginError = EMPTY_FIELD_ERROR;
                else if ( strlen($_POST['login']) <= 3 ) $loginError = 'Login musi byc dłuższy niż 3 litery!';
                else if ( strlen($_POST['login']) >= 10 ) $loginError = 'Login nie może byc dłuższy niż 10 liter!';
                else if ( !preg_match($login_reg, $_POST['login']) ) $loginError = 'Dozwolone tylko litey, cyfry i znak \' _ \'';
                $response = $connection->query("SELECT login FROM user WHERE `login`='".$_POST['login']."'");
                $fetchedLogin = $response->fetch_row()[0];

                if ( $fetchedLogin && $fetchedLogin != $userData['login'] ) $loginError = 'Konto o podanym loginie już istnieje!';
                unset($response);

                if ( isset($emailError) || isset($loginError) ) $anyError = "Error";

                if ( !isset($anyError) ) {
                    $query = "UPDATE user SET email = '".$_POST['email']."', login = '".$_POST['login']."' WHERE `user_id` = ".$_SESSION['loggedin_id'];
                    if ( !$connection->query($query) ) {
                        throw new Exception();
                    }
                }
            }
        }

    } catch ( Exception $e ) {
        echo "SRAKA";
    } 

    #TO DO:
    # - only one edit button actvates the whole form. (it's easier and I'm stoopid)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Konto</title>
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/hamburger.css" >
    <link rel="stylesheet" href="css/login.css" >
    <link rel="stylesheet" href="css/user.css" >
    <link rel="stylesheet" href="css/secondary.css" >
    <script src="https://kit.fontawesome.com/252efe8be7.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <a href="index.php" class="menu-bold"><i class="fa-solid fa-house"></i></a>
        <a href="cart.php" class="menu-bold"><i class='fa-solid fa-cart-shopping'></i></a>
        <a href='logout.php' class='menu-bold'><i class='fa-solid fa-right-from-bracket'></i></a>

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
        
        <section class='user-settings-header'>
            <h3>Moje konto:<h3>
            <button type='button' class='<?php if ( isset($anyError) ) echo 'hidden'; ?>'  id='change-settings'><i class="fa-solid fa-user-pen fa-2xl"></i></button>
        </section>
        <section class="user-settings-container">
            <form action='' method='POST'>
                <label class='sans <?php if ( isset($emailError) ) echo 'error'; ?>'>Email:</label>
                <div class='user-setting-container'>
                    <input type='text' name='email' value='<?php if ( isset($_POST['email']) ) echo $_POST['email']; else echo $userData['email'] ?>' class='sans <?php if ( !isset($anyError) ) echo 'showcase';?> <?php if ( isset($emailError) ) echo 'error'; ?>'>
                </div>
                <p class='error'>
                    <?php
                        if ( isset($emailError) ) echo $emailError;
                        else echo '&nbsp;'
                    ?>
                </p>

                <label class='sans <?php if ( isset($loginError) ) echo 'error'; ?>'>Login:</label>
                <div class='user-setting-container'>
                    <input type='text' name='login' value='<?php if ( isset($_POST['login']) ) echo $_POST['login']; else echo $userData['login'] ?>' class='sans <?php if ( !isset($anyError) ) echo 'showcase';?> <?php if ( isset($loginError) ) echo 'error'; ?>'>
                </div>
                <p class='error'>
                    <?php
                        if ( isset($loginError) ) echo $loginError;
                        else echo '&nbsp;'
                    ?>
                </p>

                <br><br>
                <input type='hidden' value='change' name='change'>
                <input type='submit' value='Zmień dane' name='submit' class='big-btn <?php if ( !isset($anyError) ) echo 'off'?>' id='settings-submit'>
            </form>
        </section>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/userSettings.js"></script>
</html>