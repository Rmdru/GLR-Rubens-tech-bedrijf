<?php
//start session
session_start();

//load config file
require "config.inc.php";

//vars
$uuidProduct = $_GET["uuidProduct"];
$status = $_GET["status"];

//reviews
echo "<h2 class='subTitle txtCenter'>Reviews</h2>";
//read data from review and user table
$sqlReview = "SELECT date, anonymous, stars, title, description, firstName, insertion, lastName, uuidUser FROM `Review` INNER JOIN `user` ON Review.uuidUser = user.uuid WHERE 1=1 AND Review.uuidProduct = :uuidProduct";
if ($stmtReview = $dbh->prepare($sqlReview)) {
    //bind parameters
    $stmtReview->bindParam(":uuidProduct", $uuidProduct);
    //execute query, if there are results show them
    if ($stmtReview->execute()) {
        if ($stmtReview->rowCount() > 0) {
            //get and show average rating of this product      
            $sqlRating = "SELECT AVG(stars) AS avgRating FROM `Review` WHERE 1=1 AND uuidProduct = :uuidProduct";
            if ($stmtRating = $dbh->prepare($sqlRating)) {
                $stmtRating->bindParam(":uuidProduct", $uuidProduct);
                if ($stmtRating->execute()) {
                    $resultRating = $stmtRating->fetch(PDO::FETCH_ASSOC);
                    $avgRating = $resultRating['avgRating'];
                    $avgRating = round($avgRating, 1);
                    echo "<p class='txt txtCenter'><b>Dit product heeft een gemiddelde beoordeling van: {$avgRating}/5.</b></p>";
                }
            }
            $review = "";
            while ($rowReview = $stmtReview->fetch(PDO::FETCH_ASSOC)) {
                $reviewExist = 0;
                $uuidUser = $rowReview['uuidUser'];
                $firstName = $rowReview['firstName'];
                $insertion = $rowReview['insertion'];
                $lastName = $rowReview['lastName'];
                $date = $rowReview['date'];
                $date = date("d-m-Y", strtotime($date));
                $anonymous = $rowReview['anonymous'];
                $stars = $rowReview['stars'];
                $title = $rowReview['title'];
                $description = $rowReview['description'];
                if ($_SESSION['uuid'] == $uuidUser) {
                    $reviewExist = 1;
                }
                if (!empty($insertion)) {
                    $fullName = "{$firstName} {$insertion} {$lastName}";
                } else {
                    $fullName = "{$firstName} {$lastName}";
                }
                if ($anonymous == 1) {
                    $fullName = "Anonieme gebruiker";
                }
                $review .= "<div class='centerItem width50 review'><p class='txt'><b>{$fullName}</b> | {$date}</p><span class='title'>";
                for ($i = 0; $i < $stars; $i++) {
                    $review .= "<i class='material-icons verticalCentered'>star</i>";
                }
                $starBorder = 5 - $stars;
                for ($i = 0; $i < $starBorder; $i++) {
                    $review .= "<i class='material-icons verticalCentered'>star_border</i>";
                }
                $review .= "</span><br /><h3 class='subTitle'>{$title}</h3><p class='txt'>{$description}</p></div>";
            }
            echo $review;
        } else {
            echo "<p class='txt txtCenter'>Er zijn nog geen reviews geplaatst.</p>";
        }
    } else {
        echo "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
    }
} else {
    echo "<p class='txt txtCenter'>Er is iets fout gegaan, probeer het opnieuw.</p>";
}
//if user is logged in show review form otherwise show warning
if (!isset($_SESSION['uuid'])) {
    echo "<h3 class='txt txtCenter'><b>Review plaatsen</b></h3>";
    echo "<p class='txt'>Om een review te plaatsen moet je ingelogd zijn. Als je al een account hebt kun je <a class='link linkHoverColorGreen' href='inloggen.php'>hier inloggen</a>, als je nog geen account heb kun je <a class='link linkHoverColorGreen' href='registreren.php'>hier je account aanmaken</a>.</p>";
} else if ($reviewExist != 0 AND $_GET['status'] == 0) {
    echo "<h3 class='txt txtCenter'><b>Review plaatsen</b></h3>";
    echo "<p class='txt txtCenter'>U heeft al een review bij dit product geschreven.</p>";
} else if ($_GET['status'] == 1) {
    echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Review succesvol geplaatst!</p>";
} else {
    echo "<h3 class='txt txtCenter'><b>Review plaatsen</b></h3>";
    //generate csrf token
    $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
    $_SESSION['csrfToken'] = $csrfToken;
    echo "<form class='form50'>";
        echo "<p class='txt txtCenter'>Aantal sterren:</p>";
        echo "<select id='stars' class='inputField width55'>";
            echo "<option value=''>Kies het aantal sterren</option>";
            for ($i = 1; $i < 6; $i++) {
                echo "<option value='{$i}'>{$i}</option>";
            }
        echo "</select>";
        echo "<input type='text' class='inputField' placeholder='Titel' id='title' />";
        echo "<textarea rows='5' class='inputField' placeholder='Uw ervaring met dit product' id='description'></textarea><br />";
        echo "<label class='checkboxContainer'>Anonieme review plaatsen";
            echo "<input type='checkbox' id='anonymous' value='1' />";
            echo "<span class='checkboxCheckmark'></span>";
        echo "</label><br />";
        ?>
        <button type="button" onclick="submitReview('<?php echo $uuidProduct; ?>', '<?php echo $csrfToken; ?>')" class="primaryBtn centerItem">Review plaatsen</button>
        <?php
        echo "<div id='response'></div>";
    echo "</form><br />";
}
?>
