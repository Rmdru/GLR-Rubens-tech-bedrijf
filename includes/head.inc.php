        <!--favicon-->
        <link href="img/logo/icon.png" type="image/png" rel="icon" />
        <!--material icons-->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
        <!--css-->
        <link href="css/style.css" type="text/css" rel="stylesheet" />
        <!--roboto and dm sans font-->
        <link href="https://fonts.googleapis.com/css?family=DM+Sans|Roboto&amp;display=swap" rel="stylesheet" />
        <!--viewport-->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--fontawesome icons-->
        <script defer="" src="https://use.fontawesome.com/releases/v5.0.12/js/all.js" integrity="sha384-Voup2lBiiyZYkRto2XWqbzxHXwzcm4A5RfdfG6466bu5LqjwwrjXCMBQBLMWh7qR" crossorigin="anonymous"></script>
        <!--jquery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!--js files-->
        <script defer src="js/main.js"></script>
        <script defer src="js/register.js"></script>
        <script defer src="js/login.js"></script>
        <script defer src="js/product.js"></script>
        <script defer src="js/shoppingCart.js"></script>
        <script defer src="js/search.js"></script>
        <script defer src="js/productsOverview.js"></script>
        <?php
        $currentPage = basename($_SERVER['PHP_SELF']);
        if ($currentPage == "mijnAccount.php" OR $currentPage == "mijnAccount") {
                echo "<script defer src='js/myAccount.js'></script>";
        }
        ?>
        <script defer src="js/removeAccount.js"></script>
        <?php
        //load autologin.inc.php
        require "includes/autologin.inc.php";
        ?>