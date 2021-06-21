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
        <?php
        require "includes/head.inc.php";
        
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
        $uuidProduct = validateData("uuid");

        //validate data
        if (empty($uuidProduct)) {
            $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat dit product niet.</p>";
            $title = "Error: mogelijk bestaat dit product niet";
        }

        if (empty($error)) {
            $sqlProduct = "SELECT title, description, oldPrice, discountPercent, stock, deliveryTime FROM `product` WHERE uuid = :uuid";
            if ($stmtProduct = $dbh->prepare($sqlProduct)) {
                $stmtProduct->bindParam(":uuid", $uuidProduct);
                $stmtProduct->execute();
                $rowProduct = $stmtProduct->fetch(PDO::FETCH_ASSOC);
                $title = $rowProduct['title'];
                $description = $rowProduct['description'];
                $price = $rowProduct['oldPrice'];
                $discountPercent = $rowProduct['discountPercent'];
                $stock = $rowProduct['stock'];
                $deliveryTime = $rowProduct['deliveryTime'];
                if ($stmtProduct->rowCount() > 0) {
        ?>
        <title><?php echo $title; ?> - Ruben's tech bedrijf</title>
    </head>
    <body onload="calculatePrice(1);loadReviews('<?php echo $uuidProduct; ?>', 0);loadFile('next');">
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <div class="wrapperTop">
            <!--show product info-->
            <h1 class="title txtCenter"><?php echo $title; ?></h1>
            <div class="column50">
                <!--image carousel-->
                <?php
                for ($i = 1;$i < 10;$i++) {
                    $imgSrc = "img/product/{$uuidProduct}_{$i}.png";
                    if (file_exists($imgSrc)) {
                        echo "<img src='{$imgSrc}' class='centerItem width60 carouselImg' draggable='false' />";
                        if ($i > 1) {
                            $multipleImgs = 1;
                        }
                    }
                }
                if ($multipleImgs) {
                    echo "<br /><div class='centerItem'>";
                    ?>
                        <button class="secondaryBtn" onclick="loadFile('previous');" id="previousBtn"><i class="material-icons verticalCentered">keyboard_arrow_left</i></button>
                        <button class="secondaryBtn marginLeft20" onclick="loadFile('next');" id="nextBtn"><i class="material-icons verticalCentered">keyboard_arrow_right</i></button>
                    <?php
                    echo "</div>";
                }
                ?>
            </div>
            <div class="column50">
            <?php
                echo "<p class='txt'>{$description}</p>";
                //calculate price
                if ($discountPercent != 0) {
                    echo "<h3 class='title'><span class='txtColorRed'>Alleen nu {$discountPercent}% korting op dit product!</span><br /><span class='lineThrough'>€<span id='oldPrice'></span></span> €<span id='price'></span></h3>";
                    echo "<script>
                    function calculatePrice(amount) {
                        var oldPrice = {$price};
                        oldPrice = oldPrice * amount;
                        var discountSum = {$discountPercent} / 100;
                        var discountSum = 1 - discountSum;
                        var price = oldPrice * discountSum;
                        price = price.toFixed(2);
                        price = price.toString();
                        price = price.replace('.', ',');
                        oldPrice = oldPrice.toFixed(2);
                        oldPrice = oldPrice.toString();
                        oldPrice = oldPrice.replace('.', ',');
                        document.getElementById('oldPrice').innerHTML = oldPrice;
                        document.getElementById('price').innerHTML = price;
                    } 
                    </script>";
                } else {
                    echo "<h3 class='title'>€<span id='price'></span></h3>";
                    echo "<script>
                    function calculatePrice(amount) {
                        var price = {$price};
                        price = price * amount;
                        price = price.toFixed(2);
                        price = price.toString();
                        price = price.replace('.', ',');
                        document.getElementById('price').innerHTML = price;
                    } 
                    </script>";
                }
                if ($stock == 1) {
                    echo "<p class='txt'>{$stock} item op voorraad</p>";
                } else {
                    echo "<p class='txt'>{$stock} items op voorraad</p>";
                }
                if ($deliveryTime == 1) {
                    echo "<p class='txt'>{$deliveryTime} dag levertijd</p>";
                } else {
                    echo "<p class='txt'>{$deliveryTime} dagen levertijd</p>";                    
                }
                if ($deliveryTime == 1) {
                    echo "<p class='txt'><i class='material-icons verticalCentered'>local_shipping</i> Vandaag besteld = morgen gratis in huis!</p>";
                } else {
                    $deliveryDate = date("d-m-Y", strtotime("+{$deliveryTime} day"));
                    echo "<p class='txt'><i class='material-icons verticalCentered'>local_shipping</i> Vandaag besteld = {$deliveryDate} gratis in huis!</p>";                    
                }
                echo "<p class='txt'>Aantal:</p>";
                echo "<input class='alignLeft inputField width20' type='number' min='1' max='99' value='1' id='amount' onchange='calculatePrice(this.value);' onkeyup='calculatePrice(this.value);' /><br />";
                ?>
                <button class="primaryBtn" onclick="addToCart('<?php echo $uuidProduct; ?>');"><i class="material-icons verticalCentered">shopping_cart</i> In winkelwagen</button>
                <div id="shoppingCartResponse"></div>
            </div>
            <?php
            //specs table
            //sql query
            $sqlSpecValue = "SELECT uuidSpec, value FROM `ProductSpecValue` WHERE uuidProduct = :uuidProduct";
            //prepare
            if ($stmtSpecValue = $dbh->prepare($sqlSpecValue)) {
                //bind parameters
                $stmtSpecValue->bindParam(":uuidProduct", $uuidProduct);
                //execute query
                $stmtSpecValue->execute();
                //check if specs are found
                if ($stmtSpecValue->rowCount() > 0) {
                    //build table with specs
                    echo "<h2 class='subTitle txtCenter marginTop500'>Specificaties</h2><br />";
                    echo "<table class='table txt centerItem'>";
                        while ($rowSpecValue = $stmtSpecValue->fetch(PDO::FETCH_ASSOC)) {
                            $sqlSpecType = "SELECT name FROM `ProductSpecType` WHERE uuid = :uuidSpecType";
                            if ($stmtSpecType = $dbh->prepare($sqlSpecType)) {
                                $uuidSpecType = $rowSpecValue['uuidSpec'];
                                $stmtSpecType->bindParam(":uuidSpecType", $uuidSpecType);
                                $stmtSpecType->execute();
                                if ($stmtSpecType) {
                                    $rowSpecType = $stmtSpecType->fetch(PDO::FETCH_ASSOC);
                                    $specName = $rowSpecType['name'];
                                    $specValue = $rowSpecValue['value'];
                                    if ($specValue == "false") {
                                        $specValue = "<span class='txtColorRed'><i class='material-icons verticalCentered'>close</i></span>";
                                    } else if ($specValue == "true") {
                                        $specValue = "<span class='txtColorGreen'><i class='material-icons verticalCentered'>check</i></span>";                                     
                                    }
                                    echo "<tr><td class='tableCell'><b>{$specName}</b></td><td class='tableCell'>{$specValue}</td></tr>";
                                } else {
                                    echo "<tr><td class='tableCell'>Er is iets fout gegaan, probeer het opnieuw.</td><td class='tableCell'></td></tr>";
                                }
                            } else {
                                echo "<tr><td class='tableCell'>Er is iets fout gegaan, probeer het opnieuw.</td><td class='tableCell'></td></tr>";
                            }
                        }           
                    echo "</table><br />";         
                } else {
                    echo "<h2 class='subTitle txtCenter marginTop500'>Specificaties</h2><br />";
                    echo "<p class='txt txtCenter'>Er zijn geen specificaties opgegeven voor dit product.</p>";
                }
            }
            ?>
            <!--reviews-->
            <div id="reviewsResponse"></div>
        </div>
    </body>
</html>
<?php
        } else {
            $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat dit product niet.</p>";
            echo $error;
        }
    } else {
        $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat dit product niet.</p>";
        echo $error;
    }
} else {
    $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat dit product niet.</p>";
    echo $error;
}
?>