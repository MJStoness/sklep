const delBtns = document.querySelectorAll(".delete-product");
const cover = document.querySelector(".cover");
const alert = document.querySelector(".alert");
const checkboxes = document.querySelectorAll(".delete-product-checkbox");

const deleteSelectedBtnContainer = document.querySelector("#delete-selected-container");
const productEntriesContainer = document.querySelector(".product-edit-entries");

let chosenToDelete = new Array();

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

const clearAlert = () => {
    alert.querySelector("ul").innerHTML = "";
}

alert.querySelector("i").onclick = () => {
    chosenToDelete = new Array();
    hideAlert();
    setTimeout(() => {
        clearAlert();
    }, 200);
}

alert.querySelector("button").onclick = () => {
    hideAlert();
    setTimeout(() => {
        clearAlert();
    }, 200);

    let productIdsPOSTified = new Array();
    chosenToDelete.forEach( container => {
        const id = container.querySelector(".product-edti-controlls-container .delete-product").getAttribute("data-product-id");
        productIdsPOSTified.push("product_id%5B%5D="+id);
    });

    const xhrAdd = new XMLHttpRequest();
    xhrAdd.open("POST","php_scripts/A_deleteProduct.php");
    xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhrAdd.send(productIdsPOSTified.join('&'));
    xhrAdd.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let popup = JSON.parse(this.responseText);
            pop(popup["message"], popup["color"], "1s");
            if ( popup["color"] == "green" ) {
                chosenToDelete.forEach( container => {
                    container.remove();
                });
            }
        }
    };

    setTimeout(() => {
        chosenToDelete = new Array();
    }, 250);
}

const generateAlert = () => {
    checkboxes.forEach( checkbox => {
        if ( checkbox.checked ) {
            chosenToDelete.push(checkbox.parentElement.parentElement.parentElement);
        }
    });

    chosenToDelete.forEach( container => {
        const productName = container.querySelector("a .product-edti-details-container span span h2").innerText;
        alert.querySelector("ul").innerHTML += "<li>"+productName+"</li>";
    });

    showAlert();
}

delBtns.forEach( delBtn => {
    delBtn.querySelector("i").onclick = () => {
        const container = delBtn.parentElement.parentElement;
        if ( !container.querySelector(".product-edti-controlls-container .custom-checkbox input").checked ) {
            chosenToDelete.push(container);
        }
        generateAlert();
    }
});

//============================= CHECKBOXES, MULTIPLE DELETE


const countSelected = () => {
    let count = 0;
    checkboxes.forEach( checkbox => {
        if ( checkbox.checked ) count++;
    });

    return count;
}
const showDeleteBtn = () => {
    deleteSelectedBtnContainer.querySelector("button #product-count").innerText = countSelected();
    setTimeout(() => {
        deleteSelectedBtnContainer.style.opacity = 1;
        deleteSelectedBtnContainer.style.pointerEvents = "auto";
    }, 200);
    productEntriesContainer.style.marginTop = "80px";
}
const hideDeleteBtn = () => {
    deleteSelectedBtnContainer.style.opacity = 0;
    deleteSelectedBtnContainer.style.pointerEvents = "none";
    setTimeout(() => {
        productEntriesContainer.style.marginTop = "0px";
    }, 200);
}

const areTheyUnchecked = () => {
    let unchecked = true;
    checkboxes.forEach( checkbox => {
        if ( checkbox.checked ) unchecked = false;
    });

    return unchecked;
}

checkboxes.forEach( checkbox => {
    checkbox.onclick = () => {
        if ( checkbox.checked ) {
            showDeleteBtn();
        } else if ( areTheyUnchecked() ) {
            hideDeleteBtn();
        } else {
            showDeleteBtn();
        }
        
    }
});

deleteSelectedBtnContainer.querySelector("button").onclick = () => {
    generateAlert();
}

//=========================================================