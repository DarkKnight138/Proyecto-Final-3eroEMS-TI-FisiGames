var colores = ["rojo", "verde", "azul", "amarillo"];
var secuencia = [];
var jugador = [];
var puedeJugar = false;

// 🎵 Sonidos
var sonidoRojo = new Audio("sonidos/1.mp3");
var sonidoVerde = new Audio("sonidos/2.mp3");
var sonidoAzul = new Audio("sonidos/3.mp3");
var sonidoAmarillo = new Audio("sonidos/4.mp3");
var sonidoPierde = new Audio("sonidos/pierde.mp3");
var sonidoEmpieza = new Audio("sonidos/empieza.mp3");
var sonidoNivel10 = new Audio("sonidos/empieza.mp3");

// 🖼️ Imágenes normales
var imgRojo = "imgs/rojo.webp";
var imgVerde = "imgs/verde.avif";
var imgAzul = "imgs/azul.png";
var imgAmarillo = "imgs/amarillo.jpg";

// 🖼️ Imágenes encendidas
var imgRojoOn = "imgs/rojo1.jpg";
var imgVerdeOn = "imgs/verde1.avif";
var imgAzulOn = "imgs/azul1.jpeg";
var imgAmarilloOn = "imgs/amarillo2.avif";

// 🎯 Iniciar juego
document.getElementById("start-btn").addEventListener("click", comenzarJuego);

function comenzarJuego() {
    sonidoEmpieza.play();
    secuencia = [];
    jugador = [];
    puedeJugar = false;
    setTimeout(nuevoNivel, 1000);
}

// 🧩 Clicks de colores
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

// 🚀 Nuevo nivel
function nuevoNivel() {
    puedeJugar = false;
    var numero = Math.floor(Math.random() * 4);
    var color = colores[numero];
    secuencia.push(color);
    jugador = [];

    if ((secuencia.length - 1) > 0 && (secuencia.length - 1) % 10 == 0) {
        sonidoNivel10.play();
    }

    mostrarSecuencia(0);
}

// 👀 Mostrar la secuencia
function mostrarSecuencia(i) {
    if (i < secuencia.length) {
        var color = secuencia[i];
        reproducirColor(color);
        setTimeout(() => mostrarSecuencia(i + 1), 800);
    } else {
        setTimeout(() => puedeJugar = true, 500);
    }
}

// 🔊 Reproducir sonido e iluminar
function reproducirColor(color) {
    var imagen = document.getElementById(color);
    if (color === "rojo") {
        imagen.src = imgRojoOn;
        sonidoRojo.play();
        setTimeout(() => imagen.src = imgRojo, 400);
    } else if (color === "verde") {
        imagen.src = imgVerdeOn;
        sonidoVerde.play();
        setTimeout(() => imagen.src = imgVerde, 400);
    } else if (color === "azul") {
        imagen.src = imgAzulOn;
        sonidoAzul.play();
        setTimeout(() => imagen.src = imgAzul, 400);
    } else if (color === "amarillo") {
        imagen.src = imgAmarilloOn;
        sonidoAmarillo.play();
        setTimeout(() => imagen.src = imgAmarillo, 400);
    }
}

// ✅ Verificar jugada
function verificar(pos) {
    if (jugador[pos] !== secuencia[pos]) {
        sonidoPierde.play();
        alert("¡Perdiste! Llegaste al nivel " + secuencia.length);
        puedeJugar = false;
        return;
    }

    // 🏆 Si completó el patrón
    if (jugador.length === secuencia.length) {
        sumarPuntos(10);
        console.log("¡Nivel superado! +10 puntos");
        setTimeout(nuevoNivel, 1000);
    }
}

// 📤 Enviar puntos al backend
function sumarPuntos(puntos) {
    fetch("../../backend/controladores/actualizar_puntos.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "puntos=" + puntos
    })
    .then(response => response.text())
    .then(data => console.log("Servidor:", data))
    .catch(error => console.error("Error al enviar puntos:", error));
}
