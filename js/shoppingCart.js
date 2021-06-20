"use strict";
//load shopping cart
function loadShoppingCart() {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var shoppingCartResponse = xmlHttp.responseText;

            document.getElementById("shoppingCartResponse").innerHTML = shoppingCartResponse;
        }
    }

    xmlHttp.open("GET", "includes/loadShoppingCart.inc.php");
    xmlHttp.send(null);
}

//change amount
function changeAmount(uuidProduct, amount) {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.open("GET", "includes/changeAmountShoppingCartItem.inc.php?uuidProduct=" + uuidProduct + "&amount=" + amount);
    xmlHttp.send(null);
    
    //reload shopping cart
    loadShoppingCart();

    //refresh shopping cart icon
    refreshShoppingCartIcon();
}

//remove item
function removeItem(uuidProduct) {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.open("GET", "includes/removeShoppingCartItem.inc.php?uuidProduct=" + uuidProduct);
    xmlHttp.send(null);
    
    //reload shopping cart
    loadShoppingCart();
    
    //refresh shopping cart icon
    refreshShoppingCartIcon();
}

//empty shopping cart
function emptyShoppingCart() {
    var xmlHttp = new XMLHttpRequest();

    xmlHttp.open("GET", "includes/emptyShoppingCart.inc.php");
    xmlHttp.send(null);
    
    //reload shopping cart
    loadShoppingCart();
    
    //refresh shopping cart icon
    refreshShoppingCartIcon();
}

//refresh shopping cart icon
function refreshShoppingCartIcon() {
    $(document).ready(function (){
        $.ajax({
            url: "includes/refreshShoppingCartIcon.inc.php",
            method: "POST"
        })

        .done(function (data) {
            $("#shoppingCartIcon").html(data);
        })
    })
}