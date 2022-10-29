const UPBUTTONS = document.querySelectorAll('.cart-entry-quantity-up');
const DWBUTTONS = document.querySelectorAll('.cart-entry-quantity-down');
const QUANTITYFIELDS = document.querySelectorAll('.cart-entry-quantity input[type="number"]');
const STEP = 1;
const RECALCULATE_BUTTON = document.querySelector('#recalculate');

UPBUTTONS.forEach( (button,index) => {
    button.onclick = function () {
        if ( parseInt(QUANTITYFIELDS[index].value) < parseInt(QUANTITYFIELDS[index].max) ) { 
            QUANTITYFIELDS[index].value = parseInt(QUANTITYFIELDS[index].value) + STEP;
            const xhrAdd = new XMLHttpRequest();
            xhrAdd.open("POST","php_scripts/quantityCartControll.php");
            xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhrAdd.send("cart_entry_id="+button.getAttribute('entry_id')+"&quantity="+QUANTITYFIELDS[index].value);
            RECALCULATE_BUTTON.classList.remove('off');
        }
    }
} );
DWBUTTONS.forEach( (button,index) => {
    button.onclick = function () {
        if ( parseInt(QUANTITYFIELDS[index].value) > parseInt(QUANTITYFIELDS[index].min) ) { 
            QUANTITYFIELDS[index].value = parseInt(QUANTITYFIELDS[index].value) - STEP;
            const xhrAdd = new XMLHttpRequest();
            xhrAdd.open("POST","php_scripts/quantityCartControll.php");
            xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhrAdd.send("cart_entry_id="+button.getAttribute('entry_id')+"&quantity="+QUANTITYFIELDS[index].value);
            RECALCULATE_BUTTON.classList.remove('off');
        }
    }
} );

QUANTITYFIELDS.forEach( (field) => {
    field.onchange = function () {
        if ( field.value < field.min ) field.value = field.min;
        if ( field.value > field.max ) field.value = field.max;
    }
} );

/* const QUANTITYCONTROLLCONTAINERS = document.querySelectorAll('.cart-entry-quantity');
const STEP = 1;


class QuantityControll {
    constructor(parent) {
        this.parent = parent;
        this.upBtn = parent.querySelector(".cart-entry-quantity-up");
        this.dwBtn = parent.querySelector(".cart-entry-quantity-down");
        this.quantityField = parent.querySelector('input[type="number"]');
    }

    up() {
        if ( parseInt(this.quantityField.value) < parseInt(this.quantityField.max) ) { 
            this.quantityField.value = parseInt(this.quantityField.value) + STEP;
            this.ajaxSend();
        }
    }

    down() {
        if ( parseInt(this.quantityField.value) > parseInt(this.quantityField.min) ) { 
            this.quantityField.value = parseInt(this.quantityField.value) - STEP;
            this.ajaxSend();
        }
    }

    ajaxSend() {
        const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/quantityCartControll.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("cart_entry_id="+this.upBtn.getAttribute('entry_id')+"&quantity="+this.quantityField.value);
        RECALCULATE_BUTTON.classList.remove('off');
    }
}

let quantityControlls = new Array();
QUANTITYCONTROLLCONTAINERS.forEach ( (QuantityControllContainer) => {
    quantityControlls.push( new QuantityControll(QuantityControllContainer) )
} );

quantityControlls.forEach( (quantityControll) => {
    quantityControll.upBtn.onclick = function () { quantityControll.up() }
    quantityControll.dwBtn.onclick = function () { quantityControll.down() }
} ); */


RECALCULATE_BUTTON.onclick = function () {
    location.reload();
}