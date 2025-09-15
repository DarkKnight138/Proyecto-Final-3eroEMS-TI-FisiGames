var dinero = 1000;
function jugar(vasoElegido) {
 var apuesta = parseInt(document.getElementById("apuesta").value);
 if (isNaN(apuesta) || apuesta <= 0) {
     document.getElementById("mensaje").innerHTML = "<span class='incorrecta'>Ingresa una apuesta válida.</span>";
     return;
 }
 if (apuesta > dinero) {
     document.getElementById("mensaje").innerHTML = "<span class='incorrecta'>No tienes suficiente dinero.</span>";
     return;
 }
 // Limpia animaciones previas
 for (let i = 1; i <= 3; i++) {
     document.getElementById("vaso" + i).classList.remove("blink-green", "blink-red");
 }
 var vasoConPelota = Math.floor(Math.random() * 3) + 1;
 if (vasoElegido === vasoConPelota) {
     dinero += apuesta;
     document.getElementById("mensaje").innerHTML = "<span class='correcta'>¡Ganaste! La pelotita estaba en el vaso " + vasoConPelota + ".</span>";
     document.getElementById("vaso" + vasoConPelota).classList.add("blink-green");
 } else {
     dinero -= apuesta;
     document.getElementById("mensaje").innerHTML = "<span class='incorrecta'>Perdiste. La pelotita estaba en el vaso " + vasoConPelota + ".</span>";
     document.getElementById("vaso" + vasoConPelota).classList.add("blink-red");
 }
 document.getElementById("dineroTxt").innerHTML = "Tienes $" + dinero;
}
function reiniciarJuego() {
 dinero = 1000;
 document.getElementById("dineroTxt").innerHTML = "Tienes $1000";
 document.getElementById("mensaje").innerHTML = "";
 document.getElementById("apuesta").value = "";
 for (let i = 1; i <= 3; i++) {
     document.getElementById("vaso" + i).classList.remove("blink-green", "blink-red");
 }
}
const menuToggle = document.getElementById('menu-toggle');
const navbar = document.getElementById('navbar');
menuToggle.addEventListener('click', () => {
 navbar.classList.toggle('expanded');
});