const changeBtns = document.querySelectorAll('.setting-change');
const changeSubmit = document.querySelector('#settings-submit');

changeBtns.forEach( (changeBtn) => {
    changeBtn.onclick = () => {
        changeBtn.parentElement.querySelector('input').classList.remove('showcase');
        changeBtn.classList.add('hidden');
        changeSubmit.classList.remove('off');
    }
} )