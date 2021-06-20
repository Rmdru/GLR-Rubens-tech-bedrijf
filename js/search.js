"use strict";

//search with ajax
function search() {
    //vars
    var xmlHttp = new XMLHttpRequest;
    var searchField = document.getElementById("searchField").value;
    var csrfToken = document.getElementById("csrfToken").value;

    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            //get response
            var searchResults = xmlHttp.responseText;
            
            //show nothing when search field is empty
            if (searchField == "") {
                searchResults = "";
            }

            //show search results
            document.getElementById("searchResults").innerHTML = searchResults;
        }
    }

    //send request
    xmlHttp.open("GET", "includes/search.inc.php?searchField=" + searchField + "&csrfToken=" + csrfToken);
    xmlHttp.send(null);
}