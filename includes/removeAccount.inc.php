<?php
//start session
session_start();

//load config file
require "config.inc.php";
  

//vars
$error = 0;
$uuid = $_GET['uuid'];


//validate data
if (empty($uuid)) {
    $error++;
}

//if no errors delete account
if ($error == 0) {
    //execute queries
    $sql = "DELETE FROM `order` WHERE uuidUser = :uuid";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
    }

    $sql = "DELETE FROM `user` WHERE uuid = :uuid";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":uuid", $uuid);
        $stmt->execute();
    }

    //remove sessions
    unset($_SESSION['uuid']);
    unset($_SESSION['ipAddress']);
    unset($_SESSION['loginTime']);

    //remove cookie
    setcookie("autologin", "", time() - (86400 * 366), "/", "", true, true);

    //redirect user
    header("location: ../index.php");
} else {
    header("location: ../accountVerwijderen?uuid={$uuid}&status=failed");
}