// Variables del juego
let puntuacion = 0;
const reversoImg = 'img/reverso.jpg'; // Imagen del reverso
const imagenesOriginales = [
    'img/Brim.jpg', 'img/Chamber.jpg', 'img/Fenix.jpg', 'img/gekko.jpg',
    'img/jett.jpg', 'img/Key_0.jpg', 'img/omen.jpg', 'img/reina.jpg'
]; // Cartas (pares)

// Variables internas del juego
let imagenes, cartasVolteadas, idCartasVolteadas, cartasEmparejadas, intentos, bloquearClicks;

// Mezcla aleatoria del array
function mezclar(array) {
    let copia = [...array];
    for (let i = copia.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [copia[i], copia[j]] = [copia[j], copia[i]];
    }
    return copia;
}

// 🟢 Iniciar juego
function iniciarJuego() {
    const pares = [...imagenesOriginales, ...imagenesOriginales];
    imagenes = mezclar(pares);
    cartasVolteadas = [];
    idCartasVolteadas = [];
    cartasEmparejadas = new Array(imagenes.length).fill(false);
    intentos = 0;
    bloquearClicks = false;

    const tablero = document.getElementById('tablero');
    tablero.innerHTML = '';
    document.getElementById('mensaje').textContent = '¡Encuentra todas las parejas de agentes!';

    for (let i = 0; i < 4; i++) {
        const fila = document.createElement('tr');
        for (let j = 0; j < 4; j++) {
            const id = i * 4 + j;
            const celda = document.createElement('td');
            celda.id = id;
            celda.classList.add('carta');
            celda.onclick = () => voltearCarta(id);

            const img = document.createElement('img');
            img.src = reversoImg;
            celda.appendChild(img);
            fila.appendChild(celda);
        }
        tablero.appendChild(fila);
    }
}

// 🎯 Voltear carta
function voltearCarta(i) {
    if (bloquearClicks || cartasEmparejadas[i] || idCartasVolteadas.includes(i)) return;

    const celda = document.getElementById(i);
    const img = celda.querySelector('img');
    img.src = imagenes[i];

    cartasVolteadas.push(imagenes[i]);
    idCartasVolteadas.push(i);

    if (cartasVolteadas.length === 2) {
        intentos++;
        bloquearClicks = true;

        if (cartasVolteadas[0] === cartasVolteadas[1]) {
            setTimeout(() => {
                idCartasVolteadas.forEach(id => {
                    const celdaEmp = document.getElementById(id);
                    celdaEmp.classList.add('emparejado');
                });
                cartasEmparejadas[idCartasVolteadas[0]] = true;
                cartasEmparejadas[idCartasVolteadas[1]] = true;
                resetTurno();
                comprobarFin();
            }, 700);
        } else {
            setTimeout(() => {
                idCartasVolteadas.forEach(id => {
                    const celda = document.getElementById(id);
                    const img = celda.querySelector('img');
                    img.src = reversoImg;
                });
                resetTurno();
            }, 1000);
        }
    }
}

// 🔁 Reset turno
function resetTurno() {
    cartasVolteadas = [];
    idCartasVolteadas = [];
    bloquearClicks = false;
}

// 🧠 Comprobar si ganó
function comprobarFin() {
    if (cartasEmparejadas.every(val => val)) {
        document.getElementById('mensaje').textContent = `🎉 ¡Felicidades! Terminaste en ${intentos} intentos.`;
        puntuacion += 15;
        sumarPuntos(15); // 🔥 Enviar puntos al backend
    }
}

// 🔁 Reiniciar juego
function reiniciarJuego() {
    iniciarJuego();
    document.getElementById('mensaje').textContent = 'Juego reiniciado.';
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
        document.getElementById("mensaje").textContent += " +15 puntos 🎯";
    })
    .catch(error => {
        console.error("Error al enviar puntos:", error);
        document.getElementById("mensaje").textContent += " ⚠️ Error al guardar puntos";
    });
}

