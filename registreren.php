<?php
    //start session
    session_start();
    
    //load captcha.inc.php
    require "includes/captcha.inc.php";
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Registreren - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--register form-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Registreren</h1>
            <p class="txt">Via het formulier hieronder kun je een account aanmaken voor Ruben's tech bedrijf. Een account heeft een aantal voordelen:<br /><br /><i class="material-icons verticalCentered">check</i> Je kunt jouw bestellingen bekijken, volgen en beheren<br /><i class="material-icons verticalCentered">check</i> Je kunt eenvoudig nieuwe bestellingen plaatsen<br /><i class="material-icons verticalCentered">check</i> Je kunt reviews plaatsen bij producten<br /><br />Heb je al een account?<br />Dan kan je <a href="inloggen.php" class="link linkHoverColorGreen">hier inloggen</a>.</p>
            <div class="form50">
                <h2 class="title txtCenter">Registratieformulier</h2>
                <?php
                    if ($_GET['verifyStatus'] == "failed") {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets mis gegaan bij het verifiëren van het e-mailadres, of dit e-mailadres is al geverifieërd. Probeer je hieronder opnieuw te registreren.</p>";
                    }
                    $csrfToken == bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;
                ?>
                <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <input type="text" class="inputField" name="firstName" id="firstName" placeholder="Voornaam" />
                <input type="text" class="inputField" name="insertion" id="insertion" placeholder="Tussenvoegsel" />
                <input type="text" class="inputField" name="lastName" id="lastName" placeholder="Achternaam" />
                <input type="text" class="inputField" name="email" id="email" placeholder="E-mailadres" />
                <input type="password" class="inputField" name="psw" id="psw" placeholder="Wachtwoord" />
                <input type="password" class="inputField" name="pswRepeat" id="pswRepeat" placeholder="Herhaal wachtwoord" /><br /><br />
                <label class="checkboxContainer">Wachtwoord weergeven
                    <input type="checkbox" onclick="pswToggle();" id="pswCheckbox">
                    <span class="checkboxCheckmark"></span>
                </label><br />
                <button type="button" class="secondaryBtn centerItem" onclick="generateRandomPsw();"><i class="fas fa-random verticalCentered" style="font-size: 18px;"></i> Willekeurig wachtwoord genereren</button><br />
                <img src="img/captcha/captchaImg.php" class="captchaImg centerItem" id="captchaImg" draggable="false" /><br />
                <input type="text" name="captcha" id="captcha" class="inputField" placeholder="Typ de karakters hierboven over" /><br />
                <button type="button" class="secondaryBtn centerItem" onclick="reloadCaptcha();"><i class="material-icons verticalCentered">sync</i> Nieuwe karakters</button><br />
                <button type="submit" onclick="register()" class="primaryBtn centerItem"><i class="material-icons verticalCentered">check</i> Registreren</button>
                <div id="response"></div>
            </div><br />
        </div>
    </body>
</html>