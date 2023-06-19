const loadingScreen = document.querySelector("#loadingScreen");
const mainScreen = document.querySelector("#mainScreen");

mainScreen.style.display = 'none';
const loadingTime = 2000; //(ms)

setTimeout(() => {
    loadingScreen.style.display = 'none';
    mainScreen.style.display = 'flex';
}, loadingTime);