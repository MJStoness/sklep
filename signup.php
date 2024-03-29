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

                    $query = "SELECT `user_id` FROM user WHERE email='".mysqli_real_escape_string($connection, htmlentities($_POST['email'], ENT_QUOTES, "UTF-8"))."'";
                    if ( $response = $connection->query($query) ) {
                        $userId = $response->fetch_row()[0];
                    } else {
                        throw new Exception();
                    }

                    $query = "INSERT INTO cart (`user_id`) VALUES ($userId)";
                    if ( !$connection->query($query) ) {
                        throw new Exception();
                    }

                    header("Location: signedup");
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
        <h3 class="title">Rejestracja</h3>
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

            <label class='sans <?php if ( isset($repasswdError) ) echo 'error'; ?>' for='form-repasswd' data-highlight='yes'>Powtórz hasło: </label>
            <span class='passwd-container'><input type='password' class='sans <?php if ( isset($repasswdError) ) echo 'error'; ?>' id='form-repasswd' data-highlight='yes' name='repasswd'
                <?php
                    if ( isset($_POST['repasswd']) ) echo "value='".$_POST['repasswd']."'";
                ?>
            ><i class="fa-solid fa-eye-low-vision" data-toggled="untoggled"></i></span>
            <p class='error'>
                <?php
                    if ( isset($repasswdError) ) echo $repasswdError;
                    else echo '&nbsp;'
                ?>
            </p>

            <input type="submit" value="Zarejestruj" name='submit' class='big-btn'>
        </form>
        <a href="login" class='a-btn'>Zaloguj się</a>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/passwdControll.js"></script>
</html>