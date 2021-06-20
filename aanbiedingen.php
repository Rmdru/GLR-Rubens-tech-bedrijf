<?php
//start session
session_start();

//load config file
require "includes/config.inc.php";
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Aanbiedingen - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <div class="wrapperTop">
            <h1 class="title txtCenter">Aanbiedingen</h1>
            <!--products-->
            <?php
            //run query to get products with discount from db
            $sql = "SELECT uuid, oldPrice, discountPercent, title, oldPrice / 100 * (100 - discountPercent) AS price FROM `product` WHERE 1=1 AND discountPercent > 0 ORDER BY discountPercent DESC";
            if ($stmt = $dbh->prepare($sql)) {
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($stmt->execute()) {
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $uuid = $row['uuid'];
                            $title = $row['title'];
                            $oldPrice = $row['oldPrice'];
                            $oldPrice = str_replace(".", ",", $oldPrice);
                            $price = $row['price'];
                            $price = round($price, 2);
                            $price = str_replace(".", ",", $price);
                            $discountPercent = $row['discountPercent'];
                            echo "<div class='tile tileHover'>";
                                echo "<img class='tileImg centerItem' src='img/product/{$uuid}.png' draggable='false' />";
                                echo "<h3 class='title txtCenter'>{$title}</h3>";
                                if ($discountPercent != 0) {
                                    echo "<h4 class='subTitle txtCenter'><span class='lineThrough'>€{$oldPrice}</span> €{$price} <span class='txtColorRed'>-{$discountPercent}%</span></h4>";
                                } else {
                                    $price = str_replace(".", ",", $price);
                                    echo "<h4 class='subTitle txtCenter'>€{$price}</h4>";
                                }
                                echo "<a class='primaryBtn centerItem' href='product.php?uuid={$uuid}'>Bekijk dit product</a><br />";
                            echo "</div>";
                        }
                    } else {
                        echo "<p class='txt txtCenter'>Er zijn momenteel geen aanbiedingen.</p>";
                    }
                } else {
                    echo "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
                }
            } else {
                echo "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
            }
            ?>
        </div>
    </body>
</html>