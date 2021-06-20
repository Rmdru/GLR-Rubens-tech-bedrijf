<?php
    //start session
    session_start();

    //load config.inc.php
    require "includes/config.inc.php";

    //load sessions.inc.php
    require "includes/sessions.inc.php";
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
            <?php
                //execute query on db to get account data
                $uuidUser = $_SESSION['uuid'];
                $sqlUser = "SELECT * FROM `user` WHERE uuid = :uuid";
                if ($stmtUser = $dbh->prepare($sqlUser)) {
                    $stmtUser->bindParam(":uuid", $uuidUser);
                    if ($stmtUser->execute()) {
                        $resultUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
                        $name = $resultUser['name'];
                    }
                }

                //vars
                $firstName = $resultUser['firstName'];
                $insertion = $resultUser['insertion'];
                $lastName = $resultUser['lastName'];
                if (!empty($insertion)) {
                    $fullName = "{$firstName} {$insertion} {$lastName}";
                } else {
                    $fullName = "{$firstName} {$lastName}";
                }
                $currentTime = date("H");

                //welcome msg
                if ($currentTime >= 0 && $currentTime < 12) {
                    echo "<h1 class='title txtCenter'>Goedemorgen {$fullName}</h1>";
                } else if ($currentTime >= 12 && $currentTime <= 17) {
                    echo "<h1 class='title txtCenter'>Goedemiddag {$fullName}</h1>";
                } else if ($currentTime >= 18 && $currentTime <= 24) {
                    echo "<h1 class='title txtCenter'>Goedenavond {$fullName}</h1>";
                }
            ?>
            <p class="txt txtCenter">Hier kan je jouw bestellingen bekijken, volgen en account gegevens en instellingen bekijken en aanpassen.</p><br />
            <?php
            if ($_GET['changeAccountDetailsStatus'] == "failed") {
                echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets mis gegaan. Probeer het opnieuw. Zorg dat je alle velden correct invult.</p>";
            } else if ($_GET['changeAccountDetailsStatus'] == "success") {
                echo "<p class='txt txtColorGreen txtCenter'><i class='material-icons verticalCentered'>check</i> Accountgegevens succesvol gewijzigd.</p>";
            }
            ?>
            <div class="tabs">
                <button class="tabLink" onclick="openTab(event, 'orders')" id="defaultTab">Bestellingen</button>
                <button class="tabLink" onclick="openTab(event, 'accountDetails')">Accountgegevens</button>
                <button class="tabLink" onclick="openTab(event, 'accountSettings')">Accountinstellingen</button>
            </div>
            <div id="orders" class="tabContent">
                <h3 class="title txtCenter">Bestellingen</h3>
                <?php
                //get orders from db
                $sqlOrder = "SELECT * FROM `order` WHERE uuidUser = :uuidUser";
                if ($stmtOrder = $dbh->prepare($sqlOrder)) {
                    $stmtOrder->bindParam(":uuidUser", $uuidUser);
                    if ($stmtOrder->execute()) {
                        if ($stmtOrder->rowCount() > 0) {
                            echo "<table class='table txtCenter'>";
                                echo "<tr class='tableRow'>";
                                    echo "<th class='tableHead subTitle'>Ordernummer</th>";
                                    echo "<th class='tableHead subTitle'>Product(en)</th>";
                                    echo "<th class='tableHead subTitle'>Totaalprijs</th>";
                                    echo "<th class='tableHead subTitle'>Status</th>";
                                    echo "<th class='tableHead subTitle'>Verwachte leverdatum</th>";
                                    echo "<th class='tableHead subTitle'>Datum bestelling geplaatst</th>";
                                    echo "<th class='tableHead subTitle'>Datum bestelling geleverd</th>";
                                echo "</tr>";
                                while ($rowOrder = $stmtOrder->fetch(PDO::FETCH_ASSOC)) {
                                    $orderId = $rowOrder['orderId'];
                                    $products = $rowOrder['uuidProducts'];
                                    $products = explode(";", $products);
                                    $products = array_count_values($products);
                                    $price = $rowOrder['price'];
                                    $price = str_replace(".", ",", $price);
                                    $status = $rowOrder['status'];
                                    $deliveryTime = $rowOrder['deliveryTime'];
                                    $dateOrderPlaced = $rowOrder['dateOrderPlaced'];
                                    $dateOrderPlaced = date("d-m-Y", strtotime($dateOrderPlaced));
                                    $dateOrderDelivered = $rowOrder['dateOrderDelivered'];
                                    if ($dateOrderDelivered != null) {
                                        $dateOrderDelivered = date("d-m-Y", strtotime($dateOrderDelivered));
                                    }
                                    echo "<tr class='tableRow'>";
                                        echo "<td class='tableCell txt'>{$orderId}</td>";
                                        echo "<td class='tableCell txt'>";
                                        foreach($products as $product => $amount) {
                                            $sqlProduct = "SELECT uuid,title FROM `product` WHERE uuid = :product";
                                            if ($stmtProduct = $dbh->prepare($sqlProduct)) {
                                                $stmtProduct->bindParam(":product", $product);
                                                if ($stmtProduct->execute()) {
                                                    $result = $stmtProduct->fetch(PDO::FETCH_ASSOC);
                                                    $uuidProduct = $result['uuid'];
                                                    $title = $result['title'];
                                                    echo "<a href='product.php?uuid={$uuidProduct}' class='link linkHoverColorGreen'>" . $title . "</a> ({$amount}x)<br /><br />";
                                                }
                                            }
                                        }
                                        echo "</td>";
                                        echo "<td class='tableCell txt'>€{$price}</td>";
                                        echo "<td class='tableCell txt'>";
                                        if ($status == 0) {
                                            echo "Geannuleerd";
                                        } else if ($status == 1) {
                                            echo "In behandeling<br /><br /><a href='includes/cancelOrder.inc.php?orderId={$orderId}' class='link linkHoverColorGreen linkUnderline'>Bestelling annuleren</a>";
                                        } else if ($status == 2) {
                                            echo "Onderweg naar het depot";
                                        } else if ($status == 3) {
                                            echo "Bij het depot";
                                        } else if ($status == 4) {
                                            echo "Bezorger is onderweg";
                                        } else if ($status == 5) {
                                            echo "Afgeleverd";
                                        } else if ($status == 6) {
                                            echo "Status onbekend, <a href='contact.php' class='link linkHoverColorGreen linkUnderline'>Neem hier contact op</a>";
                                        }
                                        echo "</td>";
                                        echo "<td class='tableCell txt'>";
                                        if ($deliveryTime == -1) {
                                            echo "Niet beschikbaar";
                                        } else if ($deliveryTime == 1) {
                                            echo "Morgen";
                                        } else {
                                            $deliveryDate = date("d-m-Y", strtotime("+{$deliveryTime} day"));
                                            echo $deliveryDate;
                                        }
                                        echo "</td>";
                                        echo "<td class='tableCell txt'>{$dateOrderPlaced}</td>";
                                        echo "<td class='tableCell txt'>";
                                        if ($dateOrderDelivered != null) {
                                            echo "{$dateOrderDelivered}</td>";
                                        } else {
                                            echo "Niet beschikbaar";
                                        }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            echo "</table>";
                        } else {
                            echo "<p class='txt txtCenter'>Er zijn nog geen bestellingen geplaatst.</p>";
                        }
                    } else {
                        echo "<p class='txt txtCenter'>Er is iets fout gegaan bij het ophalen van de bestellingen, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link linkHoverColorGreen'>Neem dan contact op</a>.</p>";
                    }
                } else {
                    echo "<p class='txt txtCenter'>Er is iets fout gegaan bij het ophalen van de bestellingen, probeer het opnieuw. Blijft het probleem optreden? <a href='contact.php' class='link linkHoverColorGreen'>Neem dan contact op</a>.</p>";
                }
                ?>
            </div>
            <div id="accountDetails" class="tabContent">
                <h3 class="title txtCenter">Accountgegevens</h3>
                <form action="includes/changeAccountDetails.inc.php" method="post" class="form50 txtCenter">
                    <?php
                    //vars
                    $email = $resultUser['email'];
                    $city = $resultUser['city'];
                    if (empty($city)) {
                        $city = "Geen";
                    }
                    $postalCode = $resultUser['postalCode'];
                    if (empty($postalCode)) {
                        $postalCode = "Geen";
                    }
                    $address = $resultUser['address'];
                    if (empty($address)) {
                        $address = "Geen";
                    }
                    $housenumber = $resultUser['housenumber'];
                    if ($housenumber == 0) {
                        $housenumber = "Geen";
                    }
                    $bank = $resultUser['bank'];
                    $paymentMethod = $resultUser['paymentMethod'];

                    $csrfToken == bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['csrfToken'] = $csrfToken;
                    ?>
                    <input type="hidden" id="uuid" name="uuid" value="<?php echo $uuidUser; ?>" />
                    <input type="hidden" id="csrfToken" name="csrfToken" value="<?php echo $csrfToken; ?>" />
                    <h3 class="subTitle">Inloggegevens</h3>
                    <p class="txt">E-mailadres:</p>
                    <input class="inputField" type="text" value="<?php echo $email; ?>" name="email" />
                    <p class="txt">Nieuw wachtwoord:</p>
                    <input class="inputField" type="password" id="psw" name="psw" />
                    <p class="txt">Herhaal nieuwe wachtwoord:</p>
                    <input class="inputField" type="password" name="pswRepeat" /><br />
                    <label class="checkboxContainer txtLeft">Wachtwoord weergeven
                        <input type="checkbox" onclick="pswToggle();" id="pswCheckbox">
                        <span class="checkboxCheckmark"></span>
                    </label><br />
                    <button type="button" class="secondaryBtn centerItem" onclick="generateRandomPsw();"><i class="fas fa-random verticalCentered" style="font-size: 18px;"></i> Willekeurig wachtwoord genereren</button><br />
                    <h3 class="subTitle">Persoonsgegevens</h3>
                    <p class="txt">Voornaam:</p>
                    <input class="inputField" type="text" value="<?php echo $firstName; ?>" name="firstName" />
                    <p class="txt">Tussenvoegsel:</p>
                    <input class="inputField" type="text" value="<?php echo $insertion; ?>" name="insertion" />
                    <p class="txt">Achternaam:</p>
                    <input class="inputField" type="text" value="<?php echo $lastName; ?>" name="lastName" />
                    <p class="txt">Woonplaats:</p>
                    <input class="inputField" type="text" value="<?php echo $city; ?>" name="city" />
                    <p class="txt">Postcode:</p>
                    <input class="inputField" type="text" value="<?php echo $postalCode; ?>" name="postalCode" />
                    <p class="txt">Straat:</p>
                    <input class="inputField" type="text" value="<?php echo $address; ?>" name="address" />
                    <p class="txt">Huisnummer:</p>
                    <input class="inputField" type="text" value="<?php echo $housenumber; ?>" name="housenumber" />
                    <h3 class="subTitle">Betaalgegevens</h3>
                    <p class="txt">Bank:</p>
                    <select name="bank" class="inputField">
                        <option value="">Geen bank geselecteerd</option>
                        <option value="ABN AMRO" <?php if ($bank == "ABN AMRO") { echo "selected='selected'"; } ?>>ABN AMRO</option>
                        <option value="ASN Bank" <?php if ($bank == "ASN Bank") { echo "selected='selected'"; } ?>>ASN Bank</option>
                        <option value="ASR Bank" <?php if ($bank == "ASR Bank") { echo "selected='selected'"; } ?>>ASR Bank</option>
                        <option value="BinckBank" <?php if ($bank == "BinckBank") { echo "selected='selected'"; } ?>>BinckBank</option>
                        <option value="Rabobank" <?php if ($bank == "Rabobank") { echo "selected='selected'"; } ?>>Rabobank</option>
                        <option value="Delta Lloyd" <?php if ($bank == "Delta Lloyd") { echo "selected='selected'"; } ?>>Delta Lloyd</option>
                        <option value="ING Bank" <?php if ($bank == "ING Bank") { echo "selected='selected'"; } ?>>ING Bank</option>
                        <option value="Nationale-Nederlanden" <?php if ($bank == "Nationale-Nederlanden") { echo "selected='selected'"; } ?>>Nationale-Nederlanden</option>
                        <option value="RegioBank" <?php if ($bank == "RegioBank") { echo "selected='selected'"; } ?>>RegioBank</option>
                        <option value="SNS Bank" <?php if ($bank == "SNS Bank") { echo "selected='selected'"; } ?>>SNS Bank</option>
                        <option value="Triodos Bank" <?php if ($bank == "Triodos Bank") { echo "selected='selected'"; } ?>>Triodos Bank</option>
                    </select>
                    <p class="txt">Betaalmethode:</p>
                    <select name="paymentMethod" class="inputField">
                        <option value="">Geen betaalmethode geselecteerd</option>
                        <option value="iDEAL" <?php if ($paymentMethod == "iDEAL") { echo "selected='selected'"; } ?>>iDEAL</option>
                        <option value="Visa" <?php if ($paymentMethod == "Visa") { echo "selected='selected'"; } ?>>Visa</option>
                        <option value="Mastercard" <?php if ($paymentMethod == "Mastercard") { echo "selected='selected'"; } ?>>Mastercard</option>
                        <option value="PayPal" <?php if ($paymentMethod == "PayPal") { echo "selected='selected'"; } ?>>PayPal</option>
                        <option value="Afterpay" <?php if ($paymentMethod == "Afterpay") { echo "selected='selected'"; } ?>>Afterpay</option>
                    </select><br />
                    <button type="submit" class="primaryBtn"><i class="material-icons verticalCentered">check</i> Wijzigingen opslaan</button>
                </form>
            </div>
            <div id="accountSettings" class="tabContent">
                <h3 class="title txtCenter">Accountinstellingen</h3>
                <h3 class="subTitle txtCenter">Uitloggen</h3>
                <a href="includes/logout.inc.php" class="secondaryBtn centerItem">Uitloggen</a><br />
                <hr class="rule" />
                <h3 class="subTitle txtCenter">Account verwijderen</h3>
                <a href="accountVerwijderen.php?uuid=<?php echo $uuidUser; ?>" class="secondaryBtn centerItem">Account verwijderen</a><br />                
            </div>
        </div><br />
    </body>
</html>