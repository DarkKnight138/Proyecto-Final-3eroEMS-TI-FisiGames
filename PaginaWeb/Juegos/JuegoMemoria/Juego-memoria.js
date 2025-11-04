let cartas = [];
let seleccionadas = [];
let aciertos = 0;
let intentos = 0;
let puedeSeleccionar = false;

const tablero = document.getElementById("tablero");
const mensaje = document.getElementById("mensaje");

// 🖼️ Imágenes de los agentes (en carpeta img/)
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

// 🔀 Genera y mezcla las cartas
function generarCartas() {
  cartas = [...imagenes, ...imagenes].sort(() => 0.5 - Math.random());
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

      // crea la imagen con el reverso
      const img = document.createElement("img");
      img.src = "img/reverso.jpg";
      img.classList.add("img-carta");
      celda.appendChild(img);

      celda.addEventListener("click", () => seleccionarCarta(celda, img));
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
  mensaje.textContent = "¡Encuentra todas las parejas!";
  puedeSeleccionar = true;
}

function reiniciarJuego() {
  iniciarJuego();
}

function seleccionarCarta(celda, img) {
  if (!puedeSeleccionar) return;
  const index = celda.dataset.index;

  // Evita volver a seleccionar la misma carta o una ya descubierta
  if (seleccionadas.includes(celda) || img.classList.contains("descubierta")) return;

  img.src = cartas[index];
  img.classList.add("descubierta");
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
  const [c1, c2] = seleccionadas;
  const img1 = c1.querySelector("img");
  const img2 = c2.querySelector("img");

  if (img1.src === img2.src) {
    aciertos++;
    c1.style.backgroundColor = "#90EE90";
    c2.style.backgroundColor = "#90EE90";
    if (aciertos === 8) {
      mensaje.textContent = `¡Ganaste en ${intentos} intentos! 🎉`;
      sumarPuntos();
    }
  } else {
    img1.src = "img/reverso.jpg";
    img2.src = "img/reverso.jpg";
    img1.classList.remove("descubierta");
    img2.classList.remove("descubierta");
  }

  seleccionadas = [];
}

// 🟢 SUMAR 30 PUNTOS AL GANAR
function sumarPuntos() {
  const puntosGanados = 30;

  fetch("../../backend/controladores/actualizar_puntos.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "puntos=" + puntosGanados
  })
    .then(res => res.text())
    .then(data => {
      console.log(data);
      alert(`¡Ganaste ${puntosGanados} puntos! 🧠`);
    })
    .catch(err => console.error("Error al actualizar puntos:", err));
}
