let cartas = [];
let seleccionadas = [];
let aciertos = 0;
let intentos = 0;
let puedeSeleccionar = false;

const tablero = document.getElementById("tablero");
const mensaje = document.getElementById("mensaje");

// Genera un tablero de 4x4 con pares de imágenes (o emojis)
function generarCartas() {
  const iconos = ["🍎", "🍌", "🍒", "🍇", "🍉", "🍓", "🍍", "🥝"];
  cartas = [...iconos, ...iconos].sort(() => 0.5 - Math.random());
}

function dibujarTablero() {
  tablero.innerHTML = "";
  let index = 0;
  for (let i = 0; i < 4; i++) {
    const fila = document.createElement("tr");
    for (let j = 0; j < 4; j++) {
      const celda = document.createElement("td");
      celda.dataset.index = index;
      celda.classList.add("carta");
      celda.addEventListener("click", () => seleccionarCarta(celda));
      fila.appendChild(celda);
      index++;
    }
    tablero.appendChild(fila);
  }
}

function iniciarJuego() {
  generarCartas();
  dibujarTablero();
  aciertos = 0;
  intentos = 0;
  mensaje.textContent = "Encuentra todas las parejas!";
  puedeSeleccionar = true;
}

function reiniciarJuego() {
  iniciarJuego();
}

function seleccionarCarta(celda) {
  if (!puedeSeleccionar) return;
  const index = celda.dataset.index;

  // Evita volver a seleccionar la misma carta
  if (seleccionadas.includes(celda) || celda.textContent !== "") return;

  celda.textContent = cartas[index];
  seleccionadas.push(celda);

  if (seleccionadas.length === 2) {
    puedeSeleccionar = false;
    intentos++;
    setTimeout(() => {
      compararCartas();
      puedeSeleccionar = true;
    }, 800);
  }
}

function compararCartas() {
  const [carta1, carta2] = seleccionadas;

  if (carta1.textContent === carta2.textContent) {
    aciertos++;
    carta1.style.backgroundColor = "#90EE90";
    carta2.style.backgroundColor = "#90EE90";
    if (aciertos === 8) {
      mensaje.textContent = `¡Ganaste en ${intentos} intentos! 🎉`;
      sumarPuntos();
    }
  } else {
    carta1.textContent = "";
    carta2.textContent = "";
  }

  seleccionadas = [];
}

// 🟢 SUMAR PUNTOS AL TERMINAR
function sumarPuntos() {
  let puntosGanados = 0;

  if (intentos <= 12) puntosGanados = 100;
  else if (intentos <= 20) puntosGanados = 70;
  else if (intentos <= 30) puntosGanados = 40;
  else puntosGanados = 20;

  fetch("../../backend/controladores/actualizar_puntos.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "puntos=" + puntosGanados
  })
  .then(res => res.text())
  .then(data => {
    console.log(data);
    alert(`¡Ganaste ${puntosGanados} puntos!`);
  })
  .catch(err => console.error("Error al actualizar puntos:", err));
}
