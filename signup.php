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
                $login_reg = "/^[A-Za-z\dĄĘĆŻŹŁąęćżźł_]*$/";
                $passwd_reg = "/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d!@#$%^&*()])\S*$/";

                if ( empty($_POST['email']) ) $emailError = EMPTY_FIELD_ERROR;
                else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) $emailError = 'Nieprawidłowy email!';
                $response = $connection->query("SELECT * FROM user WHERE email='".$_POST['email']."'");
                if ( $response->fetch_row() ) $emailError = 'Konto o podanym emailu już istnieje!';
                unset($response);

                if ( empty($_POST['login']) ) $loginError = EMPTY_FIELD_ERROR;
                else if ( strlen($_POST['login']) <= 3 ) $loginError = 'Login musi byc dłuższy niż 3 litery!';
                else if ( strlen($_POST['login']) >= 10 ) $loginError = 'Login nie może byc dłuższy niż 10 liter!';
                else if ( !preg_match($login_reg, $_POST['login']) ) $loginError = 'Dozwolone tylko litey, cyfry i znak \' _ \'';
                $response = $connection->query("SELECT * FROM user WHERE `login`='".$_POST['login']."'");
                if ( $response->fetch_row() ) $loginError = 'Konto o podanym loginie już istnieje!';
                unset($response);

                if ( empty($_POST['passwd']) ) $passwdError = EMPTY_FIELD_ERROR;
                else if ( strlen($_POST['passwd']) <= 4 ) $passwdError = 'Hasło musi byc dłuższe niż 4 znaki!';
                else if ( strlen($_POST['passwd']) >= 25 ) $passwdError = 'Hasło musi byc krótsze niż 25 znaków!';
                else if ( !preg_match($passwd_reg, $_POST['passwd']) ) $passwdError = 'Hasło musi zawierac przynajmniej jedną małą i dużą literę oraz znak specjalny!';

                if ( empty($_POST['repasswd']) ) $repasswdError = EMPTY_FIELD_ERROR;
                else if ( $_POST['passwd'] != $_POST['repasswd'] ) $repasswdError = 'Hasła się różnią!';

                if ( !isset($emailError)&&!isset($loginError)&&!isset($passwdError)&&!isset($repasswdError) ) {
                    $query = "INSERT INTO user (`email`, `login`, `passwd`) VALUES (
                    '".mysqli_real_escape_string($connection, htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"))."',
                    '".mysqli_real_escape_string($connection, htmlentities($_POST['login'], ENT_QUOTES, "UTF-8"))."',
                    '".password_hash($_POST['passwd'], PASSWORD_DEFAULT)."')";
                    if ( !$connection->query($query) ) {
                        throw new Exception();
                    }
                    header("Location: signedup.php");
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
        <form class="standard-form-container" method="POST">
            <label class='sans <?php if ( isset($emailError) ) echo 'error'; ?>' for='form-email' data-highlight='yes'>Email: </label>
            <input type='text' class='sans <?php if ( isset($emailError) ) echo 'error'; ?>' id='form-email' data-highlight='yes' name='email'
                <?php
                    if ( isset($_POST['email']) ) echo "value='".$_POST['email']."'";
                ?>
            >
            <p class='error'>
                <?php
                    if ( isset($emailError) ) echo $emailError;
                    else echo '&nbsp;'
                ?>
            </p>

            <label class='sans <?php if ( isset($loginError) ) echo 'error'; ?>' for='form-login' data-highlight='yes'>Login: </label>
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

            <label class='sans <?php if ( isset($repasswdError) ) echo 'error'; ?>' for='form-repasswd' data-highlight='yes'>Powtórz hasło: </label>
            <input type='password' class='sans <?php if ( isset($repasswdError) ) echo 'error'; ?>' id='form-repasswd' data-highlight='yes' name='repasswd'
                <?php
                    if ( isset($_POST['repasswd']) ) echo "value='".$_POST['repasswd']."'";
                ?>
            >
            <p class='error'>
                <?php
                    if ( isset($repasswdError) ) echo $repasswdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <input type="submit" value="Zarejestruj" name='submit' class='big-btn'>
        </form>
        <a href="login.php" class='a-btn'>Zaloguj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
</html>