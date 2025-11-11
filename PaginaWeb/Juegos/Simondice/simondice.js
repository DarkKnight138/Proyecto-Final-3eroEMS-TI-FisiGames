// Lista de colores disponibles
var colores = ["rojo", "verde", "azul", "amarillo"];

// Variables principales del juego
var secuencia = []; // Guarda la secuencia generada por el juego
var jugador = []; // Guarda la secuencia elegida por el jugador
var puedeJugar = false; // Controla si el jugador puede tocar botones
var temporizadorInactividad; // Controla el tiempo sin tocar botones
var puntuacion = 0; // Guarda los puntos totales del jugador

// Sonidos del juego
var sonidoRojo = new Audio("sonidos/1.mp3");
var sonidoVerde = new Audio("sonidos/2.mp3");
var sonidoAzul = new Audio("sonidos/3.mp3");
var sonidoAmarillo = new Audio("sonidos/4.mp3");
var sonidoPierde = new Audio("sonidos/pierde.mp3");
var sonidoEmpieza = new Audio("sonidos/empieza.mp3");
var sonidoNivel10 = new Audio("sonidos/empieza.mp3");

// Imágenes de los botones
var imgRojo = "imgs/rojo.webp";
var imgVerde = "imgs/verde.avif";
var imgAzul = "imgs/azul.png";
var imgAmarillo = "imgs/amarillo.jpg";

// Imágenes cuando el botón está encendido
var imgRojoOn = "imgs/rojo1.webp";
var imgVerdeOn = "imgs/verde1.avif";
var imgAzulOn = "imgs/azul1.jpeg";
var imgAmarilloOn = "imgs/amarillo2.avif";

// Elementos del DOM
const mensaje = document.getElementById("mensaje");
const marcador = document.getElementById("puntos");
document.getElementById("start-btn").addEventListener("click", comenzarJuego);

// Muestra un mensaje en pantalla
function mostrarMensaje(texto, color = "#00ffe7") {
	mensaje.style.color = color;
	mensaje.textContent = texto;
}

// Actualiza el texto del marcador de puntos
function actualizarPuntuacion() {
	if (marcador) marcador.textContent = "Puntuación: " + puntuacion;
}

// Inicia el juego
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

// Eventos de clic para cada color
document.getElementById("rojo").addEventListener("click", () => tocarColor("rojo"));
document.getElementById("verde").addEventListener("click", () => tocarColor("verde"));
document.getElementById("azul").addEventListener("click", () => tocarColor("azul"));
document.getElementById("amarillo").addEventListener("click", () => tocarColor("amarillo"));

// Acción al tocar un color
function tocarColor(color) {
	if (!puedeJugar) return;
	reiniciarTemporizador(); // reinicia el tiempo de inactividad
	reproducirColor(color); // reproduce sonido y luz
	jugador.push(color); // agrega el color elegido al array
	verificar(jugador.length - 1); // verifica si coincide con la secuencia
}

// Genera un nuevo nivel
function nuevoNivel() {
	puedeJugar = false;
	var numero = Math.floor(Math.random() * 4);
	var color = colores[numero];
	secuencia.push(color);
	jugador = [];

	// Reproduce sonido especial cada 10 niveles
	if ((secuencia.length - 1) > 0 && (secuencia.length - 1) % 10 == 0) {
    	sonidoNivel10.play();
	}

	mostrarMensaje("Nivel " + secuencia.length);
	mostrarSecuencia(0);
}

// Muestra la secuencia de colores al jugador
function mostrarSecuencia(i) {
	if (i < secuencia.length) {
    	var color = secuencia[i];
    	reproducirColor(color);
    	setTimeout(() => mostrarSecuencia(i + 1), 800);
	} else {
    	setTimeout(() => {
        	puedeJugar = true;
        	mostrarMensaje("Tu turno - Nivel " + secuencia.length);
        	reiniciarTemporizador(); // empieza el contador de inactividad
    	}, 500);
	}
}

// Reproduce el sonido y la imagen de un color
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

// Verifica si el jugador acertó la secuencia
function verificar(pos) {
	if (jugador[pos] != secuencia[pos]) {
    	perder("¡Perdiste! Llegaste al nivel " + secuencia.length);
    	return;
	}

	// Si completó correctamente el nivel
	if (jugador.length == secuencia.length) {
    	puntuacion += 5; // suma 5 puntos por nivel
    	actualizarPuntuacion();
    	sumarPuntos(); // llama al backend
    	mostrarMensaje("Nivel " + secuencia.length + " completado");
    	clearTimeout(temporizadorInactividad);
    	setTimeout(nuevoNivel, 1000);
	}
}

// Pierde por error o por inactividad
function perder(texto) {
	sonidoPierde.play();
	mostrarMensaje(texto, "#ff4b4b");
	puedeJugar = false;
	clearTimeout(temporizadorInactividad);
}

// Reinicia el temporizador de 10 segundos sin tocar nada
function reiniciarTemporizador() {
	clearTimeout(temporizadorInactividad);
	temporizadorInactividad = setTimeout(() => {
    	if (puedeJugar) {
        	perder("¡Tiempo agotado! Tardaste más de 10 segundos");
    	}
	}, 10000);
}

// Envía los puntos al backend en PHP
function sumarPuntos() {
	const puntosGanados = 5; // cada nivel vale 5 puntos
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
