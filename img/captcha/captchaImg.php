<?php
//start session
session_start();

//generate captcha img
$frame = imagecreatetruecolor(155, 37);
$bg = imagecolorallocate($frame, rand(0, 255), rand(0, 255), rand(0, 255));
imagefill($frame, 0, 0, $bg);
$txtColor = imagecolorallocate($frame, rand(0, 255), rand(0, 255), rand(0, 255));
imagestring($frame, 5, 55, 10, $_SESSION['captcha'], $txtColor);
header("Content-type: image/jpeg");
imagejpeg($frame);
