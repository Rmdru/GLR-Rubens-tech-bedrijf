<?php
    //start session
    session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Wachtwoord wijziging aanvragen - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--login form-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Wachtwoord wijzigen</h1>
            <p class="txt">Ben je per ongeluk je wachtwoord van je account vergeten? Geen nood, je kan hier je wachtwoord wijzigen. Het werkt alsvolgt:<br /><br />1. Om te verifiëren dat jij degene bent die je wachtwoord wijzigt moeten wij je een verificatie e-mail sturen, vul daarvoor je e-mailadres hieronder in.<br />2. Check je mailbox en klik daar op de link die wij naar jou toe hebben gestuurd. Let op! Zorg dat je de de browser open laat staan!<br />3. Voer je nieuwe wachtwoord in.</p>
            <form action="includes/changePswRequest.inc.php" class="form50" method="POST">
                <h2 class="title txtCenter">Wachtwoord wijzigen</h2>
                <?php
                    if ($_GET['status'] == "success") {
                        echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Mail succesvol verzonden! Check je mailbox en klik daar op de link om je wachtwoord te wijzigen.</p>";
                    } else if ($_GET['status'] == "failed") {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Vul een geldig e-mailadres in!</p>";
                    } else if ($_GET['status'] == "unknownEmail") {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Dit e-mailadres is niet geregistreerd bij ons!</p>";
                    }
                    $csrfToken == bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;
                ?>
                <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <input type="text" class="inputField" name="email" id="email" placeholder="E-mailadres" /><br />
                <button type="submit" class="primaryBtn centerItem">Verificatiemail versturen</button>
            </form><br />
        </div>
    </body>
</html>