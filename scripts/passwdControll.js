const PASSWORD_INPUTS = document.querySelectorAll(".passwd-container");
console.log(PASSWORD_INPUTS);

PASSWORD_INPUTS.forEach( passwordInput => {
  let toggle = passwordInput.querySelector("i");
  let input = passwordInput.querySelector("input");
  toggle.onclick = () => {
    if ( toggle.getAttribute("data-toggled") == "toggled" ) {
      toggle.classList = "fa-solid fa-eye-low-vision";
      toggle.setAttribute("data-toggled", "untoggled");
      input.type = "password";
    } else {
      toggle.classList = "fa-solid fa-eye";
      toggle.setAttribute("data-toggled", "toggled");
      input.type = "text";
    }
  }
});