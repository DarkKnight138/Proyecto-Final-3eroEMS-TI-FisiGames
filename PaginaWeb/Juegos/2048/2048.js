var empieza = 0;
var modal = document.getElementById("cajadeReglas");
var modal2 = document.getElementById("tabla");

function abrir() { modal.style.display = "block"; }
function cerrar() { modal.style.display = "none"; }
function abrir2() { modal2.style.display = "block"; }
function cerrar2() { modal2.style.display = "none"; }

var tablero = [
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null],
    [null, null, null, null, null]
];

function actualizarTablero() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            var celda = document.getElementById(String.fromCharCode(97 + i) + (j + 1));
            if (tablero[i][j] !== null) {
                celda.innerHTML = tablero[i][j];
                celda.setAttribute('data-value', tablero[i][j]);
            } else {
                celda.innerHTML = "";
                celda.removeAttribute('data-value');
                celda.classList.add("empty");
            }
        }
    }
}

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

// --- FUNCIONES DE MOVIMIENTO ---
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

function checkGameOver() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (tablero[i][j] === null) return false;
        }
    }
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (i < 4 && tablero[i][j] === tablero[i + 1][j]) return false;
            if (j < 4 && tablero[i][j] === tablero[i][j + 1]) return false;
        }
    }
    alert("¡Perdiste! El juego se reiniciará.");
    return true;
}

function teclas(event) {
    if (event.key === "ArrowUp") { moverArriba(); Numaleatorio(); }
    if (event.key === "ArrowDown") { moverAbajo(); Numaleatorio(); }
    if (event.key === "ArrowLeft") { moverIzquierda(); Numaleatorio(); }
    if (event.key === "ArrowRight") { moverDerecha(); Numaleatorio(); }
}
window.addEventListener("keydown", teclas);

function iniciarJuego() {
    abrir2();
    if (empieza === 0) {
        Numaleatorio();
        Numaleatorio();
        empieza = 1;
    }
}

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

function Ganaste() {
    for (var i = 0; i < 5; i++) {
        for (var j = 0; j < 5; j++) {
            if (tablero[i][j] === 1024) {
                alert("¡Muy bien! Llegaste a 1024 (+50 puntos)");
                sumarPuntos(50);
                return true;
            }
            if (tablero[i][j] === 2048) {
                alert("¡Felicidades! Has ganado (2048) (+100 puntos)");
                sumarPuntos(100);
                return true;
            }
        }
    }
}
