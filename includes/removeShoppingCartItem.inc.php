<?php
//start session
session_start();

//vars
$shoppingCart = $_SESSION['shoppingCart'];
$uuidProduct = $_GET['uuidProduct'];

//search for uuid
$result = preg_grep("/$uuidProduct/i", $shoppingCart);

//remove al results that match te uuid
foreach ($result as $key => $value) {
    unset($shoppingCart[$key]);
}

$_SESSION['shoppingCart'] = $shoppingCart;

//add delay
sleep(0.5);