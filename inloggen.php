<?php
    //start session
    session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Inloggen - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--login form-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Inloggen</h1>
            <p class="txt">Als je al een account hebt kan je hieronder inloggen.<br /><br />Heb je nog geen account?<br />Dan kan je je <a href="registreren.php" class="link linkHoverColorGreen">hier registreren</a>.</p>
            <div class="form50">
                <h2 class="title txtCenter">Inloggen</h2>
                <?php
                    if ($_GET['verifyStatus'] == "success") {
                        echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> E-mailadres succesvol geverifieerd</p>";
                    } else if ($_GET['loggedOut'] == 1) {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Je bent automatisch uitgelogd, omdat je IP-adres gewijzigd is en daarom word u verdacht van session hijacking.</p>";
                    } else if ($_GET['loggedOut'] == 2) {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Je bent automatisch uitgelogd, omdat je een uur lang geen interactie hebt gemaakt met je account. Je kan gewoon weer hieronder inloggen.</p>";
                    } else if ($_GET['pswChangeStatus'] == "success") {
                        echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Wachtwoord succesvol gewijzigd, je kunt nu inloggen met je nieuwe wachtwoord.</p>";
                    }
                    
                    $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;
                ?>
                <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <input type="text" class="inputField" name="email" id="email" placeholder="E-mailadres" />
                <input type="password" class="inputField" name="psw" id="psw" placeholder="Wachtwoord" /><br /><br />
                <label class="checkboxContainer">Wachtwoord weergeven
                    <input type="checkbox" onclick="pswToggle();" id="pswCheckbox" />
                    <span class="checkboxCheckmark"></span>
                </label><br />
                <label class="checkboxContainer">Ingelogd blijven
                    <input type="checkbox" id="autologinCheckbox" />
                    <span class="checkboxCheckmark"></span>
                </label><br />
                <a href="wachtwoordWijzigingAanvragen.php" class="centerItem link linkHoverColorGreen">Wachtwoord vergeten?</a><br />
                <button type="submit" onclick="login();" class="primaryBtn centerItem">Inloggen</button>
                <div id="response"></div>
            </div><br />
        </div>
    </body>
</html>