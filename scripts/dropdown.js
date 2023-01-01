const dropdownContainers = document.querySelectorAll(".xdropdown-container");

dropdownContainers.forEach( dropdownContainer => {
    const dropdownToggle = dropdownContainer.querySelector(".xdropdown-header");
    const dropdownContent = dropdownContainer.querySelector(".xdropdown-content");
    const dropdownIcon = dropdownToggle.querySelector(".dropdown-icon-container .dropdown-icon");

    const originalHeight = parseInt(getComputedStyle(dropdownContainer).height);

    const hideDropdown = () => {
        dropdownToggle.setAttribute("data-toggle", "off");
        dropdownContent.style.transform = "scaleY(0)";
        dropdownIcon.style.transform = "rotate(0)";
        
        let containerHeight = getComputedStyle(dropdownContainer).height;
        let contentHeight = getComputedStyle(dropdownContent).height;
        dropdownContainer.style.height = parseInt(containerHeight) - parseInt(contentHeight) - 15 + "px";
    }

    const showDropdown = () => {
        dropdownToggle.setAttribute("data-toggle", "on");
        dropdownContent.style.transform = "scaleY(1)";
        dropdownIcon.style.transform = "rotate(180deg)";

        dropdownContainer.style.height = originalHeight + "px";
    }

    if ( dropdownToggle.getAttribute("data-toggle") == "off" ) {
        hideDropdown();
    } else {
        showDropdown();
    }

    dropdownToggle.onclick = (e) => {
        if ( dropdownToggle.getAttribute("data-toggle") == "on" ) {
            hideDropdown();
        } else {
            showDropdown();
        }
    }
});

// ======= filter button stuff

const filterBtn = document.querySelector(".filter-container");
const filterForm = document.querySelector("#category-from");

filterForm.onchange = () => {
    filterBtn.querySelector("input").style.color = "#fff";
    filterBtn.querySelector("i").style.color = "#fff";
}