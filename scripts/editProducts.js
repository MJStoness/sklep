const delBtns = document.querySelectorAll(".delete-product");
const cover = document.querySelector(".cover");
const alert = document.querySelector(".alert");
const checkboxes = document.querySelectorAll(".delete-product-checkbox");

const hideAlert = () => {
    cover.style.opacity = 0;
    cover.style.pointerEvents = "none";

    alert.style.opacity = 0;
    alert.style.pointerEvents = "none";
}
const showAlert = () => {
    cover.style.opacity = 1;
    cover.style.pointerEvents = "auto";

    alert.style.opacity = 1;
    alert.style.pointerEvents = "auto";
}

alert.querySelector("i").onclick = () => {
    hideAlert();
}

delBtns.forEach( delBtn => {
    delBtn.onclick = () => {
        const productId = delBtn.getAttribute("data-product-id");
        const productName = delBtn.parentElement.parentElement.querySelector("a .product-edti-details-container span span h2").innerText;

        alert.querySelector("ul").innerHTML = "<li>"+productName+"</li>";
        showAlert();

        alert.querySelector('.alert-confirm').onclick = () => {
            console.log(productName);
            hideAlert();
        }
        /* const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/A_deleteProduct.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("product_id="+productId);
        xhrAdd.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               let popup = JSON.parse(this.responseText);
               pop(popup["message"], popup["color"], "1s");
            }
        }; */
    }
});