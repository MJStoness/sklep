<?php
    $servername = "localhost";
    $username = "root";
    $passwd = "";
    $dbname = "sklep";

    define('EMPTY_FIELD_ERROR', 'Pole jest puste!');

    function colToString(array $arr, mixed $col, string $separator) {
        $toString = array();
        foreach ($arr as $el) {
            array_push($toString, $el[$col]);
        }

        return implode($separator,$toString);
    }

    function fetchAllToArray(array &$arr, $response) {
        $i=0;
        while ( $row = $response->fetch_assoc() ) {
            $arr[$i] = $row;
            $i++;
        }
    }

    function getRowByValueAtIndex(array $arr, mixed $index, mixed $value) {
        foreach ( $arr as $el ) {
            if ( $el[$index] == $value ) return $el;
        }

        return null;
    }

    function unionArraysByCommonIndex(array &$main, array $secondary, mixed $newIndex, mixed $commonIndex, mixed $secondaryIndex) {
        for ( $i=0; $i<count($main); $i++ ) {
            $main[$i][$newIndex] = getRowByValueAtIndex($secondary, $commonIndex, $main[$i][$commonIndex])[$secondaryIndex];
        }
    }

    function cartSum(array $cartEntires) {
        $sum = 0;
        foreach( $cartEntires as $cartEntire ) {
            $sum += floatval($cartEntire['price'])*intval($cartEntire['quantity']);
        }
        return number_format($sum, 2, '.', '');
    }

    function generateCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVZYabcdefghijklmnopqrstuvwxyz';
        $code = substr(str_shuffle($chars), 0, 10);
        return $code;
    }

    function generateIdentifier($connection, string $table) {
        do {
            $identifier = generateCode();
            $query = "SELECT indentifier FROM $table WHERE identifier = $identifier";
            $result = $connection->query($query);
        } while ($result);

        return $identifier;
    }
      