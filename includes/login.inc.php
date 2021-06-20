<?php
//session start
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

//vars
$errors = 0;
$email = validateData("email");
$psw = $_GET['psw'];
$autologin = validateData("autologin");
$csrfTokenInput = $_GET['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];

//validate data
if (empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul het e-mailadres op de juiste manier in</p><br />";
}

if (empty($psw)) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul het juiste wachtwoord in dat bij het ingevulde e-mailadres hoort</p><br />";
}

if ($csrfTokenInput != $csrfTokenSession) {
    $errors .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
}

//execute query and log user in or show error
if (empty($errors)) {
    $sql = "SELECT uuid, email, psw FROM `user` WHERE email = :email";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $validPsw = password_verify($psw, $result['psw']);
            if ($validPsw) {
                $uuid = $result['uuid'];
                //set sessions
                $_SESSION['loginTime'] = time();
                $_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['uuid'] = $uuid;

                //regenerate session id
                session_regenerate_id();

                //set optional cookie for autologin
                if ($autologin == 1) {
                    setcookie("autologin", $uuid, time() + (86400 * 366), "/", "", true, true);
                }
                
                echo "redirectAccountDashboard";
            } else {
                $error .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Het ingevoerde wachtwoord is onjuist. Probeer het opnieuw. Als u uw wachtwoord bent vergeten kan je die <a href='wachtwoordWijzigingAanvragen.php' class='link'>hier wijzigen</a>.</p><br />";
                echo $error;
            }
        } else {
            $error .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Het ingevoerde e-mailadres is nog niet geregistreerd bij ons. <a href='registreren.php' class='link'>Registreer je hier</a> of probeer een ander e-mailadres.</p><br />";
            echo $error;
        }
    } else {
        $error .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
        echo $error;
    }
} else {
    $error .= "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p><br />";
    echo $error;
}