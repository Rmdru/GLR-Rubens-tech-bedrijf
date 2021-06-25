<?php
//start session
session_start();

//load config file
require "config.inc.php";

//vars
$shoppingCart = $_SESSION['shoppingCart'];
sort($shoppingCart);

if (!empty($shoppingCart)) {
    $shoppingCart = array_count_values($shoppingCart);
} else {
    $shoppingCart = 0;
}

//check if shopping cart is empty
if ($shoppingCart != 0) {
    $totalPrice = 0;
    $maxDeliveryTime = 0;
    echo "<button class='secondaryBtn centerItem' onclick='emptyShoppingCart();'><i class='material-icons verticalCentered'>delete</i> Winkelwagen legen</button><br /><br />";
    if ($_GET['placeOrderStatus'] == "failed") {
        echo "<p class='txt txtColorRed txtCenter'><i class='material-icons verticalCentered'>close</i> Er is iets mis gegaan. Probeer het opnieuw. Zorg dat je alle velden correct invult.</p>";
    }
    echo "<table class='table txt centerItem'>
        <tr>
            <th class='tableHead'></th>
            <th class='tableHead'>Product</th>
            <th class='tableHead'>Prijs</th>
            <th class='tableHead'>Aantal</th>
            <th class='tableHead'></th>
        </tr>
    ";
        foreach ($shoppingCart as $uuidProduct => $amount) {
            $sql = "SELECT uuid, oldPrice, discountPercent, title, oldPrice / 100 * (100 - discountPercent) AS price, MAX(deliveryTime) AS maxDeliveryTime FROM `product` WHERE uuid = :uuidProduct";
            if ($stmt = $dbh->prepare($sql)) {
                $stmt->bindParam(":uuidProduct", $uuidProduct);
                if ($stmt->execute()) {
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $title = $result['title'];
                    $price = $result['price'];
                    $price = round($price, 2);
                    if ($result['maxDeliveryTime'] > $maxDeliveryTime) {
                        $maxDeliveryTime = $result['maxDeliveryTime'];
                    }
                    $discountPercent = $result['discountPercent'];
                    $subTotalPrice = $price * $amount;
                    $subTotalPrice = str_replace(".", ",", $subTotalPrice);   
                    $totalPrice += $subTotalPrice;
                    echo "<tr>
                        <td class='tableCell'><img class='height100px margin0auto displayBlock' src='img/product/{$uuidProduct}_1.png' draggable='false' /></td>
                        <td class='tableCell txtCenter'><a class='link linkHoverColorGreen' href='product.php?uuid={$uuidProduct}'>{$title}</a></td>
                        <td class='tableCell txtCenter'>€{$subTotalPrice}</td>";
                        ?>
                        <td class="tableCell"><input class="inputField width55 margin0 centerItem" type="number" min="1" max="99" value="<?php echo $amount; ?>" onchange="changeAmount('<?php echo $uuidProduct; ?>', this.value);" onkeyup="changeAmount('<?php echo $uuidProduct; ?>', this.value);" /></td>
                        <td class="tableCell"><button class="secondaryBtn centerItem" onclick="removeItem('<?php echo $uuidProduct; ?>');"><i class="material-icons verticalCentered">delete</i></button></td>
                        <?php
                    echo "</tr>";
                }
            }
        }
        $totalPrice = str_replace(".", ",", $totalPrice);
        echo "<tr><td class='tableCell txtCenter'><b>Totaal:</b></td><td class='tableCell'></td><td class='tableCell txtCenter'><b>€{$totalPrice}</b></td><td class='tableCell'></td><td class='tableCell'></td></tr>";
        echo "</table><br />";
        $maxDeliveryDate = date("d-m-Y", strtotime("+{$maxDeliveryTime} day"));
        if ($maxDeliveryTime == 1) {
            echo "<p class='txt txtCenter'>De bestelling word morgen gratis geleverd.</p>";            
        } else {
            echo "<p class='txt txtCenter'>De bestelling word op {$maxDeliveryDate} gratis geleverd.</p>";
        }
        //execute query on db to get account data
        $uuidUser = $_SESSION['uuid'];
        $sqlUser = "SELECT * FROM `user` WHERE uuid = :uuid";
        if ($stmtUser = $dbh->prepare($sqlUser)) {
            $stmtUser->bindParam(":uuid", $uuidUser);
            if ($stmtUser->execute()) {
                $resultUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
            }
        }
        ?>
        <h2 class="subTitle txtCenter">Persoonsgegevens</h2>
        <form action="includes/placeOrder.inc.php" method="post" class="form50 txtCenter">
            <input type="hidden" name="csrfToken" value="<?php echo $csrfToken; ?>" />
            <?php
            //vars
            $email = $resultUser['email'];
            if (empty($email)) {
                $email = "Geen";
            }
            $firstName = $resultUser['firstName'];
            if (empty($firstName)) {
                $firstName = "Geen";
            }
            $insertion = $resultUser['insertion'];
            if (empty($insertion)) {
                $insertion = "Geen";
            }
            $lastName = $resultUser['lastName'];
            if (empty($lastName)) {
                $lastName = "Geen";
            }
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
            <h3 class="subTitle">Persoonsgegevens</h3>
            <p class="txt">E-mailadres:</p>
            <input class="inputField" type="text" value="<?php echo $email; ?>" name="email" />
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
            <button type="submit" class="primaryBtn"><i class="material-icons verticalCentered">shopping_cart</i> Bestellen & afrekenen</button>
        </form><br />
        <?php
} else {
    echo "<p class='txt txtCenter'>Je winkelwagen is leeg. Vul je winkelwagen met <a class='link linkHoverColorGreen' href='productCategorie.php?category=pcComponents'>PC componenten</a>, <a class='link linkHoverColorGreen' href='productCategorie.php?category=preBuildPcs'> pre-build PC's</a> of <a class='link linkHoverColorGreen' href='productCategorie.php?category=laptops'> laptops</a>.</p>";
}