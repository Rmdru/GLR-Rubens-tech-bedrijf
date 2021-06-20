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

//curl handle
function curlHandle($url) {        
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $jsonData = curl_exec($ch);

    curl_close($ch);
    
    $data = json_decode($jsonData);

    return $data;
}

//vars
$email = validateData("email");
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];

//validate data
if (empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location: ../wachtwoordWijzigingAanvragen.php?status=failed");
}

if ($csrfTokenInput != $csrfTokenSession) {
    header("location: ../wachtwoordWijzigingAanvragen.php?status=failed");
}

//generate random token for email verification
$charsArray = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));

for ($i = 0; $i < 33; $i++) {
    $randomChar = array_rand($charsArray);
    if ($i != 0) {
        $verificationToken .= $charsArray[$randomChar];
    } else {
        $verificationToken = $charsArray[$randomChar];
    }
}

//check if email exist and send mail
    $sql = "SELECT uuid, email FROM `user` WHERE email = :email";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $uuid = $result['uuid'];
            $email = $result['email'];
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            $ipAddressApi = curlHandle("https://ip-api.io/api/json/{$ipAddress}");
            $country = $ipAddressApi->country;
            if (empty($country)) {
                $country = "Onbekend";
            }
            $city = $ipAddressApi->city;
            if (empty($city)) {
                $city = "Onbekend";
            }
            $dateTime = date("d-m-Y H:i");
            
            $_SESSION['verificationToken'] = $verificationToken;

            $subject = "Wachtwoord wijziging aangevraagd - Ruben's tech bedrijf";
            $content = "<!doctype html>
            <html>
                <body style='width: 600px;'>
                    <h1>Er is een wijziging van het wachtwoord aangevraagd.</h1>
                    <h3>Het gaat om het account dat geregistreerd met het volgende e-mailadres: {$email}.</h3>
                    <p>De gegevens van de aanvrager:<br />Land: {$country}<br />Plaats: {$city}<br />Datum en tijd: {$dateTime}<br />IP-adres: {$ipAddress}<br /></p>
                    <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=wachtwoordWijzigen.php&verificationToken={$verificationToken}&uuid={$uuid}'>Via deze link kan je jouw wachtwoord wijzigen</a>
                    <p>Heb jij geen nieuw wachtwoord aangevraagd of vertrouw je het niet?</p>
                    <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>Neem dan z.s.m. hier contact met ons op</a>
                    <p>Lukt het niet of heb je vragen? Ook dan staan we uiteraard graag voor je klaar.</p>
                    <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>Neem hier contact op</a>
                    <p>De e-mail is automatisch verzonden, reacties op deze e-mail zullen niet beantwoord worden. Als u contact op wilt nemen <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>kan dat hier</a>.</p>
                </body>
            </html>";
            $headers = "From: Ruben's tech bedrijf <noReply@rubenstechbedrijf.nl>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=utf-8\r\n";
            mail($email, $subject, $content, $headers);

            header("location: ../wachtwoordWijzigingAanvragen.php?status=success");
        } else {
            header("location: ../wachtwoordWijzigingAanvragen.php?status=unknownEmail");
        }
    }