const ELEMENTS_TO_MINIMIZE = document.querySelectorAll(".scroll-minimize");
const OFFSET_THRESHOLD = 100;

window.onscroll = function() {
    if( window.scrollY > OFFSET_THRESHOLD ) minimize(ELEMENTS_TO_MINIMIZE);
    if( window.scrollY < OFFSET_THRESHOLD ) maximize(ELEMENTS_TO_MINIMIZE);
}

function minimize(elements) {
    elements.forEach(element => {
        element.classList.add("small");
    });
}

function maximize(elements) {
    elements.forEach(element => {
        element.classList.remove("small");
    });
}
