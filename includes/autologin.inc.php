<?php
//set session if autologin is enabled
if (isset($_COOKIE['autologin'])) {
    $_SESSION['uuid'] = $_COOKIE['autologin'];
    $_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['loginTime'] = time();
}