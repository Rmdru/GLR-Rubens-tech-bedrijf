<?php
//start session
session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <?php require "includes/head.inc.php"; ?>
        <title>Winkelwagen - Ruben's tech bedrijf</title>
    </head>
    <body onload="loadShoppingCart();">
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <div class="wrapperTop">
            <h1 class="title txtCenter">Winkelwagen</h1>
            <div id="shoppingCartResponse"></div>
        </div>
    </body>
</html>