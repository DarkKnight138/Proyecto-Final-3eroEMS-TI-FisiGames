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
        if (tablero[index] !== "" || !juegoActivo || jugadorActual !== "X") return;

        tablero[index] = "X";
        e.target.textContent = "X";

        if (verificarGanador("X")) {
            juegoActivo = false;
            alert("¡Ganaste!");
            sumarPuntos();
            return;
        }

        if (!tablero.includes("")) {
            juegoActivo = false;
            alert("Empate");
            return;
        }

        //  turno del bot después de un pequeño retraso
        jugadorActual = "O";
        setTimeout(turnoBot, 500);
    }

    function turnoBot() {
        if (!juegoActivo) return;

        // obtiene los índices vacíos
        const vacios = tablero.map((v, i) => v === "" ? i : null).filter(v => v !== null);

        if (vacios.length === 0) return;

        // elige una posición aleatoria
        const randomIndex = vacios[Math.floor(Math.random() * vacios.length)];
        tablero[randomIndex] = "O";
        casillas[randomIndex].textContent = "O";

        if (verificarGanador("O")) {
            juegoActivo = false;
            alert("Perdiste ");
            return;
        }

        if (!tablero.includes("")) {
            juegoActivo = false;
            alert("Empate");
            return;
        }

        jugadorActual = "X";
    }

    function verificarGanador(jugador) {
        return combinacionesGanadoras.some(([a, b, c]) =>
            tablero[a] === jugador && tablero[b] === jugador && tablero[c] === jugador
        );
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
