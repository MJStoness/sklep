const DROP_AREA = document.querySelector(".add-photo-container");
const DROP_SQUARE = document.querySelector(".input-file-container");

const FILE_INPUT = document.querySelector("#addPhoto");

const FILE_PREVIEW = document.querySelector(".file-preview");

const ALLOWED_TYPES = ["image/jpeg", "image/png", "image/webp", "image/avif"]

const active = () => {
    DROP_SQUARE.classList.add("highlighted");
}

const inactive = () => {
    DROP_SQUARE.classList.remove("highlighted");
}

['dragenter','dragover','dragleave','drop'].forEach( eventName => {
    DROP_AREA.addEventListener(eventName, (e) => { e.preventDefault() } );      
});

['dragenter','dragover','onmouseenter'].forEach( eventName => {
    DROP_AREA.addEventListener(eventName, active);
});

['dragleave','drop'].forEach( eventName => {
    DROP_AREA.addEventListener(eventName, inactive);
});

function updateFilePreview() {
    for ( let i=0; i<FILE_INPUT.files.length; i++ ) {
        FILE_PREVIEW.innerHTML = "";
        let reader = new FileReader();

        reader.onload = (e) => {
            const imgfile = e.target;
            const img = document.createElement("img");
            img.src = imgfile.result;
            FILE_PREVIEW.appendChild(img);
        }
        reader.readAsDataURL(FILE_INPUT.files[i]);
    }
}

DROP_AREA.addEventListener('drop', (e) => {
    // ============================== INPUTING ALL FILES INTO THE <input file> ELEMENT
    let loadedFiles = FILE_INPUT.files;
    let newFiles = e.dataTransfer.files;

    let tempDataTransfer = new DataTransfer();

    for ( let i=0; i<loadedFiles.length; i++ ) {
        tempDataTransfer.items.add(loadedFiles[i]);
    }

    for ( let i=0; i<newFiles.length; i++ ) {
        if ( ALLOWED_TYPES.includes(newFiles[i].type) ) {
            tempDataTransfer.items.add(newFiles[i]);
        } else {
            pop("Nieprawidłowy typ pliku!", "red", "2s");
        }
    }

    FILE_INPUT.files = tempDataTransfer.files;
    // ===============================================================================

    updateFilePreview();

});