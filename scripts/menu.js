const MENU_TOGGLE = document.querySelector(".hamburger-container input[type='checkbox']");
const MENU_CONTAINER = document.querySelector(".menu-container");
const COVER = document.querySelector(".cover");


MENU_TOGGLE.onchange = () => {
    if ( MENU_TOGGLE.checked ) { MENU_CONTAINER.classList.remove("hidden"); COVER.style.opacity = "100"; }
    else { MENU_CONTAINER.classList.add("hidden"); COVER.style.opacity = "0"; }
}