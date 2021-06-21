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
        <title>Contact - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--wrapper-->
        <div class="wrapperTop">
            <h1 class="title txtCenter">Contact</h1>
            <p class="txt">Heeft u een vraag, klacht of tip? Misschien wilt u wel een compliment geven of een beroep doen op uw recht op garantie? Of heeft u hulp nodig bij het kiezen van een bepaald product? Of is er iets anders waarvoor u contact wilt opnemen met ons? Ons personeel heeft gespecialiseerde technische kennis en helpt u graag. Het contact center is van 9:00 uur tot 17:00 uur geopend. U kunt contact met ons opnemen door het onderstaande contact formulier in te vullen.</p>
            <!--contact form-->
            <form action="includes/contact.inc.php" method="POST" class="form50">
                <?php
                    //error
                    if ($_GET['status'] == "success") {
                        echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Contactformulier succesvol verzonden! Houd uw mailbox in de gaten, want we zullen u zo spoedig mogelijk helpen!</p>";
                    } else if ($_GET['status'] == "failed") {
                        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets mis gegaan bij het verzenden van het contactformulier. Zorg dat je alle velden correct invult! Let op: het is niet toegestaan om links in het onderwerp of bericht te plaatsen!</p>";
                    }

                    //create csrf token and put it in a session
                    $csrfToken == bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;

                    //load user data if user is logged in
                    if (isset($_SESSION['uuid'])) {
                        $uuid = $_SESSION['uuid'];
                        $sql = "SELECT firstName, insertion, lastName, email FROM `user` WHERE uuid = :uuid";
                        if ($stmt = $dbh->prepare($sql)) {
                            $stmt->bindParam(":uuid", $uuid);
                            if ($stmt->execute()) {
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $firstName = $result['firstName'];
                                $insertion = $result['insertion'];
                                $lastName = $result['lastName'];
                                if (!empty($insertion)) {
                                    $fullName = "{$firstName} {$insertion} {$lastName}";
                                } else {
                                    $fullName = "{$firstName} {$lastName}";                                    
                                }
                                $email = $result['email'];
                            }
                        }
                    }
                ?>
                <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                <h3 class="subTitle txtCenter">Contactformulier</h3>
                <p class="txt txtCenter">Naam:</p>
                <input class="inputField" type="text" name="name" value="<?php echo $fullName; ?>" />
                <p class="txt txtCenter">E-mailadres:</p>
                <input class="inputField" type="email" name="email" value="<?php echo $email; ?>" />
                <p class="txt txtCenter">Onderwerp:</p>
                <input class="inputField" type="text" name="subject" />
                <p class="txt txtCenter">Uw bericht:</p>
                <textarea class="inputField" name="msg" rows="5"></textarea><br />
                <button class="centerItem primaryBtn" type="submit">Verzenden</button>
            </form><br />
        </div>
    </body>
</html>