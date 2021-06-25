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

//generate uuid
function uuid() {
    $data = openssl_random_pseudo_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf("%s%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
}

//vars
$errors = 0;
if (isset($_SESSION['uuid'])) {
    $uuidUser = $_SESSION['uuid'];
} else {
    $uuidUser = NULL;
}
$uuidOrder = uuid();
$shoppingCart = $_SESSION['shoppingCart'];
$uuidProducts = implode(";", $shoppingCart);
$shoppingCart = array_count_values($shoppingCart);
$maxDeliveryTime = 0;
foreach ($shoppingCart as $uuidProduct => $amount) {
    $sqlProduct = "SELECT oldPrice, discountPercent, title, oldPrice / 100 * (100 - discountPercent) AS price, MAX(deliveryTime) AS maxDeliveryTime FROM `product` WHERE uuid = :uuidProduct";
    if ($stmtProduct = $dbh->prepare($sqlProduct)) {
        $stmtProduct->bindParam(":uuidProduct", $uuidProduct);
        if ($stmtProduct->execute()) {
            $resultProduct = $stmtProduct->fetch(PDO::FETCH_ASSOC);
            $price = $resultProduct['price'];
            if ($resultProduct['maxDeliveryTime'] > $maxDeliveryTime) {
                $maxDeliveryTime = $resultProduct['maxDeliveryTime'];
            }
            $discountPercent = $resultProduct['discountPercent'];
            $subTotalPrice = $price * $amount;
            $totalPrice += $subTotalPrice;
            $totalPrice = round($totalPrice, 2);
        }
    }
}
$dateOrderPlaced = date("Y-m-d");
$null = NULL;
$status = 1;
$email = validateData("email");
if ($email == "Geen") {
    $email = "";
}
$firstName = validateData("firstName");
if ($firstName == "Geen") {
    $firstName = "";
}
$insertion = validateData("insertion");
$lastName = validateData("lastName");
if ($lastName == "Geen") {
    $lastName = "";
}
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

if (empty($housenumber)) {
    $housenumber = 0;
}

if ($csrfTokenInput != $csrfTokenSession) {
    $errors++;
}

//if no errors add order to db, if there are errors redirect the user
if ($errors == 0) {
    //insert data into db
    $sql = "INSERT INTO `order` VALUES (:orderId, :uuidUser, :uuidProducts, :price, :status, :deliveryTime, :dateOrderPlaced, :dateOrderDelivered)";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":orderId", $uuidOrder);
        $stmt->bindParam(":uuidUser", $uuidUser);
        $stmt->bindParam(":uuidProducts", $uuidProducts);
        $stmt->bindParam(":price", $totalPrice);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":deliveryTime", $maxDeliveryTime);
        $stmt->bindParam(":dateOrderPlaced", $dateOrderPlaced);
        $stmt->bindParam(":dateOrderDelivered", $null);

        if ($stmt->execute()) {
            //get product title
            $allProducts = "";
            $i = 1;
            foreach($shoppingCart as $product => $amount) {
                $sqlProduct = "SELECT uuid,title FROM `product` WHERE uuid = :product";
                if ($stmtProduct = $dbh->prepare($sqlProduct)) {
                    $stmtProduct->bindParam(":product", $product);
                    if ($stmtProduct->execute()) {
                        $result = $stmtProduct->fetch(PDO::FETCH_ASSOC);
                        $uuidProduct = $result['uuid'];
                        $title = $result['title'];
                        if ($i > 1) {
                            $allProducts .= ", ";
                        }
                        $allProducts .= "<a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=product.php&uuid={$uuidProduct}'>" . $title . "</a> ({$amount}x)";
                    }
                }
                $i++;
            }
            
            //send email
            $totalPrice = str_replace(".", ",", $totalPrice);
            if ($maxDeliveryTime == -1) {
                $maxDeliveryDate = "Niet beschikbaar";
            } else {
                $maxDeliveryDate = date("d-m-Y", strtotime("+{$maxDeliveryTime} day"));
                echo $maxDeliveryDate;
            }
            $dateOrderPlaced = date("d-m-Y", strtotime($dateOrderPlaced));
            $subject = "Bedankt voor je bestelling bij Ruben's tech bedrijf!";
            $content = "<!doctype html>
            <html>
                <body style='width: 600px;'>
                    <h1>Bedankt voor je bestelling bij Ruben's tech bedrijf!</h1>
                    <h3>Wij hebben je bestelling succesvol ontvangen en gaan er direct mee aan de slag om de bestelling zo snel mogelijk te verzenden.</h3>
                    <h3>Bestelgegevens:</h3>
                    <p><b>Ordernummer:</b> {$uuidOrder}<br />
                    <b>Product(en):</b> {$allProducts}<br />
                    <b>Totaalprijs:</b> €{$totalPrice}<br />
                    <b>Verwachte leverdatum:</b> {$maxDeliveryDate}<br />
                    <b>Datum bestelling geplaatst:</b> {$dateOrderPlaced}</p>
                    <p>Als je je bestelling wilt annuleren, retourneren of een beroep wilt doen op je recht op garantie, kun je <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>hier contact opnemen</a>.</p>
                    <p>De e-mail is automatisch verzonden, reacties op deze e-mail zullen niet beantwoord worden. Als u contact op wilt nemen <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>kan dat hier</a>.</p>
                </body>
            </html>";
            $headers = "From: Ruben's tech bedrijf <noReply@rubenstechbedrijf.nl>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($email, $subject, $content, $headers);

            //empty shopping cart
            unset($_SESSION['shoppingCart']);

            //redirect user
            if (isset($_SESSION['uuid'])) {
                header("location: ../mijnAccount.php");
            } else {
                header("location: ../index.php");
            }
        } else {
            header("location: ../winkelwagen.php?placeOrderStatus=failed");
        }
    } else {
        header("location: ../winkelwagen.php?placeOrderStatus=failed");
    }
} else {
    header("location: ../winkelwagen.php?placeOrderStatus=failed");
}