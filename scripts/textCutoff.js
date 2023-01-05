const texts = document.querySelectorAll(".textCutoff");

texts.forEach( text => {
    if ( parseInt(getComputedStyle(text).height) > 50 ) {
        text.classList.add("cutoff");
    }
});