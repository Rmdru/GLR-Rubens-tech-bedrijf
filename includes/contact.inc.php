<?php
//start session
session_start();

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
$csrfTokenInput = $_POST['csrfToken'];
$csrfTokenSession = $_SESSION['csrfToken'];
$name = validateData("name");
$email = validateData("email");
$subject = validateData("subject");
$msg = validateData("msg");

//validate dat with regex
$namePattern = "/[a-zA-Z]*/";
if (empty($name) OR !preg_match($namePattern, $name)) {
    $errors++;
}

if (empty($email) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors++;
}

if ($csrfTokenInput != $csrfTokenSession) {
    $errors++;
}

$subjectPattern = "/http/i";
if (empty($subject) OR preg_match($subjectPattern, $subject)) {
    $errors++;
}

$msgPattern = "/http/i";
if (empty($msg) OR preg_match($msgPattern, $msg)) {
    $errors++;
}

//if no errors, send mail and redirect
if ($errors == 0) {
    $emailTo = "rmdruijter@rubenderuijter.nl";
    $subjectMail = "Contactformulier ingevuld door {$name}";
    $content = "<!doctype html>
    <html>
        <body style='width: 600px;'>
            <h1>{$name} heeft het contactformulier ingevuld</h1>
            <p>E-mailadres: {$email}<br />Onderwerp: {$subject}<br />Bericht: {$msg}</p>
        </body>
    </html>";
    $headers = "From: {$name} <{$email}>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    if(mail($emailTo, $subjectMail, $content, $headers)) {
        header("location: ../contact.php?status=success");
    } else {
        header("location: ../contact.php?status=failed2");
    }
} else {
    header("location: ../contact.php?status=failed1");
}