var empieza = 0; // Controla si el juego ya comenzó
var modal = document.getElementById("cajadeReglas"); // Modal de reglas
var modal2 = document.getElementById("tabla"); // Modal del tablero

function abrir() { modal.style.display = "block"; } // Abre el modal de reglas
function cerrar() { modal.style.display = "none"; } // Cierra el modal de reglas
function abrir2() { modal2.style.display = "block"; } // Abre el modal del tablero
function cerrar2() { modal2.style.display = "none"; } // Cierra el modal del tablero

// Crea el tablero 5x5 vacío
var tablero = [
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null]
];

// Actualiza el contenido visual del tablero
function actualizarTablero() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            var celda = document.getElementById(String.fromCharCode(97 + i) + (j + 1)); // Genera ID tipo a1, a2...
            if (tablero[i][j] !== null) {
                celda.innerHTML = tablero[i][j]; // Muestra número
                celda.setAttribute('data-value', tablero[i][j]); // Guarda valor
            } else {
                celda.innerHTML = ""; // Limpia celda
                celda.removeAttribute('data-value');
                celda.classList.add("empty"); // Marca como vacía
            }
        }
    }
}

// Reinicia el juego a tablero vacío
function reiniciarJuego() {
    console.log("Reiniciando juego");
    empieza = 0;
    tablero = [
        [null, null, null, null, null],
        [null, null, null, null, null],
        [null, null, null, null, null],
        [null, null, null, null, null],
        [null, null, null, null, null]
    ];
    actualizarTablero();
}

// Genera un número aleatorio (2 o 4) en una posición vacía
function Numaleatorio() {
    var posicionesVacias = [];
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (tablero[i][j] === null) {
                posicionesVacias.push([i, j]);
            }
        }
    }
    if (posicionesVacias.length > 0) {
        var randomPos = posicionesVacias[Math.floor(Math.random() * posicionesVacias.length)];
        var numero = Math.random() < 0.5 ? 2 : 4;
        tablero[randomPos[0]][randomPos[1]] = numero;
        actualizarTablero();
    }
}

// --- MOVIMIENTOS ---

// Mueve hacia arriba
function moverArriba() {
    for (var col = 0; col < 5; col++) {
        var nuevaColumna = [null, null, null, null, null];
        var index = 0;
        for (var fila = 0; fila < 5; fila++) {
            if (tablero[fila][col] !== null) {
                if (nuevaColumna[index] === null) {
                    nuevaColumna[index] = tablero[fila][col];
                } else if (nuevaColumna[index] === tablero[fila][col]) {
                    nuevaColumna[index] *= 2;
                    index++;
                } else {
                    index++;
                    nuevaColumna[index] = tablero[fila][col];
                }
            }
        }
        for (var fila = 0; fila < 5; fila++) {
            tablero[fila][col] = nuevaColumna[fila];
        }
    }
    actualizarTablero();
    if (checkGameOver() || Ganaste()) reiniciarJuego();
}

// Mueve hacia abajo
function moverAbajo() {
    for (var col = 0; col < 5; col++) {
        var nuevaColumna = [null, null, null, null, null];
        var index = 4;
        for (var fila = 4; fila >= 0; fila--) {
            if (tablero[fila][col] !== null) {
                if (nuevaColumna[index] === null) {
                    nuevaColumna[index] = tablero[fila][col];
                } else if (nuevaColumna[index] === tablero[fila][col]) {
                    nuevaColumna[index] *= 2;
                    index--;
                } else {
                    index--;
                    nuevaColumna[index] = tablero[fila][col];
                }
            }
        }
        for (var fila = 0; fila < 5; fila++) {
            tablero[fila][col] = nuevaColumna[fila];
        }
    }
    actualizarTablero();
    if (checkGameOver() || Ganaste()) reiniciarJuego();
}

// Mueve hacia la izquierda
function moverIzquierda() {
    for (var fila = 0; fila < 5; fila++) {
        var nuevaFila = [null, null, null, null, null];
        var index = 0;
        for (var col = 0; col < 5; col++) {
            if (tablero[fila][col] !== null) {
                if (nuevaFila[index] === null) {
                    nuevaFila[index] = tablero[fila][col];
                } else if (nuevaFila[index] === tablero[fila][col]) {
                    nuevaFila[index] *= 2;
                    index++;
                } else {
                    index++;
                    nuevaFila[index] = tablero[fila][col];
                }
            }
        }
        for (var col = 0; col < 5; col++) {
            tablero[fila][col] = nuevaFila[col];
        }
    }
    actualizarTablero();
    if (checkGameOver() || Ganaste()) reiniciarJuego();
}

// Mueve hacia la derecha
function moverDerecha() {
    for (var fila = 0; fila < 5; fila++) {
        var nuevaFila = [null, null, null, null, null];
        var index = 4;
        for (var col = 4; col >= 0; col--) {
            if (tablero[fila][col] !== null) {
                if (nuevaFila[index] === null) {
                    nuevaFila[index] = tablero[fila][col];
                } else if (nuevaFila[index] === tablero[fila][col]) {
                    nuevaFila[index] *= 2;
                    index--;
                } else {
                    index--;
                    nuevaFila[index] = tablero[fila][col];
                }
            }
        }
        for (var col = 0; col < 5; col++) {
            tablero[fila][col] = nuevaFila[col];
        }
    }
    actualizarTablero();
    if (checkGameOver() || Ganaste()) reiniciarJuego();
}

// Verifica si el jugador perdió
function checkGameOver() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (tablero[i][j] === null) return false; // Si hay espacios vacíos, sigue
        }
    }
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (i < 4 && tablero[i][j] === tablero[i + 1][j]) return false; // Comparar vertical
            if (j < 4 && tablero[i][j] === tablero[i][j + 1]) return false; // Comparar horizontal
        }
    }
    alert("¡Perdiste! El juego se reiniciará.");
    return true;
}

// Detecta teclas de flechas
function teclas(event) {
    if (event.key === "ArrowUp") { moverArriba(); Numaleatorio(); }
    if (event.key === "ArrowDown") { moverAbajo(); Numaleatorio(); }
    if (event.key === "ArrowLeft") { moverIzquierda(); Numaleatorio(); }
    if (event.key === "ArrowRight") { moverDerecha(); Numaleatorio(); }
}
window.addEventListener("keydown", teclas); // Escucha las teclas

// Inicia el juego
function iniciarJuego() {
    abrir2();
    if (empieza === 0) {
        Numaleatorio();
        Numaleatorio();
        empieza = 1;
    }
}

// Envía los puntos al backend PHP
function sumarPuntos(puntos) {
    fetch("../../backend/controladores/actualizar_puntos.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "puntos=" + puntos
    })
    .then(response => response.text())
    .then(data => {
        console.log("Servidor:", data);
        alert("¡Ganaste " + puntos + " puntos! ");
    })
    .catch(error => console.error("Error al enviar puntos:", error));
}

// Verifica si se llegó al número 2048
function Ganaste() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (tablero[i][j] === 2048) {
                alert("¡Felicidades! Llegaste a 2048 (+80 puntos)");
                sumarPuntos(80);
                return true;
            }
        }
    }
    return false;
}
