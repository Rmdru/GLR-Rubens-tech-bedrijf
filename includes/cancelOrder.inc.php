<?php
//load config file
require "config.inc.php";

//vars
$orderId = $_GET['orderId'];

$sql = "UPDATE `order` SET status = 0, deliveryTime = -1 WHERE orderId = :orderId";
if ($stmt = $dbh->prepare($sql)) {
    $stmt->bindParam(":orderId", $orderId);
    if ($stmt->execute()) {
        header("location: ../mijnAccount.php");       
    }
}