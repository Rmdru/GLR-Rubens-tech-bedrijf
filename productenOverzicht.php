<?php
//start session
session_start();

//load config file
require "includes/config.inc.php";

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
$error = "";
$type = validateData("type");

//validate data
if (empty($type)) {
    $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat deze subcategorie niet of er zijn nog geen producten toegevoegd aan deze subcategorie.</p>";
    $typeName = "Error: mogelijk bestaat deze categorie niet";
}

$sqlType = "SELECT name FROM `productType` WHERE id = :type";
if ($stmtType = $dbh->prepare($sqlType)) {
    $stmtType->bindParam(":type", $type);
    $stmtType->execute();
    $rowType = $stmtType->fetch(PDO::FETCH_ASSOC);
    $typeName = $rowType['name'];
}
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title><?php echo $typeName; ?> - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body onload="loadProducts('<?php echo $type; ?>', 'a-z');">
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <div class="wrapperTop">
            <h1 class="title txtCenter"><?php echo $typeName; ?></h1>
            <!--sort options-->
            <p class="txt txtCenter"><i class="material-icons verticalCentered">sort</i> Sorteren op: </p>
            <select onchange="loadProducts('<?php echo $type; ?>', this.value, document.getElementById('keyword').value, document.getElementById('price').value, document.getElementById('deliveryTime').value);" class="inputField width20" id="sortBy">
                <option value="a-z" selected="selected">A-Z</option>
                <option value="z-a">Z-A</option>
                <option value="priceAsc">Prijs laag - hoog</option>
                <option value="priceDesc">Prijs hoog - laag</option>
            </select>
            <!--filter options-->
            <div class="column14 margin10 borderSecondary">
                <h3 class="subTitle txtCenter"><i class="material-icons verticalCentered">filter_list</i> Filteren</h3>
                <p class="txt txtCenter"><b>Trefwoord</b></p>
                <input type="text" placeholder="Trefwoord" class="inputField" id="keyword" onchange="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, this.value, document.getElementById('price').value, document.getElementById('deliveryTime').value);" onkeyup="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, this.value, document.getElementById('price').value, document.getElementById('deliveryTime').value);" />
                <p class="txt txtCenter"><b>Maximale prijs (€)</b></p>
                <input type="number" min="0" step="0.01" placeholder="Prijs" class="inputField" id="price" onchange="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, document.getElementById('keyword').value, this.value, document.getElementById('deliveryTime').value);" onkeyup="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, document.getElementById('keyword').value, this.value, document.getElementById('deliveryTime').value);" />
                <p class="txt txtCenter"><b>Maximale levertijd</b></p>
                <input type="range" class="slider" min="1" max="14" value="14" id="deliveryTime" oninput="showDeliveryTimeSliderValue(this.value);" onchange="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, document.getElementById('keyword').value, document.getElementById('price').value, this.value);" onkeyup="loadProducts('<?php echo $type; ?>', document.getElementById('sortBy').value, document.getElementById('keyword').value, document.getElementById('price').value, this.value);" />
                <p class="txt txtCenter"><span id="deliveryTimeSliderValue">14 of meer</span> dag(en)</p>
                <button class="secondaryBtn" onclick="clearFilter('<?php echo $type; ?>', document.getElementById('sortBy'));"><i class="material-icons verticalCentered">delete</i> Filters wissen</button>
            </div>
            <!--products-->
            <div class="column80Right">    
                <div id="products"></div>
            </div>
        </div>
    </body>
</html>