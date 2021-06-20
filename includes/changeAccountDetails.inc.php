<?php
//start session
session_start();

//load config file
require "config.inc.php";

//validate data
function validateData($input) {
    $value = $_POST[$input];
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $value = stripslashes($value);
    $value = trim($value);
    $value = htmlentities($value);    
    return $value;
}

//vars
$errors = 0;
$uuid = $_SESSION['uuid'];
$email = validateData("email");
$pswUnencrypted = $_POST['psw'];
$psw = password_hash($pswUnencrypted, PASSWORD_BCRYPT, array("cost" => 12));
$pswRepeatUnencrypted = $_POST['pswRepeat'];
$firstName = validateData("firstName");
$insertion = validateData("insertion");
$lastName = validateData("lastName");
$city = validateData("city");
if ($city == "Geen") {
    $city = "";
}
$postalCode = validateData("postalCode");
$postalCode = str_replace(" ", "", $postalCode);
if ($postalCode == "Geen") {
    $postalCode = "";
}
$address = validateData("address");
if ($address == "Geen") {
    $address = "";
}
$housenumber = validateData("housenumber");
if ($housenumber == "Geen") {
    $housenumber = "";
}
$bank = validateData("bank");
$paymentMethod = validateData("paymentMethod");
$csrfTokenInput = $_GET['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];

//validate data
$firstNamePattern = "/[a-zA-Z]*/";
if (empty($firstName) OR !preg_match($firstNamePattern, $firstName)) {
    $errors++;
}

$lastNamePattern = "/[a-zA-Z]*/";
if (empty($lastName) OR !preg_match($lastNamePattern, $lastName)) {
    $errors++;
}

if (!empty($city)) {
    $cityPattern = "/[a-zA-Z]*/";
    if (empty($city) OR !preg_match($cityPattern, $city)) {
        $errors++;
    }
}

if (!empty($postalCode)) {
    $postalCodePattern = "/[0-9]{4}[a-zA-Z]{2}/";
    if (!preg_match($postalCodePattern, $postalCode)) {
        $errors++;
    }
}

if (empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors++;
}

if (empty($housenumber)) {
    $housenumber = 0;
}

if ($csrfTokenInput != $csrfTokenSession) {
    $errors++;
}

if (!empty($pswRepeatUnencrypted) OR !empty($pswUnencrypted)) {
    if ($pswUnencrypted != $pswRepeatUnencrypted) {
        $errors++;
    }
}

//update data
if ($errors == 0) {
    $sql = "UPDATE `user` SET  email = :email, ";
    if (!empty($pswUnencrypted)) {
        $sql .= "psw = :psw, ";
    }
    $sql .= "firstName = :firstName, insertion = :insertion, lastName = :lastName, city = :city, postalCode = :postalCode, address = :address, housenumber = :housenumber, bank = :bank, paymentMethod = :paymentMethod WHERE uuid = :uuid";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":email", $email);
        if (!empty($pswUnencrypted)) {
            $stmt->bindParam(":psw", $psw);            
        }
        $stmt->bindParam(":firstName", $firstName);
        $stmt->bindParam(":insertion", $insertion);
        $stmt->bindParam(":lastName", $lastName);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":postalCode", $postalCode);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":housenumber", $housenumber);
        $stmt->bindParam(":bank", $bank);
        $stmt->bindParam(":paymentMethod", $paymentMethod);
        $stmt->bindParam(":uuid", $uuid);
        if ($stmt->execute()) {
            header("location: ../mijnAccount.php?changeAccountDetailsStatus=success");
        } else {
            header("location: ../mijnAccount.php?changeAccountDetailsStatus=failed");
        }
    } else {
        header("location: ../mijnAccount.php?changeAccountDetailsStatus=failed");
    }
} else {
    header("location: ../mijnAccount.php?changeAccountDetailsStatus=failed");
}