const customSelections = document.querySelectorAll(".custom-select");

const hideAll = () => {
    customSelections.forEach( customSelect => {
        const header = customSelect.querySelector(".custom-select-header");
        const list = customSelect.querySelector(".custom-select-list");
        const chevron = header.querySelector("i");
    
        customSelect.setAttribute("data-toggle", "off");
        list.style.transform = "scaleY(0)";
        chevron.style.transform = "";
    });
}

customSelections.forEach( customSelect => {
    const header = customSelect.querySelector(".custom-select-header");
    const list = customSelect.querySelector(".custom-select-list");
    const radios = customSelect.querySelector(".custom-select-radio-container");
    const chevron = header.querySelector("i");
  
    const select = (option) => {
        header.querySelector("span").innerText = option.innerText;
      
        const id = option.getAttribute("for");
        const selectedRadio = radios.querySelector("#"+id);
      
        selectedRadio.checked = true;
    
        list.querySelectorAll("label[data-selected='true']").forEach( option => {
            option.removeAttribute("data-selected");
        });
        option.setAttribute("data-selected", "true");
    }
  
    const hideList = () => {
        customSelect.setAttribute("data-toggle", "off");
        list.style.transform = "scaleY(0)";
        chevron.style.transform = "";
    }
    const showList = () => {
        hideAll();
        customSelect.setAttribute("data-toggle", "on");
        list.style.transform = "scaleY(1)";
        chevron.style.transform = "rotate(180deg)";
    }
  
    header.onclick = () => {
        if ( customSelect.getAttribute("data-toggle") == "off" ) {
            showList();
        } else {
            hideList();
        }
    }
  
  
    list.querySelectorAll("label").forEach( option => {
        if ( option.getAttribute("data-selected") == "true" ) {
        select(option);
        }
        option.onclick = () => {
            select(option);
            hideList();
        }
    });
  
});