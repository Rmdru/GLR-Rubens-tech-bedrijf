"use strict";

//reload captcha
function reloadCaptcha() {
    //vars
	var xmlHttp = new XMLHttpRequest();

    //send ajax request
  	xmlHttp.open("GET", "includes/captcha.inc.php", true);
  	xmlHttp.send(null);
	
    //generate new captcha image
	document.getElementById("captchaImg").src = "img/captcha/captchaImg.php";
}

//send form with ajax
function register() {
    //vars
    var xmlHttp = new XMLHttpRequest();
    var csrfToken = document.getElementById("csrfToken").value;
    var firstName = document.getElementById("firstName").value;
    var insertion = document.getElementById("insertion").value;
    var lastName = document.getElementById("lastName").value;
    var email = document.getElementById("email").value;
    var psw = document.getElementById("psw").value;
    var pswRepeat = document.getElementById("pswRepeat").value;
    var captcha = document.getElementById("captcha").value;

    //place errors in error element
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4) {
            var response = xmlHttp.responseText;

            document.getElementById("response").innerHTML = response;
        }
    }

    //send ajax request
    xmlHttp.open("GET", "includes/register.inc.php?csrfToken=" + csrfToken + "&firstName=" + firstName+ "&insertion=" + insertion + "&lastName=" + lastName + "&email=" + email + "&psw=" + psw + "&pswRepeat=" + pswRepeat + "&captcha=" + captcha, true);
    xmlHttp.send(null);
}