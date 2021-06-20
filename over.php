<?php
//start session
session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Over - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--wrapper-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Over Ruben's tech bedrijf</h1>
            <p class="txt">Ruben's tech bedrijf is een webwinkel die gespecialiseerd is in de verkoop van computer hardware. Wij verkopen losse PC componenten, Pre-Build PC's en laptops.</p>
            <!--promotion tiles-->
            <div class="txtCenter">
                <h2 class="title txtCenter">Redenen waarom u bij ons moet kopen</h2>
                <div class="tile130 tileHover">
                    <i class="material-icons verticalCentered" style="font-size: 40px;">local_shipping</i><br />
                    <h3 class="subTitle">Altijd gratis bezorging</h3>
                </div>
                <div class="tile130 tileHover">
                    <i class="material-icons verticalCentered" style="font-size: 40px;">star</i><br />
                    <h3 class="subTitle">Gemiddeld door klanten beoordeeld met een 9</h3>
                </div>
                <div class="tile130 tileHover">
                    <i class="material-icons verticalCentered" style="font-size: 40px;">person</i><br />
                    <h3 class="subTitle">Ons personeel heeft verstand van computers en helpt u graag</h3>
                </div>
            </div>
            <h2 class="title txtCenter marginTop250">Wat wij verkopen</h2>            
            <div class="tile tileHover">
                <img class="tileImg centerItem" src="img/type/gpu.png" draggable="false" />
                <h3 class="subTitle txtCenter">PC componenten</h3>
                <a class="primaryBtn centerItem" href="productCategorie.php?category=pcComponents">Bekijk deze categorie</a><br />
            </div>
            <div class="tile tileHover">
                <img class="tileImg centerItem" src="img/type/gamingPc.png" draggable="false" />
                <h3 class="subTitle txtCenter">Pre-Build PC's</h3>
                <a class="primaryBtn centerItem" href="productCategorie.php?category=preBuildPcs">Bekijk deze categorie</a><br />
            </div>
            <div class="tile tileHover">
                <img class="tileImg centerItem" src="img/type/gamingLaptop.png" draggable="false" />
                <h3 class="subTitle txtCenter">Laptops</h3>
                <a class="primaryBtn centerItem" href="productCategorie.php?category=laptops">Bekijk deze categorie</a><br />
            </div>
        </div>
    </body>
</html>