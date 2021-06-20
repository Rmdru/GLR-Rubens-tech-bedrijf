<?php
//start session
session_start();

//remove autologin cookie
setcookie("autologin", "", time() - (86400 * 366), "/", "", true, true);

//remove sessions
unset($_SESSION['uuid']);
unset($_SESSION['ipAddress']);
unset($_SESSION['loginTime']);


//redirect user
header("location: ../index.php");