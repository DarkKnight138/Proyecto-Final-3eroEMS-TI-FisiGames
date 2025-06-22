const toggleBtn = document.querySelector('.toggle_btn');
const dropDownMenu = document.querySelector('.dropdown_menu');

toggleBtn.onclick = function () {
    dropDownMenu.classList.toggle('open');
};

function abrirSignUp() {
    var formSignUp = document.getElementById("formSignUp");
    formSignUp.style.display = "flex";
}
function abrirLogin() {
    var formLogin = document.getElementById("formLogin");
    formLogin.style.display = "flex";
}

function cerrarSignUp() {
    var formSignUp = document.getElementById("formSignUp");
    formSignUp.style.display = "none";
}
function cerrarLogin() {
    var formLogin = document.getElementById("formLogin");
    formLogin.style.display = "none";
}