<?php
//start session
session_start();

//get amount of shopping cart items
if (isset($_SESSION['shoppingCart'])) {
    $shoppingCartAmount = count($_SESSION['shoppingCart']);
} else {
    $shoppingCartAmount = 0;
}

echo $shoppingCartAmount;