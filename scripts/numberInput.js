const NUMBER_INPUTS = document.querySelectorAll(".custom-number-input");

NUMBER_INPUTS.forEach( numberInput => {
    const input = numberInput.querySelector("input");
    const min = input.getAttribute("min");
    const max = input.getAttribute("max");
    const step = input.getAttribute("step");

    const upBtn = numberInput.querySelector(".custom-number-input-controlls .count-up");
    const downBtn = numberInput.querySelector(".custom-number-input-controlls .count-down");

    let intervalUp;
    let intervalDown;

    const stepUp = () => {
        if ( parseInt(input.value) - parseInt(step) <= max ) {
            input.value = parseInt(input.value) + parseInt(step);
        }
    }
    const stepDown = () => {
        if ( parseInt(input.value) - parseInt(step) >= min ) {
            input.value = parseInt(input.value) - parseInt(step);
        }
    }

    upBtn.onclick = () => {
        stepUp();
    }
    upBtn.onmousedown = () => {
        intervalUp = setInterval( () => {
            stepUp()
        }, 100)
    }
    upBtn.onmouseup = () => {
        clearInterval(intervalUp);
    }
    upBtn.onmouseleave = () => {
        clearInterval(intervalUp);
    }

    downBtn.onclick = () => {
        stepDown();
    }
    downBtn.onmousedown = () => {
        intervalDown = setInterval( () => {
            stepDown()
        }, 100)
    }
    downBtn.onmouseup = () => {
        clearInterval(intervalDown);
    }
    downBtn.onmouseleave = () => {
        clearInterval(intervalDown);
    }

    input.onchange = () => {
        if ( parseInt(input.value) < min ) input.value = min;
        if ( parseInt(input.value) > max ) input.value = max;
        if ( input.value.length == 0 ) input.value = min;
    }
});