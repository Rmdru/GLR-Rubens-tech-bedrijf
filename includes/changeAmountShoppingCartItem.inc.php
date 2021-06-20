<?php
//start session
session_start();

//vars
$shoppingCart = $_SESSION['shoppingCart'];
$uuidProduct = $_GET['uuidProduct'];
$amount = $_GET['amount'];

//search for uuid
$result = preg_grep("/$uuidProduct/i", $shoppingCart);

//remove all results that match the uuid
foreach ($result as $key => $value) {
    unset($shoppingCart[$key]);
}

//add the uuids of the amount input
for ($i = 0; $i < $amount; $i++) {
    $shoppingCart[] = $uuidProduct;
    $_SESSION['shoppingCart'] = $shoppingCart;
}