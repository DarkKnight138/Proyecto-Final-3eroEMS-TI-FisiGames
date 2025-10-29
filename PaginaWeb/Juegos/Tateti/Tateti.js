document.addEventListener("DOMContentLoaded", () => {
    const casillas = document.querySelectorAll(".tablero button");
    const reiniciarBtn = document.getElementById("reiniciar");
    let tablero = ["", "", "", "", "", "", "", "", ""];
    let jugadorActual = "X";
    let juegoActivo = true;

    const combinacionesGanadoras = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8],
        [0, 3, 6],
        [1, 4, 7],
        [2, 5, 8],
        [0, 4, 8],
        [2, 4, 6]
    ];

    function manejarClick(e) {
        const index = e.target.id;
        if (tablero[index] !== "" || !juegoActivo) return;

        tablero[index] = jugadorActual;
        e.target.textContent = jugadorActual;

        if (verificarGanador()) {
            juegoActivo = false;
            alert("¡Ganaste!");
            sumarPuntos(); // 💥 ahora llama al backend
        } else if (!tablero.includes("")) {
            juegoActivo = false;
            alert("Empate");
        } else {
            jugadorActual = jugadorActual === "X" ? "O" : "X";
        }
    }

    function verificarGanador() {
        return combinacionesGanadoras.some(combinacion => {
            const [a, b, c] = combinacion;
            return tablero[a] && tablero[a] === tablero[b] && tablero[a] === tablero[c];
        });
    }

    function reiniciarJuego() {
        tablero = ["", "", "", "", "", "", "", "", ""];
        jugadorActual = "X";
        juegoActivo = true;
        casillas.forEach(c => c.textContent = "");
    }

    async function sumarPuntos() {
        console.log("Intentando sumar puntos...");

        try {
            const response = await fetch("../../backend/controladores/actualizar_puntos.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "puntos=20"
            });

            const text = await response.text();
            console.log("Respuesta del servidor:", text);
            alert(text);
        } catch (error) {
            console.error("Error al sumar puntos:", error);
            alert("Error al conectar con el servidor");
        }
    }

    casillas.forEach(casilla => casilla.addEventListener("click", manejarClick));
    reiniciarBtn.addEventListener("click", reiniciarJuego);
});
