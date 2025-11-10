for (let index = 0; index < 9; index++) {
  document.getElementById(index).setAttribute("class", "vacio btn");
  document.getElementById(index).disabled = false;
  document.getElementById(index).setAttribute("onclick", "elijeCasilla(this.id)");
}

let $casillas = [0, 1, 2, 3, 4, 5, 6, 7, 8];
let contador = 0;
const combinacionesGanadoras = [
  [0, 1, 2], [3, 4, 5], [6, 7, 8],
  [0, 3, 6], [1, 4, 7], [2, 5, 8],
  [0, 4, 8], [2, 4, 6]
];

const mensaje = document.createElement("div");
mensaje.id = "mensaje-tateti";
mensaje.style.marginTop = "15px";
mensaje.style.fontSize = "1.3rem";
mensaje.style.fontWeight = "bold";
mensaje.style.textShadow = "0 0 8px rgba(0, 255, 231, 0.6)";
mensaje.style.color = "#00ffe7";
mensaje.style.transition = "color 0.3s ease";
document.querySelector("main").appendChild(mensaje);

function mostrarMensaje(texto, color = "#00ffe7") {
  mensaje.textContent = texto;
  mensaje.style.color = color;
}

function reiniciar() {
  for (let index = 0; index < 9; index++) {
    let btn = document.getElementById(index);
    btn.setAttribute("class", "vacio btn");
    btn.disabled = false;
    btn.innerText = "";
    btn.setAttribute("onclick", "elijeCasilla(this.id)");
  }
  $casillas = [0, 1, 2, 3, 4, 5, 6, 7, 8];
  contador = 0;
  mostrarMensaje("");
}

function sumarPuntos() {
  const puntosGanados = 10;
  fetch("../../backend/controladores/actualizar_puntos.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "puntos=" + puntosGanados
  })
    .then(res => res.text())
    .then(data => console.log(data))
    .catch(err => console.error("Error al actualizar puntos:", err));
}

function elijeCasilla(id) {
  let eleccion = $casillas[id];
  let btn = document.getElementById(eleccion);
  btn.disabled = true;
  btn.classList.add("cruz");
  btn.innerText = "X";
  $casillas[eleccion] = 10;

  if (hayGanador(combinacionesGanadoras, "cruz")) {
    mostrarMensaje("¡Ganaste! 🎉", "#00ffe7");
    sumarPuntos();
    setTimeout(reiniciar, 1500);
    return;
  }

  if (esEmpate()) {
    mostrarMensaje("Empate", "#ffaa00");
    setTimeout(reiniciar, 1500);
    return;
  }

  let eleccionBot;
  let elegido = false;
  do {
    eleccionBot = Math.floor(Math.random() * 9);
    if ($casillas[eleccionBot] === eleccionBot) {
      elegido = true;
      let btnBot = document.getElementById(eleccionBot);
      btnBot.disabled = true;
      btnBot.classList.add("circulo");
      btnBot.innerText = "O";
      $casillas[eleccionBot] = 10;
    }
  } while (!elegido && contador < 4);

  if (hayGanador(combinacionesGanadoras, "circulo")) {
    mostrarMensaje("Perdiste", "#ff4d4d");
    setTimeout(reiniciar, 1500);
    return;
  }

  if (esEmpate()) {
    mostrarMensaje("Empate", "#ffaa00");
    setTimeout(reiniciar, 1500);
  }

  contador++;
}

function hayGanador(combinaciones, clase) {
  for (let combinacion of combinaciones) {
    if (combinacion.every(id => document.getElementById(id).classList.contains(clase))) {
      return true;
    }
  }
  return false;
}

function esEmpate() {
  return $casillas.every((c, i) => c === 10);
}

document.getElementById("reiniciar").addEventListener("click", reiniciar);