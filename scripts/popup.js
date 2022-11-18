const POPUP_OFFSET = 20;
let popups = new Array();

function positionPopups(array) {
    let totalOffset = 0;
    for ( let i=1; i<array.length; i++ ) {
        for ( let j=0; j<i; j++ ) {
            totalOffset += array[j].offsetHeight + POPUP_OFFSET;
        }
        totalOffset += POPUP_OFFSET;
        array[i].style.top = totalOffset + "px";
        totalOffset = 0;
    }
}

const POPUP_APPEAR_TIME = 0.4; //in seconds

class Popup {
    constructor(message, color, duration) {
        this.message = message;
        this.color = color;
        this.duration = duration;
    }

    pop() {
        let popup = document.createElement("div");
        popup.classList.add("popup");
        popup.classList.add(this.color);
        popup.style.setProperty("--duration", this.duration);
        popup.innerText = this.message;
        document.body.appendChild(popup);
        popups.unshift(popup);
        positionPopups(popups);
        setTimeout(() => {
            popups.splice( popups.indexOf(popup), 1 );
            document.body.removeChild(popup);
            positionPopups(popups);
        }, ( POPUP_APPEAR_TIME * 2 + parseFloat(this.duration) ) * 1000 );
    }
}

function pop( message, color, duration ) {
    let popup = new Popup(message, color, duration);
    popup.pop();
}