const cover = document.querySelector(".cover");
const alert = document.querySelector(".alert");

const editBtns = document.querySelectorAll(".category-edti-controlls-container a");
const delBtns = document.querySelectorAll(".category-edti-controlls-container .delete-product");

const addBtn = document.querySelector("#add-category");
const cancelBtn = document.querySelector("#cancel-category");

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

alert.querySelector("button").onclick = () => {
    hideAlert();

    const id = alert.getAttribute("data-category-id");
    const xhrAdd = new XMLHttpRequest();
    xhrAdd.open("POST","php_scripts/A_deleteCategory.php");
    xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhrAdd.send("id="+id);
    xhrAdd.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let popup = JSON.parse(this.responseText);
            pop(popup["message"], popup["color"], "1s");
            if ( popup["color"] == "green" ) {
                document.querySelector('.category-edit-entrie[data-id="'+id+'"]').remove();
            }
        }
    };

}

delBtns.forEach(delBtn => {
   delBtn.onclick = () => {
        alert.setAttribute("data-category-id", delBtn.getAttribute("data-category-id"));
        alert.querySelector("ul").innerHTML = "<li>"+delBtn.getAttribute("data-category-name")+"</li>";
        showAlert(); 
    }
});

editBtns.forEach(editBtn => {
    editBtn.onclick = () => {
        const titleContainer = editBtn.parentElement.parentElement.children[0];
        if ( editBtn.getAttribute("data-toggle") == "yes" ) {
            editBtn.setAttribute("data-toggle", "no")
            editBtn.innerHTML = "<i class='fa-solid fa-pen-to-square fa-xl'></i>";

            const name = titleContainer.children[0].value;
            const id = titleContainer.parentElement.getAttribute("data-id");

            const xhrAdd = new XMLHttpRequest();
            xhrAdd.open("POST","php_scripts/A_changeCategoryName.php");
            xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhrAdd.send("id="+id+"&name="+name);
            xhrAdd.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let popup = JSON.parse(this.responseText);
                    pop(popup["message"], popup["color"], "1s");
                    if ( popup["color"] == "green" ) {
                        titleContainer.innerHTML = name;
                    }
                }
            };
        } else {
            editBtn.setAttribute("data-toggle", "yes")
            editBtn.innerHTML = "<i class='fa-solid fa-check fa-xl'></i>";
            titleContainer.innerHTML = "<input type='text' value='"+ titleContainer.innerText +"'>";
        }
    }
});

addBtn.onclick = () => {
    const addForm = document.querySelector("#new-category");
    if ( addBtn.getAttribute("data-toggle") == "yes" ) {
        addBtn.setAttribute("data-toggle", "no")
        addBtn.classList = "fa-solid fa-plus";
        addForm.style.visibility = "hidden";
        cancelBtn.style.display = "none";

        let name = addForm.querySelector("input").value;

        const xhrAdd = new XMLHttpRequest();
        xhrAdd.open("POST","php_scripts/A_addCategory.php");
        xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhrAdd.send("name="+name);
        xhrAdd.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let popup = JSON.parse(this.responseText);
                pop(popup["message"], popup["color"], "1s");
                if ( popup["color"] == "green" ) {
                    location.reload();
                }
            }
        };

    } else {
        addBtn.setAttribute("data-toggle", "yes")
        addBtn.classList = "fa-solid fa-check";
        addForm.style.visibility = "visible";
        cancelBtn.style.display = "block";
    }
}