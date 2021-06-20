<?php
//start session
session_start();

//chars array
$charsArray = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));

//generate random captcha code
for ($i = 0; $i < 5; $i++) {
  $randomChar = array_rand($charsArray);
  if ($i != 0) {
    $captchaCode .= $charsArray[$randomChar];
  } else {
    $captchaCode = $charsArray[$randomChar];
  }
}

//put captcha code in session
$_SESSION['captcha'] = $captchaCode;