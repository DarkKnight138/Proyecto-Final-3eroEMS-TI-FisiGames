const colores = ["rojo", "verde", "azul", "amarillo"];
let secuencia = [];
let secuenciaJugador = [];
let nivel = 0;
let puedeJugar = false;

const startBtn = document.getElementById("start-btn");
const nivelTexto = document.getElementById("nivel-texto");

startBtn.addEventListener("click", iniciarJuego);

function iniciarJuego() {
  nivel = 0;
  secuencia = [];
  secuenciaJugador = [];
  siguienteNivel();
}

function siguienteNivel() {
  nivel++;
  nivelTexto.textContent = "Nivel: " + nivel;
  secuenciaJugador = [];
  puedeJugar = false;

  const colorAleatorio = colores[Math.floor(Math.random() * 4)];
  secuencia.push(colorAleatorio);

  reproducirSecuencia();
}

function reproducirSecuencia() {
  let i = 0;
  const intervalo = setInterval(() => {
    iluminarColor(secuencia[i]);
    i++;
    if (i >= secuencia.length) {
      clearInterval(intervalo);
      puedeJugar = true;
    }
  }, 800);
}

function iluminarColor(color) {
  const boton = document.getElementById(color);
  const sonido = new Audio("sounds/" + color + ".mp3");
  sonido.play();
  boton.classList.add("activo");
  setTimeout(() => boton.classList.remove("activo"), 400);
}

// Detectar clics del jugador
colores.forEach(color => {
  document.getElementById(color).addEventListener("click", () => manejarInput(color));
});

function manejarInput(color) {
  if (!puedeJugar) return;
  const sonido = new Audio("sounds/" + color + ".mp3");
  sonido.play();

  secuenciaJugador.push(color);
  verificarRespuesta();
}

function verificarRespuesta() {
  const i = secuenciaJugador.length - 1;

  if (secuenciaJugador[i] !== secuencia[i]) {
    perder();
    return;
  }

  if (secuenciaJugador.length === secuencia.length) {
    if (nivel === 10) {
      ganar();
    } else {
      setTimeout(siguienteNivel, 1000);
    }
  }
}

function perder() {
  const sonido = new Audio("sounds/perder.mp3");
  sonido.play();
  alert("¡Perdiste en el nivel " + nivel + "!");
  sumarPuntos(false);
}

function ganar() {
  alert("¡Ganaste! Superaste los 10 niveles 🎉");
  sumarPuntos(true);
}

// 🟢 SUMA DE PUNTOS
function sumarPuntos(gano) {
  let puntos = 0;

  if (gano) puntos = 100;
  else if (nivel >= 5) puntos = 50;
  else puntos = 20;

  fetch("../../backend/controladores/actualizar_puntos.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "puntos=" + puntos
  })
  .then(res => res.text())
  .then(data => {
    console.log(data);
    alert("Has ganado " + puntos + " puntos.");
  })
  .catch(err => console.error("Error al actualizar puntos:", err));
}
