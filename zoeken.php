<?php
//start session
session_start();
?>
<!doctype html>
<!--start html-->
<html>
    <!--title and head-->
    <head>
        <title>Zoeken - Ruben's tech bedrijf</title>
        <?php require "includes/head.inc.php"; ?>
    </head>
    <body>
        <!--navbar-->
        <?php require "includes/navbar.inc.php"; ?>
        <!--wrapper-->
        <div class="wrapperTop"> 
            <h1 class="title txtCenter">Zoeken</h1>
            <!--generate csrf token-->
            <?php
            $csrfToken = bin2hex(openssl_random_pseudo_bytes(32));

            $_SESSION['csrfToken'] = $csrfToken;
            ?>
            <input type="hidden" value="<?php echo $csrfToken; ?>" id="csrfToken" />
            <!--search field-->
            <input type="text" placeholder="Zoeken" id="searchField" class="inputField centerItem" onkeyup="search();" /><br />
            <!--search results-->
            <div id="searchResults"></div>
        </div>
    </body>
</html>