const INPUTS = document.querySelectorAll("input[data-highlight='yes']");

INPUTS.forEach( (input) => {
    let label = document.querySelector("label[for='"+input.id+"']");
    input.onfocus = function () {
        label.classList.add('highlighted');
    }
    input.onblur = function () {
        label.classList.remove('highlighted');
    }
} );