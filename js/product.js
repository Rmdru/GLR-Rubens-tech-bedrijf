"use strict";

function addToCart(uuid) {
    $(document).ready(function (){
        var amount = $("#amount").val();

        $.ajax({
            url: "includes/addToCart.inc.php",
            method: "POST",
            data: {
                'uuid': uuid,
                'amount': amount
            }
        })

        .done(function (data) {
            if (data == "success") {
                $("#shoppingCartResponse").html("<p class='txt txtColorGreen'><i class='material-icons verticalCentered'>check</i> Product succesvol toegevoegd aan winkelwagen</p><a class='primaryBtn' href='winkelwagen.php'><i class='material-icons verticalCentered'>shopping_cart</i> Winkelwagen bekijken & producten bestellen</a><br /><br />");

            } else {
                $("#shoppingCartResponse").html("<p class='txt txtColorRed'><i class='material-icons verticalCentered'>close</i> Er is iets fout gegaan, probeer het opnieuw</p><br /><br />");
            }
            refreshShoppingCartIcon();
        })
    })
}

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

function loadReviews(uuidProduct, status) {
    var xmlHttp = new XMLHttpRequest;

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var reviewsResponse = xmlHttp.responseText;

            document.getElementById("reviewsResponse").innerHTML = reviewsResponse;
        }
    }

    xmlHttp.open("GET", "includes/loadReviews.inc.php?uuidProduct=" + uuidProduct + "&status=" + status);
    xmlHttp.send(null);
}

function submitReview(uuidProduct, csrfToken) {
    var xmlHttp = new XMLHttpRequest;
    var stars = document.getElementById("stars").value;
    var title = document.getElementById("title").value;
    var description = document.getElementById("description").value;
    var anonymous = document.getElementById("anonymous").value;
    if (document.getElementById("anonymous").checked == true) {
        anonymous = 1;
    } else {
        anonymous = 0;
    }

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var response = xmlHttp.responseText;

            document.getElementById("response").innerHTML = response;

            loadReviews(uuidProduct, 1);
        }
    }

    xmlHttp.open("GET", "includes/submitReview.inc.php?uuidProduct=" + uuidProduct + "&csrfToken=" + csrfToken + "&stars=" + stars + "&title=" + title + "&description=" + description + "&anonymous=" + anonymous);
    xmlHttp.send(null);
}