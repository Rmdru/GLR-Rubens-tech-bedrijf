"use strict";

//load products with ajax
function loadProducts(type, sortBy, keyword = "", price = "", deliveryTime = 14) {
    var xmlHttp = new XMLHttpRequest;

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var response = xmlHttp.responseText;

            document.getElementById("products").innerHTML = response;
        }
    }

    xmlHttp.open("GET", "includes/loadProducts.inc.php?type=" + type + "&sortBy=" + sortBy + "&keyword=" + keyword + "&price=" + price + "&deliveryTime=" + deliveryTime);
    xmlHttp.send(null);
}

//show delivery time slider value
function showDeliveryTimeSliderValue(value) {
    if (value == 14) {
        value = "14 of meer";
    }
    document.getElementById("deliveryTimeSliderValue").innerHTML = value;
}

//clear filters
function clearFilter(type, sortBy) {
    //reset fields
    $("#keyword").val("");
    $("#price").val("");
    $("#deliveryTime").val(14);

    //reload products
    loadProducts(type, sortBy, "", "", 14);

    //change delivery time slider value
    document.getElementById("deliveryTimeSliderValue").innerHTML = 14;

    //change slider value txt
    $("#deliveryTimeSliderValue").html("14 of meer");
}