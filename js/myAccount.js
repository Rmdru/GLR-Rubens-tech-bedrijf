"use strict";

function openTab(evt, tabName) {
    var i, tabContent, tabLink;
    tabContent = document.getElementsByClassName("tabContent");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }
    tabLink = document.getElementsByClassName("tabLink");
    for (i = 0; i < tabLink.length; i++) {
        tabLink[i].className = tabLink[i].className.replace(" tabLinkActive", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " tabLinkActive";
}

document.getElementById("defaultTab").click();