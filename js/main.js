"use strict";

//generate random psw
function generateRandomPsw() {
    //vars
    var pswField = document.getElementById("psw");
    pswField.value = "";
    var randomLength = Math.floor(Math.random() * (32 - 16 + 1) ) + 16;
    var characters = ["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0","!","@","#","$","%","^","&","*","(",")","{","}","|","[","]",";","'",":","<",">","?","/"];
    //generate psw
    for (var i = 0; i < randomLength; i++) {
        var randomPsw = characters[Math.floor(Math.random()*characters.length)];
        pswField.value += randomPsw;
    }
}

//show psw toggle
function pswToggle() {
    var pswCheckbox = document.getElementById("pswCheckbox");
    var pswField = document.getElementById("psw");
    if (pswCheckbox.checked == true) {
         pswField.type = "text";
    } else {
        pswField.type = "password";
    }
}