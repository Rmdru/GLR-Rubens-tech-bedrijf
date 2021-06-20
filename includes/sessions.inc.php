<?php
//start session
session_start();

//check if the session time is valid
if ((time() - 3600) > $_SESSION['loginTime']) {
    unset($_SESSION['uuid']);
    unset($_SESSION['ipAddress']);
    unset($_SESSION['loginTime']);

    setcookie("autologin", "", time() - (84600 * 366), "/", "rubenderuijter.nl", true, true);

    header("location: inloggen.php?loggedOut=2");
}

//check if user has not changed his ip address
if ($_SESSION['ipAddress'] != $_SERVER['REMOTE_ADDR']) {
    unset($_SESSION['uuid']);
    unset($_SESSION['ipAddress']);
    unset($_SESSION['loginTime']);

    setcookie("autologin", "", time() - (84600 * 366), "/", "rubenderuijter.nl", true, true);

    header("location: inloggen.php?loggedOut=1");
}

//check if user is logged in
if (!isset($_SESSION['uuid'])) {
    unset($_SESSION['uuid']);
    unset($_SESSION['ipAddress']);
    unset($_SESSION['loginTime']);

    setcookie("autologin", "", time() - (84600 * 366), "/", "rubenderuijter.nl", true, true);

    header("location: inloggen.php");
}