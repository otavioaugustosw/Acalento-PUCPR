<?php
function debug_php($var) {
    echo "<pre>";
    var_dump($var);
    echo "<pre>";
}

include ("banco_php.php");
debug_php(conecta_db());

