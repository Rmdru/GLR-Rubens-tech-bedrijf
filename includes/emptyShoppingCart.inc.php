<?php
//start session
session_start();

//remove shopping cart session
session_unset($_SESSION['shoppingCart']);

//add delay
sleep(0.5);