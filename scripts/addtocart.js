const BUTTONS = document.querySelectorAll(".cart-btn");

BUTTONS.forEach( (element) => {
    element.addEventListener('click', () => {
        const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/addToCart.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("product_id="+element.value);
        xhrAdd.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let popup = JSON.parse(this.responseText);
                pop(popup["message"], popup["color"], "1s");
                if ( popup["success"] == "yes" ) {
                    animateAddButtonSuccess(element);
                } else {
                    animateAddButtonFailure(element);
                }
            }
          };
    })
})
