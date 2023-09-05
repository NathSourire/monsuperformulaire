let password1 = document.getElementById('passWord1');
let password2 = document.getElementById('passWord2');
let form = document.getElementById('form');
let passwordMessage = document.getElementById('passwordMessage');

const checkPassword = (e) => {
    if (password1.value !== password2.value){
        passwordMessage.innerHTML = 'Les mots de passe ne sont pas identique '
    }else {
        passwordMessage.innerHTML = ''
    }
}
const checkForm = (event) => {
    event.preventDefault();
    if (password1.value == password2.value && password1 != ''){
        form.submit();
    }else{
        passwordMessage.innerHTML = 'Les mots de passe ne sont pas identique '
    }
}
password1.addEventListener("change", checkPassword);
password2.addEventListener("keyup", checkPassword);
form.addEventListener("submit", checkForm);