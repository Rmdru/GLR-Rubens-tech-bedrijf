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
$categoryId = validateData("category");

//validate data
if (empty($categoryId)) {
    $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat deze productcategorie niet.</p>";
    $categoryName = "Error: mogelijk bestaat deze categorie niet";
}

$sqlCategory = "SELECT name FROM `productCategory` WHERE id = :categoryId";
if ($stmtCategory = $dbh->prepare($sqlCategory)) {
    $stmtCategory->bindParam(":categoryId", $categoryId);
    $stmtCategory->execute();
    $rowCategory = $stmtCategory->fetch(PDO::FETCH_ASSOC);
    $categoryName = $rowCategory['name'];
}
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title><?php echo $categoryName; ?> - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <div class="wrapperTop">
            <h1 class="title txtCenter"><?php echo $categoryName; ?></h1>
            <?php
                $sqlType = "SELECT id, name FROM `productType` WHERE categoryId = :categoryId";
                if ($stmtType = $dbh->prepare($sqlType)) {
                    $stmtType->bindParam(":categoryId", $categoryId);
                    if ($stmtType->execute()) {
                        if ($stmtType->rowCount() > 0) {
                            echo "<p class='txt txtCenter'>Kies hieronder de subcategorie die u wilt bekijken.</p>";
                            while ($rowType = $stmtType->fetch(PDO::FETCH_ASSOC)) {
                                $typeId = $rowType['id'];
                                $typeName = $rowType['name'];
                                echo "<div class='tile tileHover'>";
                                    echo "<img class='tileImg centerItem' src='img/type/{$typeId}.png' draggable='false' />";
                                    echo "<h3 class='subTitle txtCenter'>{$typeName}</h3>";
                                    echo "<a class='primaryBtn centerItem' href='productenOverzicht?type={$typeId}'>Bekijk deze categorie</a><br />";
                                echo "</div>";
                            }
                        } else {
                            $error .= "<p class='txt txtCenter'>Er is iets fout gegaan. Mogelijk bestaat deze productcategorie niet.</p>";
                            echo $error;
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>