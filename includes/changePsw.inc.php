<?php
//start session
session_start();

//load config file
require "config.inc.php";

//vars
$uuid = $_POST['uuid'];
$pswUnencrypted = $_POST['psw'];
$pswRepeatUnencrypted = $_POST['pswRepeat'];
$psw = password_hash($pswUnencrypted, PASSWORD_BCRYPT, array("cost" => 12));
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$captchaInput = $_POST['captcha'];
$captchaSession = $_SESSION['captcha'];

//validate data
if ($csrfTokenInput != $csrfTokenSession) {
    header("location: wachtwoordWijzigen.php?status=failed");
}

if ($captchaInput != $captchaSession) {
    header("location: wachtwoordWijzigen.php?status=failed");
}

if ($pswRepeatUnencrypted != $pswUnencrypted) {
    header("location: wachtwoordWijzigen.php?status=failed");
}

//check if email exist and send mail
    $sql = "UPDATE `user` SET psw = :psw WHERE uuid = :uuid";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":psw", $psw);
        $stmt->bindParam(":uuid", $uuid);
        if ($stmt->execute()) {
            unset($_SESSION['verificationToken']);
            header("location: ../inloggen.php?pswChangeStatus=success");
        }
    }