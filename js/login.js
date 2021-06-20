"use strict";

//send form with ajax
function login() {
    //vars
    var xmlHttp = new XMLHttpRequest();
    var csrfToken = document.getElementById("csrfToken").value;
    var email = document.getElementById("email").value;
    var psw = document.getElementById("psw").value;
    var autologin = document.getElementById("autologinCheckbox");

    if (autologin.checked == true) {
        autologin = 1;
    } else {
        autologin = 0;
    }

    //place errors in error element
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var response = xmlHttp.responseText;

            if (response == "redirectAccountDashboard") {
                window.location.href = "mijnAccount.php";
            } else {
                document.getElementById("response").innerHTML = response;
            }
        }
    }

    //send ajax request
    xmlHttp.open("GET", "includes/login.inc.php?csrfToken=" + csrfToken + "&email=" + email + "&psw=" + psw + "&autologin=" + autologin, true);
    xmlHttp.send(null);
}