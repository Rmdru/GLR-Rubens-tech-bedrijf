<?php
//start session
session_start();

//load config file
require 'config.inc.php';

//vars
$error = "";
$type = $_GET['type'];
$sortBy = $_GET['sortBy'];
$keyword = $_GET['keyword'];
$keywordQuery = "%{$keyword}%";
$maxPrice = $_GET['price'];
$maxPrice = str_replace(",", ".", $maxPrice);
$deliveryTime = $_GET['deliveryTime'];

//get products from db and calculate price
$sqlProduct = "SELECT uuid, oldPrice, discountPercent, title, oldPrice / 100 * (100 - discountPercent) AS price FROM `product` WHERE 1=1 AND type = :type";
//filter options
if (!empty($keyword)) {
    $sqlProduct .= " AND title LIKE :keyword";
}
if (!empty($maxPrice)) {
    $sqlProduct .= " AND oldPrice / 100 * (100 - discountPercent) <= :maxPrice";
}
if (!empty($deliveryTime)) {
    $sqlProduct .= " AND deliveryTime <= :deliveryTime";
}
//sort by options
if ($sortBy == "z-a") {
    $sqlProduct .= " ORDER BY title DESC";
} else if ($sortBy == "priceAsc") {
    $sqlProduct .= " ORDER BY price ASC";
} else if ($sortBy == "priceDesc") {
    $sqlProduct .= " ORDER BY price DESC";
} else {
    $sqlProduct .= " ORDER BY title ASC";
}

//execute query to get products from db
if ($stmtProduct = $dbh->prepare($sqlProduct)) {
    $stmtProduct->bindParam(":type", $type);
    if (!empty($keyword)) {
        $stmtProduct->bindParam(":keyword", $keywordQuery);
    }
    if (!empty($maxPrice)) {
        $stmtProduct->bindParam(":maxPrice", $maxPrice);
    }
    if (!empty($deliveryTime)) {
        $stmtProduct->bindParam(":deliveryTime", $deliveryTime);
    }
    if ($stmtProduct->execute()) {
        if ($stmtProduct->rowCount() > 0) {
            while ($rowProduct = $stmtProduct->fetch(PDO::FETCH_ASSOC)) {
                $uuid = $rowProduct['uuid'];
                $title = $rowProduct['title'];
                $oldPrice = $rowProduct['oldPrice'];
                $oldPrice = str_replace(".", ",", $oldPrice);
                $price = $rowProduct['price'];
                $price = round($price, 2);
                $price = str_replace(".", ",", $price);
                $discountPercent = $rowProduct['discountPercent'];
                echo "<div class='tile tileHover'>";
                    echo "<img class='tileImg centerItem' src='img/product/{$uuid}_1.png' draggable='false' />";
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
        //show error
        } else {
            $error .= "<p class='txt txtCenter'>Er zijn geen resulaten gevonden met deze filters. Of er zijn nog geen producten toegevoegd aan deze categorie</p>";
            echo $error;
        }
    } else {
        $error .= "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
        echo $error;
    }
}