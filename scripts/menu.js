const MENU_TOGGLE = document.querySelector("#hamburger-checkbox");
const MENU_CONTAINER = document.querySelector(".menu-container");
const COVER = document.querySelector(".cover");

MENU_TOGGLE.onchange = function() {
    if ( MENU_TOGGLE.checked ) { MENU_CONTAINER.classList.remove("hidden"); COVER.style.opacity = "100"; }
    else { MENU_CONTAINER.classList.add("hidden"); COVER.style.opacity = "0"; }
}

const DROPDOWN_TOGGLES = document.querySelectorAll(".dropdown-checkbox");
const DROPDOWN_CONTENT = document.querySelectorAll(".dropdown-content")

DROPDOWN_TOGGLES.forEach( (element,index) => {
    element.addEventListener('click', event => {
        if ( element.checked ) DROPDOWN_CONTENT[index].classList.remove("hidden");
        else DROPDOWN_CONTENT[index].classList.add("hidden");
    })
})
