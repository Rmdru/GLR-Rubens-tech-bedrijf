<?php
//start session
session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Home - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--promotion banner-->
        <div class="promotionBanner">
            <div class="wrapper">
                <div class="promotionBannerBlockBottomCenter">
                    <h1 class="title txtCenter">Kies voor ultieme performance met de AMD Ryzen 5000 processors</h1>
                    <h3 class='txtColorRed subTitle txtCenter'>Alleen nu 20% korting op alle AMD Ryzen 5000 processors!</h3>
                    <h3 class="subTitle txtCenter"><i class="material-icons verticalCentered">local_shipping</i> Vandaag besteld = morgen gratis bezorgd</h3>
                    <a href="productenOverzicht.php?type=cpu" class="primaryBtn centerItem">Bekijk producten</a>
                </div>
            </div>
        </div>
        <div class="wrapper">
            <!--comparision-->
            <div class="comparision txtCenter">
                <h1 class="title">Betaal alleen voor de kracht die je nodig hebt</h1>
                <div class="comparisionColumn">
                    <h3 class="subTitle">Ruben's tech bedrijf Budget PC</h3>
                    <p class="comparisionSpec txt">€ 500</p>
                    <p class="comparisionSpec txt"><i class="material-icons verticalCentered">local_shipping</i> Vandaag besteld = morgen gratis bezorgd</p>
                    <p class="comparisionSpec txt">AMD Ryzen 3 3300X</p>
                    <p class="comparisionSpec txt">Nvidia GeForce GTX 1650</p>
                    <p class="comparisionSpec txt">8GB werkgeheugen</p>
                    <p class="comparisionSpec txt">120GB SSD</p>
                    <p class="comparisionSpec txt">500GB HDD</p>
                    <p class="comparisionSpec txt">500W voeding</p>
                    <p class="comparisionSpec txt">Samengesteld & gebouwd door Ruben's tech bedrijf</p>
                    <a href="product.php?uuid=62e4b708-77ee-4e79-9ca82c56c517" class="comparisionBtn secondaryBtn centerItem">Bekijk PC</a>
                </div>
                <div class="comparisionColumn">
                    <p class="comparisionPopularTag txt">Populairste keuze</p>
                    <h3 class="subTitle">Ruben's tech bedrijf Mid-range PC</h3>
                    <p class="comparisionSpec txt">€ 1000</p>
                    <p class="comparisionSpec txt"><i class="material-icons verticalCentered">local_shipping</i> Vandaag besteld = morgen gratis bezorgd</p>
                    <p class="comparisionSpec txt">AMD Ryzen 5 5600X</p>
                    <p class="comparisionSpec txt">Nvidia GeForce RTX 3060 Ti</p>
                    <p class="comparisionSpec txt">16GB werkgeheugen</p>
                    <p class="comparisionSpec txt">250GB SSD</p>
                    <p class="comparisionSpec txt">1000GB HDD</p>
                    <p class="comparisionSpec txt">1000W voeding</p>
                    <p class="comparisionSpec txt">Samengesteld & gebouwd door Ruben's tech bedrijf</p>
                    <a href="product.php?uuid=f5739347-82d9-40d0-b70fa3f784ab" class="comparisionBtn primaryBtn centerItem">Bekijk PC</a>
                </div>     
                <div class="comparisionColumn">
                    <h3 class="subTitle">Ruben's tech bedrijf High-end PC</h3>
                    <p class="comparisionSpec txt">€ 2000</p>
                    <p class="comparisionSpec txt"><i class="material-icons verticalCentered">local_shipping</i> Vandaag besteld = morgen gratis bezorgd</p>
                    <p class="comparisionSpec txt">AMD Ryzen 7 5800X</p>
                    <p class="comparisionSpec txt">Nvidia GeForce RTX 3080</p>
                    <p class="comparisionSpec txt">32GB werkgeheugen</p>
                    <p class="comparisionSpec txt">500GB SSD</p>
                    <p class="comparisionSpec txt">2000GB HDD</p>
                    <p class="comparisionSpec txt">1200W voeding</p>
                    <p class="comparisionSpec txt">Samengesteld & gebouwd door Ruben's tech bedrijf</p>
                    <a href="product.php?uuid=cdecc968-0152-41e2-96ef283d17c6" class="comparisionBtn secondaryBtn centerItem">Bekijk PC</a>
                </div>     
                <div class="comparisionColumn">
                    <h3 class="subTitle">Ruben's tech bedrijf Extreme PC</h3>
                    <p class="comparisionSpec txt">€ 3000</p>
                    <p class="comparisionSpec txt"><i class="material-icons verticalCentered">local_shipping</i> Vandaag besteld = morgen gratis bezorgd</p>
                    <p class="comparisionSpec txt">AMD Ryzen 9 5950X</p>
                    <p class="comparisionSpec txt">Nvidia GeForce RTX 3090</p>
                    <p class="comparisionSpec txt">64GB werkgeheugen</p>
                    <p class="comparisionSpec txt">1000GB SSD</p>
                    <p class="comparisionSpec txt">3000GB HDD</p>
                    <p class="comparisionSpec txt">1300W voeding</p>
                    <p class="comparisionSpec txt">Samengesteld & gebouwd door Ruben's tech bedrijf</p>
                    <a href="product.php?uuid=7bd1e1a3-b386-4789-ac74bf1a71d6" class="comparisionBtn secondaryBtn centerItem">Bekijk PC</a>
                </div>
            </div>
            <!--promotion tiles-->
            <div class="promotionTiles txtCenter">
                <h1 class="title">Bij ons kiest u alleen voor hoge kwaliteit</h1>
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
        </div>
    </body>
</html>