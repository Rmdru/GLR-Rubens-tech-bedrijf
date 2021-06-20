<?php
    //start session
    session_start();

    if ($_GET['verificationToken'] != $_SESSION['verificationToken']) {
        header("wachtwoordWijzigingAanvragen.php?status=failed");
    }
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Wachtwoord wijzigen - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--login form-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Wachtwoord wijzigen</h1>
            <p class="txt">Wijzig hieronder jouw wachtwoord.</p>
            <form action="includes/changePsw.inc.php" class="form50" method="POST">
                <h2 class="title txtCenter">Wachtwoord wijzigen</h2>
                <?php
                    if ($_GET['status'] == "failed") {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link'>Neem dan hier contact met ons op</a>.</p>";
                    }
                    $csrfToken == bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;
                ?>
                <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <input type="hidden" id="uuid" name="uuid" value="<?php echo $_GET['uuid']; ?>" />
                <input type="password" class="inputField" name="psw" id="psw" placeholder="Nieuwe wachtwoord" /><br />
                <input type="password" class="inputField" name="pswRepeat" id="pswRepeat" placeholder="Herhaal nieuwe wachtwoord" /><br /><br /><br />
                <label class="checkboxContainer">Wachtwoord weergeven
                    <input type="checkbox" onclick="pswToggle();" id="pswCheckbox">
                    <span class="checkboxCheckmark"></span>
                </label><br />
                <button type="button" class="secondaryBtn centerItem" onclick="generateRandomPsw();"><i class="fas fa-random verticalCentered" style="font-size: 18px;"></i> Willekeurig wachtwoord genereren</button><br />
                <img src="img/captcha/captchaImg.php" class="captchaImg centerItem" id="captchaImg" draggable="false" /><br />
                <input type="text" name="captcha" id="captcha" class="inputField" placeholder="Typ de karakters hierboven over" /><br />
                <button type="button" class="secondaryBtn centerItem" onclick="reloadCaptcha();"><i class="material-icons verticalCentered">sync</i> Nieuwe karakters</button><br />
                <button type="submit" class="primaryBtn centerItem">Verificatiemail versturen</button>
            </form><br />
        </div>
    </body>
</html>