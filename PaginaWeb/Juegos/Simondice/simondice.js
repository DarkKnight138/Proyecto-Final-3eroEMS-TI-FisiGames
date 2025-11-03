var colores = ["rojo", "verde", "azul", "amarillo"];
var secuencia = [];
var jugador = [];
var puedeJugar = false;
var temporizadorInactividad; 
var puntuacion = 0; 

var sonidoRojo = new Audio("sonidos/1.mp3");
var sonidoVerde = new Audio("sonidos/2.mp3");
var sonidoAzul = new Audio("sonidos/3.mp3");
var sonidoAmarillo = new Audio("sonidos/4.mp3");
var sonidoPierde = new Audio("sonidos/pierde.mp3");
var sonidoEmpieza = new Audio("sonidos/empieza.mp3");
var sonidoNivel10 = new Audio("sonidos/empieza.mp3");


var imgRojo = "imgs/rojo.webp";
var imgVerde = "imgs/verde.avif";
var imgAzul = "imgs/azul.png";
var imgAmarillo = "imgs/amarillo.jpg";

var imgRojoOn = "imgs/rojo1.webp";
var imgVerdeOn = "imgs/verde1.avif";
var imgAzulOn = "imgs/azul1.jpeg";
var imgAmarilloOn = "imgs/amarillo2.avif";

const mensaje = document.getElementById("mensaje");
const marcador = document.getElementById("puntos"); 
document.getElementById("start-btn").addEventListener("click", comenzarJuego);

function mostrarMensaje(texto, color = "#00ffe7") {
	mensaje.style.color = color;
	mensaje.textContent = texto;
}

function actualizarPuntuacion() {
	if (marcador) marcador.textContent = "Puntuación: " + puntuacion;
}

function comenzarJuego() {
	sonidoEmpieza.play();
	secuencia = [];
	jugador = [];
	puntuacion = 0; 
	actualizarPuntuacion();
	puedeJugar = false;
	mostrarMensaje("Jugando nivel 1...");
	setTimeout(nuevoNivel, 1000);
}

// Eventos de clic en colores
document.getElementById("rojo").addEventListener("click", () => tocarColor("rojo"));
document.getElementById("verde").addEventListener("click", () => tocarColor("verde"));
document.getElementById("azul").addEventListener("click", () => tocarColor("azul"));
document.getElementById("amarillo").addEventListener("click", () => tocarColor("amarillo"));

function tocarColor(color) {
	if (!puedeJugar) return;

	
	reiniciarTemporizador();

	reproducirColor(color);
	jugador.push(color);
	verificar(jugador.length - 1);
}

function nuevoNivel() {
	puedeJugar = false;
	var numero = Math.floor(Math.random() * 4);
	var color = colores[numero];
	secuencia.push(color);
	jugador = [];

	if ((secuencia.length - 1) > 0 && (secuencia.length - 1) % 10 == 0) {
    	sonidoNivel10.play();
	}

	mostrarMensaje("Nivel " + secuencia.length);
	mostrarSecuencia(0);
}

function mostrarSecuencia(i) {
	if (i < secuencia.length) {
    	var color = secuencia[i];
    	reproducirColor(color);
    	setTimeout(() => mostrarSecuencia(i + 1), 800);
	} else {
    	setTimeout(() => {
        	puedeJugar = true;
        	mostrarMensaje("Tu turno - Nivel " + secuencia.length);
        	reiniciarTemporizador(); 
    	}, 500);
	}
}

function reproducirColor(color) {
	var imagen = document.getElementById(color);
	let sonido, imgNormal, imgOn;

	switch (color) {
    	case "rojo": sonido = sonidoRojo; imgNormal = imgRojo; imgOn = imgRojoOn; break;
    	case "verde": sonido = sonidoVerde; imgNormal = imgVerde; imgOn = imgVerdeOn; break;
    	case "azul": sonido = sonidoAzul; imgNormal = imgAzul; imgOn = imgAzulOn; break;
    	case "amarillo": sonido = sonidoAmarillo; imgNormal = imgAmarillo; imgOn = imgAmarilloOn; break;
	}

	imagen.src = imgOn;
	sonido.play();
	setTimeout(() => { imagen.src = imgNormal; }, 400);
}

function verificar(pos) {
	if (jugador[pos] != secuencia[pos]) {
    	perder("¡Perdiste! Llegaste al nivel " + secuencia.length);
    	return;
	}

	if (jugador.length == secuencia.length) {
    	puntuacion += 5; 
    	actualizarPuntuacion();
    	sumarPuntos(); 
    	mostrarMensaje("Nivel " + secuencia.length + " completado ");
    	clearTimeout(temporizadorInactividad);
    	setTimeout(nuevoNivel, 1000);
	}
}


function perder(texto) {
	sonidoPierde.play();
	mostrarMensaje(texto, "#ff4b4b");
	puedeJugar = false;
	clearTimeout(temporizadorInactividad);
}


function reiniciarTemporizador() {
	clearTimeout(temporizadorInactividad);
	temporizadorInactividad = setTimeout(() => {
    	if (puedeJugar) {
        	perder("¡Tiempo agotado! Tardaste más de 10 segundos ⏰");
    	}
	}, 10000); 
}


function sumarPuntos() {
	const puntosGanados = 5; 
	fetch("../../backend/controladores/actualizar_puntos.php", {
    	method: "POST",
    	headers: { "Content-Type": "application/x-www-form-urlencoded" },
    	body: "puntos=" + puntosGanados
	})
    	.then(res => res.text())
    	.then(data => {
        	console.log(data);
    	})
    	.catch(err => console.error("Error al actualizar puntos:", err));
}

