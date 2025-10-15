// Variables del juego
let cartas = [];
let primeraCarta = null;
let segundaCarta = null;
let bloqueado = false;
let aciertos = 0;

// 🟢 Generar tablero
function iniciarJuego() {
    const tablero = document.getElementById("tablero");
    tablero.innerHTML = "";
    aciertos = 0;

    const imagenes = ["🍎", "🍌", "🍇", "🍉", "🍓", "🍍", "🥝", "🍒"];
    cartas = [...imagenes, ...imagenes]; // duplicar para pares
    cartas.sort(() => Math.random() - 0.5);

    let filas = 4;
    let columnas = 4;
    let index = 0;

    for (let i = 0; i < filas; i++) {
        let fila = document.createElement("tr");
        for (let j = 0; j < columnas; j++) {
            let celda = document.createElement("td");
            celda.classList.add("carta");
            celda.dataset.valor = cartas[index];
            celda.innerHTML = "❓";
            celda.addEventListener("click", () => voltearCarta(celda));
            fila.appendChild(celda);
            index++;
        }
        tablero.appendChild(fila);
    }

    document.getElementById("mensaje").innerText = "¡Encuentra las parejas!";
}

// 🔁 Reiniciar
function reiniciarJuego() {
    iniciarJuego();
    document.getElementById("mensaje").innerText = "Juego reiniciado.";
}

// 🎯 Voltear carta
function voltearCarta(celda) {
    if (bloqueado || celda === primeraCarta || celda.classList.contains("acertada")) return;

    celda.innerHTML = celda.dataset.valor;

    if (!primeraCarta) {
        primeraCarta = celda;
    } else {
        segundaCarta = celda;
        bloquearTablero(true);

        if (primeraCarta.dataset.valor === segundaCarta.dataset.valor) {
            primeraCarta.classList.add("acertada");
            segundaCarta.classList.add("acertada");
            aciertos++;
            document.getElementById("mensaje").innerText = "¡Encontraste una pareja! 🧠";
            resetSeleccion();

            if (aciertos === cartas.length / 2) {
                document.getElementById("mensaje").innerText = "🎉 ¡Ganaste el juego!";
                sumarPuntos(15); // 🔥 suma puntos cuando gana
            }
        } else {
            setTimeout(() => {
                primeraCarta.innerHTML = "❓";
                segundaCarta.innerHTML = "❓";
                resetSeleccion();
            }, 700);
        }
    }
}

// 🔒 Bloquear tablero mientras se comparan cartas
function bloquearTablero(valor) {
    bloqueado = valor;
}

// 🔁 Resetear selección
function resetSeleccion() {
    primeraCarta = null;
    segundaCarta = null;
    bloquearTablero(false);
}

// 📤 Enviar puntos al backend
function sumarPuntos(puntos) {
    fetch("../../backend/controladores/actualizar_puntos.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "puntos=" + puntos
    })
    .then(response => response.text())
    .then(data => {
        console.log("Servidor:", data);
        document.getElementById("mensaje").innerText += " +15 puntos 🎯";
    })
    .catch(error => {
        console.error("Error al enviar puntos:", error);
        document.getElementById("mensaje").innerText += " ⚠️ Error al guardar puntos";
    });
}
