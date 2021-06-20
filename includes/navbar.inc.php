            <div class="navbar">
                <div class="wrapper">
                    <a href="index.php"><img src="img/logo/logo.png" class="navbarLogo" draggable="false" /></a>
                    <div class="navbarLinks">
                        <a href="index.php" class="navbarItem navbarLink linkNoUnderline">Home</a>
                        <div class="navbarDropdown">
                            <button class="navbarItem navbarDropdownBtn">PC componenten <i class="material-icons verticalCentered">keyboard_arrow_down</i></button>
                            <div class="navbarDropdownContent">
                                <a href="productCategorie.php?category=pcComponents" class="navbarDropdownLink">Alle componenten</a>
                                <a href="productenOverzicht.php?type=cpu" class="navbarDropdownLink">Processors</a>
                                <a href="productenOverzicht.php?type=cpuCooler" class="navbarDropdownLink">Processor koelers</a>
                                <a href="productenOverzicht.php?type=mobo" class="navbarDropdownLink">Moederborden</a>
                                <a href="productenOverzicht.php?type=ram" class="navbarDropdownLink">Werkgeheugen</a>
                                <a href="productenOverzicht.php?type=gpu" class="navbarDropdownLink">Videokaarten</a>
                                <a href="productenOverzicht.php?type=ssd" class="navbarDropdownLink">SSD's</a>
                                <a href="productenOverzicht.php?type=hdd" class="navbarDropdownLink">Harde schijven</a>
                                <a href="productenOverzicht.php?type=psu" class="navbarDropdownLink">Voedingen</a>
                                <a href="productenOverzicht.php?type=case" class="navbarDropdownLink">Behuizingen</a>
                                <a href="productenOverzicht.php?type=os" class="navbarDropdownLink">Besturingssystemen</a>
                            </div>
                        </div>
                        <div class="navbarDropdown">
                            <button class="navbarItem navbarDropdownBtn">Pre-build PC's <i class="material-icons verticalCentered">keyboard_arrow_down</i></button>
                            <div class="navbarDropdownContent">
                                <a href="productCategorie.php?category=preBuildPcs" class="navbarDropdownLink">Alle pre-build PC's</a>
                                <a href="productenOverzicht.php?type=budgetPc" class="navbarDropdownLink">Budget PC's</a>
                                <a href="productenOverzicht.php?type=gamingPc" class="navbarDropdownLink">Gaming PC's</a>
                                <a href="productenOverzicht.php?type=businessPc" class="navbarDropdownLink">Zakelijke PC's</a>
                                <a href="productenOverzicht.php?type=server" class="navbarDropdownLink">Servers</a>
                            </div>
                        </div>
                        <div class="navbarDropdown">
                            <button class="navbarItem navbarDropdownBtn">Laptops <i class="material-icons verticalCentered">keyboard_arrow_down</i></button>
                            <div class="navbarDropdownContent">
                                <a href="productCategorie.php?category=laptops" class="navbarDropdownLink">Alle laptops</a>
                                <a href="productenOverzicht.php?type=budgetLaptop" class="navbarDropdownLink">Budget laptops</a>
                                <a href="productenOverzicht.php?type=gamingLaptop" class="navbarDropdownLink">Gaming laptops</a>
                                <a href="productenOverzicht.php?type=businessLaptop" class="navbarDropdownLink">Zakelijke laptops</a>
                            </div>
                        </div>
                        <div class="navbarDropdown">
                            <button class="navbarItem navbarDropdownBtn">Meer <i class="material-icons verticalCentered">keyboard_arrow_down</i></button>
                            <div class="navbarDropdownContent">
                                <a href="aanbiedingen.php" class="navbarDropdownLink">Aanbiedingen</a>
                                <a href="over.php" class="navbarDropdownLink">Over Ruben's tech bedrijf</a>
                                <?php
                                    if (isset($_SESSION['uuid'])) {
                                        echo "<a href='mijnAccount.php' class='navbarDropdownLink'>Mijn account</a>";
                                    } else {
                                        echo "<a href='registreren.php' class='navbarDropdownLink'>Registreren</a>";
                                        echo "<a href='inloggen.php' class='navbarDropdownLink'>Inloggen</a>";                                    
                                    }
                                ?>
                                <a href="contact.php" class="navbarDropdownLink">Contact</a>
                            </div>
                        </div>
                        <a href="zoeken.php" class="navbarItem navbarPrimaryBtn linkNoUnderline"><i class="material-icons verticalCentered">search</i></a>
                        <?php
                        if (isset($_SESSION['shoppingCart'])) {
                            $shoppingCartAmount = count($_SESSION['shoppingCart']);
                        } else {
                            $shoppingCartAmount = 0;
                        }
                        ?>
                        <a href="winkelwagen.php" class="navbarItem navbarPrimaryBtn linkNoUnderline"><i class="material-icons verticalCentered">shopping_cart</i> <span id="shoppingCartIcon"><?php echo $shoppingCartAmount; ?></span></a>
                    </div>
                </div>
            </div>