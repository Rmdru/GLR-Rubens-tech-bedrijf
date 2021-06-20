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

//vars
$errors = "";
$null = NULL;
$verified = 0;
$firstName = validateData("firstName");
$insertion = validateData("insertion");
$lastName = validateData("lastName");
if (!empty($insertion)) {
    $fullName = "{$firstName} {$insertion} {$lastName}";
} else {
    $fullName = "{$firstName} {$lastName}";
}
$email = validateData("email");
$pswUnencrypted = $_GET['psw'];
$pswRepeatUnencrypted = $_GET['pswRepeat'];
$psw = password_hash($pswUnencrypted, PASSWORD_BCRYPT, array("cost" => 12));
$captchaInput = $_GET['captcha'];
$captchaSession = $_SESSION['captcha'];
$csrfTokenInput = $_GET['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$uuid = uuid();

//validate data
$firstNamePattern = "/[a-zA-Z]*/";
if (empty($firstName) OR !preg_match($firstNamePattern, $firstName)) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul de voornaam op de juiste manier in</p><br />";
}

$lastNamePattern = "/[a-zA-Z]*/";
if (empty($lastName) OR !preg_match($lastNamePattern, $lastName)) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul de achternaam op de juiste manier in</p><br />";
}

if (empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul het e-mailadres op de juiste manier in</p><br />";
}

if ($csrfTokenInput != $csrfTokenSession) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
}

if ($captchaInput != $captchaSession) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul de CAPTCHA op de juiste manier in.</p><br />";
}

if ($pswUnencrypted != $pswRepeatUnencrypted) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> De twee wachtwoorden zijn niet het zelfde, probeer het opnieuw</p><br />";
}

//insert data into db and send email
if (empty($errors)) {
    $sqlEmail = "SELECT email FROM `user` WHERE email = :email";
    if ($stmtEmail = $dbh->prepare($sqlEmail)) {
        $stmtEmail->bindParam(":email", $email);
        $stmtEmail->execute();
        if ($stmtEmail->rowCount() == 0) {
            $sql = "INSERT INTO user (uuid, email, psw, firstName, insertion, lastName, city, postalCode, address, housenumber, bank, paymentMethod, verificationToken, verified) VALUES (:uuid, :email, :psw, :firstName, :insertion, :lastName, :city, :postalCode, :address, :housenumber, :bank, :paymentMethod, :verificationToken, :verified)";
            if ($stmt = $dbh->prepare($sql)) {
                $stmt->bindParam(":uuid", $uuid);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":psw", $psw);
                $stmt->bindParam(":firstName", $firstName);
                $stmt->bindParam(":insertion", $insertion);
                $stmt->bindParam(":lastName", $lastName);
                $stmt->bindParam(":city", $null);
                $stmt->bindParam(":postalCode", $null);
                $stmt->bindParam(":address", $null);
                $stmt->bindParam(":housenumber", $null);
                $stmt->bindParam(":bank", $null);
                $stmt->bindParam(":paymentMethod", $null);
                $stmt->bindParam(":verificationToken", $verificationToken);
                $stmt->bindParam(":verified", $verified);

                if ($stmt->execute()) {
                    $subject = "{$fullName}, welkom bij Ruben's tech bedrijf!";
                    $content = "<!doctype html>
                    <html>
                        <body style='width: 600px;'>
                            <h1>{$fullName}, welkom bij Ruben's tech bedrijf!</h1>
                            <h3>Je account is succesvol aangemaakt</h3>
                            <p>Om in te loggen op je account hoef je alleen je e-mailadres nog maar te verifiëren, dat kun je doen door op de link hieronder te klikken.</p>
                            <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=emailVerifieren.php&verificationToken={$verificationToken}&uuid={$uuid}'>E-mailadres verifiëren</a>
                            <h3 class='subTitle txtCenter'>Heb jij dit account niet aangemaakt?</h3>
                            <p>Dan heeft waarschijnlijk iemand anders jouw e-mailadres gebruikt voor je account. In dat geval kan je je account hieronder verwijderen.</p>
                            <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=accountVerwijderen.php&uuid={$uuid}'>Account verwijderen</a>
                            <p>De e-mail is automatisch verzonden, reacties op deze e-mail zullen niet beantwoord worden. Als u contact op wilt nemen <a href='https://rubenderuijter.nl/portfolioItem?id=68f23084-9e3a-4b68-a12f3684a8de&redirectTo=contact.php'>kan dat hier</a>.</p>
                        </body>
                    </html>";
                    $headers = "From: Ruben's tech bedrijf <noReply@rubenstechbedrijf.nl>\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
                    mail($email, $subject, $content, $headers);

                    echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Succesvol geregistreerd. Check je mailbox om je e-mailadres te verifiëren en je account te gebruiken.</p>";
                } else {
                    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
                    echo $errors;
                }
            } else {
                $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
                echo $errors;
            }
        } else {
            $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er bestaat al een account met dit e-mailadres, probeer een ander e-mailadres te gebruiken.</p><br />";
            echo $errors;
        }
    }   
} else {
    echo $errors;
}