<?php
//start session
session_start();

//load config file
require "config.inc.php";

//validate data
function validateData($input) {
    $value = $_GET[$input];
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $value = stripslashes($value);
    $value = trim($value);
    $value = htmlentities($value);    
    return $value;
}

//generate uuid
function uuid() {
    $data = openssl_random_pseudo_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf("%s%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
}

//vars
$errors = 0;
$uuidProduct = $_GET['uuidProduct'];
$uuidUser = $_SESSION['uuid'];
$csrfTokenUrl = $_GET['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$stars = validateData("stars");
$title = validateData("title");
$description = validateData("description");
$anonymous = validateData("anonymous");
$uuidReview = uuid();
$date = date("Y-m-d");

if ($csrfTokenSession != $csrfTokenUrl) {
    $errors++;
}

if (empty($stars) OR empty($title) OR empty($description) OR !isset($_SESSION['uuid']) OR empty($uuidProduct)) {
    $errors++;
}

if ($errors == 0) {
    $sql = "INSERT INTO `Review` VALUES (:uuidReview, :uuidProduct, :uuidUser, :date, :anonymous, :stars, :title, :description)";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":uuidReview", $uuidReview);
        $stmt->bindParam(":uuidProduct", $uuidProduct);
        $stmt->bindParam(":uuidUser", $uuidUser);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":anonymous", $anonymous);
        $stmt->bindParam(":stars", $stars);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":description", $description);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Zorg dat je alle velden correct invult!</p>";    
        }
    } else {
        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Zorg dat je alle velden correct invult!</p>";        
    }
} else {
    echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Zorg dat je alle velden correct invult!</p>";      
}