<?php
    session_start();

    if ( !isset($_GET['cart_id']) ) header("Location: cart.php");

    require_once "config.php";

    mysqli_report(MYSQLI_REPORT_STRICT);
    error_reporting(0);

    try {
        $connection = new mysqli($servername,$username,$passwd,$dbname);
        
        if ( $connection->connect_errno ) {
            throw new Exception();
        } else {
            $orderEntries = array();
            $images = array();
            
            $query = "SELECT cart_entry_id,cart_id,product.product_id,quantity,price,name FROM `cart_entry` JOIN product on ( cart_entry.product_id = product.product_id ) WHERE cart_id=".$_GET['cart_id'];
            if ( $response = $connection->query($query) ) {
                fetchAllToArray($orderEntries, $response);
                $response->free();
            } else {
                throw new Exception();
            }

            if ( isset($_POST['submit']) ) {

                define('EMPTY_FIELD_ERROR', 'Pole jest puste!');
                $number_reg = "/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/";
                $postCode_reg = "/^[0-9]{2}-[0-9]{3}$/";
                $address_reg = "/^(?:[A-Za-z ,.-]+)(?:[0-9]+)$/";
                $surname_reg = "/^[A-Za-z-ĄĘĆŻŹŁąęćżźł ]*$/";
                $name_reg = "/^[A-Za-zĄĘĆŻŹŁąęćżźł]*$/";
                
                if ( empty($_POST['name']) ) $nameError = EMPTY_FIELD_ERROR;
                else if ( !preg_match($name_reg, $_POST['name']) ) $nameError = 'Imie może składać się tylko z liter!';

                if ( empty($_POST['surname']) ) $surnameError = EMPTY_FIELD_ERROR;
                else if ( !preg_match($surname_reg, $_POST['surname']) ) $surnameError = 'Nazwisko może składać się tylko z liter!';

                if ( empty($_POST['email']) ) $emailError = EMPTY_FIELD_ERROR;
                else if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) $emailError = 'Nieprawidłowy email!';

                if ( empty($_POST['address']) ) $addressError = EMPTY_FIELD_ERROR;
                else if ( !preg_match($address_reg, $_POST['address']) ) $addressError = 'Nieprawidłowy adres!';

                if ( empty($_POST['postCode']) ) $postCodeError = EMPTY_FIELD_ERROR;
                else if ( !preg_match($postCode_reg, $_POST['postCode']) ) $postCodeError = 'Nieprawidłowy kod pocztowy!';

                if ( empty($_POST['contactNumber']) ) $contactNumberError = EMPTY_FIELD_ERROR;
                else if ( !preg_match($number_reg, $_POST['contactNumber']) ) $contactNumberError = 'Nieprawidłowy numer telefonu!';

                if ( !isset($nameError)&&!isset($surnameError)&&!isset($emailError)&&!isset($emailError)&&!isset($addressError)&&!isset($postCodeError)&&!isset($contactNumberError) ) {
                    header("Location: sraka.php?order_id=".$_GET['cart_id']);
                }
                # CHANGE THIS!
            }
            $connection->close();
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
    <title>Sklep - Zamówienie</title>
    <link rel="stylesheet" href="css/main.css" >
    <link rel="stylesheet" href="css/order.css" >
</head>
<body>
    <div class="cover">&nbsp;</div>

    <div class="menu-container hidden">
        <br><br>
        <a href="index.php" class="menu-bold">Sklep</a>
        <a href="cart.php" class="menu-bold">Koszyk</a>
        <a href="login.php" class="menu-bold">Zaloguj</a>
    </div>

    <div class="hamburger-container scroll-minimize">
        <input type="checkbox" id="hamburger-checkbox" autocomplete="off"> 
        <img src="gfx/hamburger.svg" class="hamburger-icon">
    </div>

    <header class="scroll-minimize">
        <h1 class="scroll-minimize">Waltuh Shop</h1>
    </header>

    <main>

        <h3>ZAMÓWIENIE</h3>

        <section class='order-form-container'>
            <form method='POST'>
                <table>
                    <tr>
                        <td class='label'>
                            <p>Imie: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='name' 
                                <?php
                                    if ( isset($_POST['name']) ) echo "value='".$_POST['name']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($nameError) ) echo $nameError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                    <tr>
                        <td class='label'>
                            <p>Nazwisko: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='surname'
                                <?php
                                    if ( isset($_POST['surname']) ) echo "value='".$_POST['surname']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($surnameError) ) echo $surnameError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                    <tr>
                        <td class='label'>
                            <p>Email: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='email'
                                <?php
                                    if ( isset($_POST['email']) ) echo "value='".$_POST['email']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($emailError) ) echo $emailError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                </table>
                    <hr>
                <table>
                    <tr>
                        <td  class='label'>
                            <p>Adres: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='address'
                                <?php
                                    if ( isset($_POST['address']) ) echo "value='".$_POST['address']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($addressError) ) echo $addressError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                    <tr>
                        <td  class='label'>
                            <p>Kod pocztowy: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='postCode'
                                <?php
                                    if ( isset($_POST['postCode']) ) echo "value='".$_POST['postCode']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($postCodeError) ) echo $postCodeError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                </table>    
                    <hr>
                <table>
                    <tr>
                        <td class='label'>
                            <p>Numer kontaktowy: </p>
                        </td>
                        <td class='input'>
                            <input type='text' name='contactNumber'
                                <?php
                                    if ( isset($_POST['contactNumber']) ) echo "value='".$_POST['contactNumber']."'";
                                ?>
                            >
                        </td>
                    </tr>
                    <tr class='error'><td colspan='2'><p class='error'>
                        <?php
                            if ( isset($contactNumberError) ) echo $contactNumberError;
                            else echo '&nbsp;'
                        ?>
                    </p></td></tr>
                </table>
                <div class="menu-header">
                    <label for="kategoria"><p class="menu-bold small">Pokaż Zamówienie:</p></label>
                    <div class="dropdown-container">
                        <input type="checkbox" class="dropdown-checkbox" autocomplete="off" id="kategoria">
                        <img src="gfx/dropdown.svg" class="dropdown-icon small">
                    </div>
                </div>
                <div class="menu-options dropdown-content hidden">
                    
                </div>
                <input type='submit' value='ZAMIAWIAM I PŁACĘ' class='big-btn' name='submit'>
            </form>
            
        </section>
    
    </main>

</body>
<script src="scripts/scroll.js"></script>
<script src="scripts/menu.js"></script>
<script src="scripts/quantityControll.js"></script>
</html>