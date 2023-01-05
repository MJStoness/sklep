const BUTTONS = document.querySelectorAll(".cart-btn");

BUTTONS.forEach( (element,index) => {
    element.addEventListener('click', event => {
        element.style.animation = "cart-press 0.4s linear 0s 1";
        setTimeout(() => {
            element.style.animation = "";
        }, 400);
        const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/addToCart.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("product_id="+element.value);
        xhrAdd.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               let popup = JSON.parse(this.responseText);
               pop(popup["message"], popup["color"], "1s");
            }
          };
    })
})
