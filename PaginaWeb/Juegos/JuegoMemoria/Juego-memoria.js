let cartas = []; // Guarda las cartas (imágenes duplicadas)
let seleccionadas = []; // Guarda las cartas seleccionadas
let aciertos = 0; // Cantidad de pares encontrados
let intentos = 0; // Número de intentos realizados
let puedeSeleccionar = false; // Controla si se puede seleccionar cartas

const tablero = document.getElementById("tablero"); // Contenedor del tablero
const mensaje = document.getElementById("mensaje"); // Elemento para mostrar mensajes

// Imágenes de los agentes (carpeta img/)
const imagenes = [
  "img/Brim.jpg",
  "img/Chamber.jpg",
  "img/Fenix.jpg",
  "img/gekko.jpg",
  "img/jett.jpg",
  "img/Key_0.jpg",
  "img/omen.jpg",
  "img/reina.jpg"
];

// Genera las cartas duplicadas y las mezcla aleatoriamente
function generarCartas() {
  cartas = [...imagenes, ...imagenes].sort(() => 0.5 - Math.random());
}

// Dibuja el tablero de 4x4 con las cartas boca abajo
function dibujarTablero() {
  tablero.innerHTML = ""; // Limpia el tablero
  let index = 0;
  for (let i = 0; i < 4; i++) {
    const fila = document.createElement("tr"); // Crea una fila
    for (let j = 0; j < 4; j++) {
      const celda = document.createElement("td"); // Crea una celda
      celda.dataset.index = index; // Asigna un índice a la celda
      celda.classList.add("carta"); // Agrega clase de estilo

      const img = document.createElement("img"); // Crea la imagen de la carta
      img.src = "img/reverso.jpeg"; // Muestra el reverso por defecto
      img.classList.add("img-carta"); // Clase para estilo de imagen
      celda.appendChild(img); // Agrega la imagen a la celda

      // Evento para seleccionar la carta
      celda.addEventListener("click", () => seleccionarCarta(celda, img));
      fila.appendChild(celda); // Agrega la celda a la fila
      index++;
    }
    tablero.appendChild(fila); // Agrega la fila al tablero
  }
}

// Inicia el juego (mezcla cartas y dibuja tablero)
function iniciarJuego() {
  generarCartas(); // Mezcla cartas
  dibujarTablero(); // Dibuja tablero
  aciertos = 0; // Reinicia aciertos
  intentos = 0; // Reinicia intentos
  mensaje.textContent = "¡Encuentra todas las parejas!"; // Mensaje inicial
  puedeSeleccionar = true; // Permite seleccionar
}

// Reinicia el juego completamente
function reiniciarJuego() {
  iniciarJuego();
}

// Maneja la selección de una carta
function seleccionarCarta(celda, img) {
  if (!puedeSeleccionar) return; // Evita seleccionar mientras se comparan
  const index = celda.dataset.index; // Obtiene el índice de la carta

  // Evita seleccionar dos veces la misma carta o una ya descubierta
  if (seleccionadas.includes(celda) || img.classList.contains("descubierta")) return;

  img.src = cartas[index]; // Muestra la imagen de la carta
  img.classList.add("descubierta"); // Marca como descubierta
  seleccionadas.push(celda); // Guarda la carta seleccionada

  // Cuando hay 2 cartas seleccionadas, las compara
  if (seleccionadas.length === 2) {
    puedeSeleccionar = false; // Bloquea nuevas selecciones
    intentos++; // Aumenta los intentos
    setTimeout(() => {
      compararCartas(); // Compara después de 0.8 segundos
      puedeSeleccionar = true; // Permite seleccionar nuevamente
    }, 800);
  }
}

// Compara las 2 cartas seleccionadas
function compararCartas() {
  const [c1, c2] = seleccionadas; // Obtiene las 2 cartas
  const img1 = c1.querySelector("img");
  const img2 = c2.querySelector("img");

  // Si son iguales → acierto
  if (img1.src === img2.src) {
    aciertos++; // Suma un acierto
    c1.style.backgroundColor = "#90EE90"; // Color verde de acierto
    c2.style.backgroundColor = "#90EE90";
    if (aciertos === 8) { // Si encontró todas las parejas
      mensaje.textContent = `¡Ganaste en ${intentos} intentos! `;
      sumarPuntos(); // Suma puntos al ganar
    }
  } else {
    // Si no coinciden, se dan vuelta otra vez
    img1.src = "img/reverso.jpeg";
    img2.src = "img/reverso.jpeg";
    img1.classList.remove("descubierta");
    img2.classList.remove("descubierta");
  }

  seleccionadas = []; // Limpia la selección
}

// SUMAR 30 PUNTOS AL GANAR
function sumarPuntos() {
  const puntosGanados = 30; // Puntos que se suman

  // Envía los puntos al backend PHP
  fetch("../../backend/controladores/actualizar_puntos.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "puntos=" + puntosGanados
  })
    .then(res => res.text()) // Convierte la respuesta en texto
    .then(data => {
      console.log(data); // Muestra la respuesta del servidor
      alert(`¡Ganaste ${puntosGanados} puntos! `); // Muestra mensaje al jugador
    })
    .catch(err => console.error("Error al actualizar puntos:", err)); // Captura errores
}
