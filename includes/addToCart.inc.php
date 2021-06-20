<?php
//start session
session_start();

//vars
$uuid = $_POST["uuid"];
$amount = $_POST["amount"];
$error = 0;

if ($amount < 1) {
    $error++;
}

if (!filter_var($amount, FILTER_VALIDATE_INT)) {
    $error++;
}

if ($error == 0) {
    //add products to shopping cart
    if (!isset($_SESSION['shoppingCart'])) {
        for ($i = 0; $i < $amount; $i++) {
            $shoppingCart[] = $uuid;
        
            $_SESSION['shoppingCart'] = $shoppingCart;
        }
    } else {
        for ($i = 0; $i < $amount; $i++) {
            $shoppingCart = $_SESSION['shoppingCart'];
        
            $shoppingCart[] = $uuid;
            $_SESSION['shoppingCart'] = $shoppingCart;
        }
    }
    echo "success";
} else {
    echo "failed";
}