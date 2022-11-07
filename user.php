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
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold"><img src="gfx/cart.svg" alt="koszyk"></a>
        <a href='logout.php' class='menu-bold'><img src='gfx/logout.svg' alt='wyloguj'></a>
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
        
            <h3>Moje konto:<h3>
        <section class="user-settings-container">
            <form action='' method='POST'>
                <label class='sans <?php if ( isset($emailError) ) echo 'error'; ?>'>Email:</label>
                <div class='user-setting-container'>
                    <input type='text' name='email' value='<?php echo $userData['email'] ?>' class='sans <?php if ( !isset($emailError) && !isset($_POST['change'])) echo 'showcase';?> <?php if ( isset($emailError) ) echo 'error'; ?>'>
                    <button type='button' class='setting-change <?php if ( isset($emailError) ) echo 'hidden'; ?>'><img src='gfx/edit.svg'></button>
                </div>
                <p class='error'>
                    <?php
                        if ( isset($emailError) ) echo $emailError;
                        else echo '&nbsp;'
                    ?>
                </p>

                <label class='sans <?php if ( isset($loginError) ) echo 'error'; ?>'>Login:</label>
                <div class='user-setting-container'>
                    <input type='text' name='login' value='<?php echo $userData['login'] ?>' class='sans showcase <?php if ( isset($loginError) ) echo 'error'; ?>'>
                    <button type='button' class='setting-change'><img src='gfx/edit.svg'></button>
                </div>
                <p class='error'>
                    <?php
                        if ( isset($loginError) ) echo $loginError;
                        else echo '&nbsp;'
                    ?>
                </p>

                <br><br>
                <input type='hidden' value='change' name='change'>
                <input type='submit' value='Zmień dane' name='submit' class='big-btn off' id='settings-submit'>
            </form>
        </section>
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/userSettings.js"></script>
</html>