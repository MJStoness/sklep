const changeBtn = document.querySelector('#change-settings');
const settings = document.querySelectorAll('.user-setting-container input');
const changeSubmit = document.querySelector('#settings-submit');

changeBtn.onclick = () => {
    changeBtn.classList.add('hidden');
    changeSubmit.classList.remove('off');
    settings.forEach( (setting) => {
        setting.classList.remove('showcase');
    });
}