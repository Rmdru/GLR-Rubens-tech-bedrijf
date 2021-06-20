<?php
    //start session
    session_start();
    
    //vars
    $uuid = $_GET['uuid'];
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Mijn account - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--account dashboard-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Account permanent verwijderen</h1>
            <?php
            if ($_GET['status'] == "failed") {
                echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets mis gegaan. Probeer het opnieuw of <a class='link' href='contact.php'>neem contact op</a>.</p>";
            }
            ?>
            <p class="txt">Hier kan jij jouw account permanent verwijderen. Hieronder staat welke gevolgen dit heeft:<br /><br />- Je kan jouw oude bestellingen niet meer zien, omdat deze verwijderd worden.<br />- Je kan jouw geannuleerde bestellingen niet meer zien, omdat deze verwijderd worden.<br />- De bestellingen die nog niet bezorgt zijn kan je niet meer zien en volgen, maar worden wel gewoon geleverd zoals u van ons gewend bent.<br />- Reviews die door jou geplaatst zijn worden verwijderd en zijn niet meer te zien.</p><br />
            <label class="checkboxContainer">Ik heb het bovenstaande gelezen en weet zeker dat ik mijn account en alle bijbehorende gegevens permanent wil verwijderen.
                <input type="checkbox" onclick="enableDisabledBtnToggle('includes/removeAccount.inc.php?uuid=<?php echo $uuid; ?>');" id="toggleCheckbox">
                <span class="checkboxCheckmark"></span>
            </label><br />
            <a class="disabledSecondaryBtn centerItem" id="disabledBtn">Account verwijderen</a><br />
        </div><br />
    </body>
</html>