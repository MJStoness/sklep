const photos = document.querySelectorAll(".photo-container");
const cover = document.querySelector(".cover");
const alert = document.querySelector(".alert");

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
    chosenToDelete = new Array();
    hideAlert();
}

alert.querySelector("button").onclick = () => {
    hideAlert();

    const id = alert.getAttribute("data-photo-id");
    const xhrAdd = new XMLHttpRequest();
    xhrAdd.open("POST","php_scripts/A_deletePhoto.php");
    xhrAdd.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhrAdd.send("id="+id);
    xhrAdd.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let popup = JSON.parse(this.responseText);
            pop(popup["message"], popup["color"], "1s");
            if ( popup["color"] == "green" ) {
                document.querySelector(".photo-container[data-photo-id='"+id+"']").remove()
            }
        }
    };

}

photos.forEach(photo => {
    photo.onclick = () => {
        let src = photo.querySelector("img").src;
        alert.querySelector("img").src = src;
        alert.setAttribute("data-photo-id", photo.getAttribute("data-photo-id"));
        showAlert(); 
    }
});