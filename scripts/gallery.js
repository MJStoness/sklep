const mainImg = document.querySelector(".main-img");
const imgs = document.querySelectorAll(".imgs-container img");

imgs.forEach(img => {
    img.onclick = () => {
        const src = img.src;
        mainImg.src = src;
    }
});