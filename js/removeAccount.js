"use strict";

function enableDisabledBtnToggle(url) {
    var toggle = document.getElementById("toggleCheckbox");
    var btn = document.getElementById("disabledBtn");

    if (toggle.checked ==  true) {
        btn.classList.add("secondaryBtn");
        btn.classList.remove("disabledSecondaryBtn");
        btn.href = url;
    } else {
        btn.classList.add("disabledSecondaryBtn");
        btn.classList.remove("secondaryBtn");
        btn.href = "";
    }
}