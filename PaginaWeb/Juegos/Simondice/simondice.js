var colores = ["rojo", "verde", "azul", "amarillo"];
var secuencia = [];
var jugador = [];
var puedeJugar = false;

// Sonidos
var sonidoRojo = new Audio("sonidos/1.mp3");
var sonidoVerde = new Audio("sonidos/2.mp3");
var sonidoAzul = new Audio("sonidos/3.mp3");
var sonidoAmarillo = new Audio("sonidos/4.mp3");
var sonidoPierde = new Audio("sonidos/pierde.mp3");
var sonidoEmpieza = new Audio("sonidos/empieza.mp3");
var sonidoNivel10 = new Audio("sonidos/empieza.mp3");

// Imágenes
var imgRojo = "img/rojo.webp";
var imgVerde = "img/verde.avif";
var imgAzul = "img/azul.png";
var imgAmarillo = "img/amarillo.jpg";

var imgRojoOn = "img/rojo1.jpg";
var imgVerdeOn = "img/verde1.avif";
var imgAzulOn = "img/azul1.jpeg";
var imgAmarilloOn = "img/amarillo2.avif";

const mensaje = document.getElementById("mensaje");
document.getElementById("start-btn").addEventListener("click", comenzarJuego);

function mostrarMensaje(texto, color = "#00ffe7") {
    mensaje.style.color = color;
    mensaje.textContent = texto;
}

function comenzarJuego() {
    sonidoEmpieza.play();
    secuencia = [];
    jugador = [];
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
        }, 500);
    }
}

function reproducirColor(color) {
    var imagen = document.getElementById(color);
    let sonido, imgNormal, imgOn;

    switch (color) {
        case "rojo":
            sonido = sonidoRojo; imgNormal = imgRojo; imgOn = imgRojoOn; break;
        case "verde":
            sonido = sonidoVerde; imgNormal = imgVerde; imgOn = imgVerdeOn; break;
        case "azul":
            sonido = sonidoAzul; imgNormal = imgAzul; imgOn = imgAzulOn; break;
        case "amarillo":
            sonido = sonidoAmarillo; imgNormal = imgAmarillo; imgOn = imgAmarilloOn; break;
    }

    imagen.src = imgOn;
    sonido.play();
    setTimeout(() => { imagen.src = imgNormal; }, 400);
}

function verificar(pos) {
    if (jugador[pos] != secuencia[pos]) {
        sonidoPierde.play();
        mostrarMensaje("¡Perdiste! Llegaste al nivel " + secuencia.length, "#ff4b4b");
        puedeJugar = false;
        return;
    }

    if (jugador.length == secuencia.length) {
        mostrarMensaje("Nivel " + secuencia.length + " completado ");
        setTimeout(nuevoNivel, 1000);
    }
}
