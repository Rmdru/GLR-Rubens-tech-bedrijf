<?php
//start session
session_start();

//load config file
require "config.inc.php";

//validate data
function validateData($input) {
    $value = $_GET[$input];
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $value = stripslashes($value);
    $value = trim($value);
    $value = htmlentities($value);    
    return $value;
}

//vars
$errors = 0;
$csrfTokenSession = $_SESSION['csrfToken'];
$csrfTokenInput = validateData("csrfToken");
$searchField = validateData("searchField");
$searchFieldQuery = "%{$searchField}%";

//check if csrfToken is valid
if ($csrfTokenInput != $csrfTokenSession) {
    $errors++;
}

//show search results if there are no errors
if ($errors == 0) {
    $sql = "SELECT uuid, oldPrice, discountPercent, title, oldPrice / 100 * (100 - discountPercent) AS price FROM `product` WHERE 1=1 AND brand LIKE :searchFieldBrand OR title LIKE :searchFieldTitle";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(":searchFieldBrand", $searchFieldQuery);
        $stmt->bindParam(":searchFieldTitle", $searchFieldQuery);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $resultAmount = $stmt->rowCount();
                echo "<h2 class='title txtCenter'>{$resultAmount} zoekresultaten voor {$searchField}</h2>";
                echo "<table class='table txt centerItem'>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $uuid = $row['uuid'];
                        $oldPrice = $row['oldPrice'];
                        $oldPrice = round($oldPrice, 2);
                        $oldPrice = str_replace(".", ",", $oldPrice);
                        $price = $row['price'];
                        $price = round($price, 2);
                        $price = str_replace(".", ",", $price);
                        $discountPercent = $row['discountPercent'];
                        $title = $row['title'];
                        echo "<tr><td class='tableCell'><img class='height100px margin0auto displayBlock' src='img/product/{$uuid}_1.png' draggable='false' /></td>
                        <td class='tableCell txtCenter'><a class='link linkHoverColorGreen' href='product.php?uuid={$uuid}'>{$title}</a></td>";
                        if ($discountPercent != 0) {
                            echo "<td class='tableCell'><h4 class='subTitle txtCenter'><span class='lineThrough'>€{$oldPrice}</span> €{$price} <span class='txtColorRed'>-{$discountPercent}%</span></h4></td>";
                        } else {
                            echo "<td class='tableCell'><h4 class='subTitle txtCenter'>€{$price}</h4></td>";
                        }
                        echo "<td class='tableCell'><a href='product?uuid={$uuid}' class='primaryBtn centerItem'>Bekijk dit product</a></td>";
                        echo "</tr>";
                    }
                echo "</table><br />";            
            } else {
                echo "<p class='txt txtCenter'>Er zijn geen zoekresulataten gevonden voor {$searchField}.</p>";                
            }
        }
    }
} else {
    echo "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
}