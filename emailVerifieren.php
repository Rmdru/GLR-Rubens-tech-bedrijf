<?php
//start session
session_start();

//load config file
require "includes/config.inc.php";

//vars
$error = 0;
$verificationToken = $_GET['verificationToken'];
$uuid = $_GET['uuid'];

//check if pattern of verification token is correct
$verificationTokenPattern = "/[a-zA-Z0-9]{32}/";
if (!preg_match($verificationTokenPattern, $verificationToken)) {
    $error++;
}

$sqlVerify = "SELECT COUNT(uuid) AS num FROM `user` WHERE verificationToken = :verificationToken AND uuid = :uuid";

if ($error == 0) {
    if ($stmtVerify = $dbh->prepare($sqlVerify)) {
        $stmtVerify->bindParam(":verificationToken", $verificationToken);
        $stmtVerify->bindParam(":uuid", $uuid);
        if ($stmtVerify->execute()) {
            $rowVerify = $stmtVerify->fetch(PDO::FETCH_ASSOC);
            if ($rowVerify['num'] == 1) {
                $sql = "UPDATE `user` SET verified = 1, verificationToken = '' WHERE uuid = :uuid";
                if ($stmt = $dbh->prepare($sql)) {
                    $stmt->bindParam(":uuid", $uuid);
                    if ($stmt->execute()) {
                        header("location: inloggen.php?verifyStatus=success");
                    } else {
                        header("location: registreren.php?verifyStatus=failed");
                    }
                } else {
                    header("location: registreren.php?verifyStatus=failed");
                }
            } else {
                header("location: registreren.php?verifyStatus=failed");
            }
        } else {
            header("location: registreren.php?verifyStatus=failed");
        }
    } else {
        header("location: registreren.php?verifyStatus=failed");
    }

} else {
    header("location: registreren.php?verifyStatus=failed");
}