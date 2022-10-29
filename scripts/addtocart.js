const BUTTONS = document.querySelectorAll(".cart-btn");

BUTTONS.forEach( (element,index) => {
    element.addEventListener('click', event => {
        const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/addToCart.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("product_id="+element.value);
    })
})
