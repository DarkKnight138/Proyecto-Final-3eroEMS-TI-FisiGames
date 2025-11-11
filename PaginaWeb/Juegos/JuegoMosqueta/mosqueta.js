// Variable inicial que guarda el dinero del jugador
var dinero = 1000;

// Función principal que se ejecuta cuando el jugador elige un vaso
function jugar(vasoElegido) {
    // Obtiene el valor ingresado en el campo de apuesta
    var apuesta = parseInt(document.getElementById("apuesta").value);

    // Valida que la apuesta sea un número positivo
    if (isNaN(apuesta) || apuesta <= 0) {
        document.getElementById("mensaje").innerHTML = "<span class='incorrecta'>Ingresa una apuesta válida.</span>";
        return;
    }

    // Verifica que el jugador tenga suficiente dinero
    if (apuesta > dinero) {
        document.getElementById("mensaje").innerHTML = "<span class='incorrecta'>No tienes suficiente dinero.</span>";
        return;
    }

    // Limpia las animaciones previas de los vasos
    for (let i = 1; i <= 3; i++) {
        document.getElementById("vaso" + i).classList.remove("blink-green", "blink-red");
    }

    // Genera aleatoriamente el vaso que contiene la pelota
    var vasoConPelota = Math.floor(Math.random() * 3) + 1;

    // Si el jugador acierta el vaso correcto
    if (vasoElegido === vasoConPelota) {
        // Suma la apuesta al dinero total
        dinero += apuesta;
        // Muestra mensaje de acierto
        document.getElementById("mensaje").innerHTML =
            "<span class='correcta'>¡Ganaste! La pelotita estaba en el vaso " + vasoConPelota + ". (+10 puntos)</span>";
        // Agrega animación verde al vaso correcto
        document.getElementById("vaso" + vasoConPelota).classList.add("blink-green");
        // Suma puntos al jugador en la base de datos
        sumarPuntos(10);
    } else {
        // Si falla, resta la apuesta al dinero total
        dinero -= apuesta;
        // Muestra mensaje de error con el vaso correcto
        document.getElementById("mensaje").innerHTML =
            "<span class='incorrecta'>Perdiste. La pelotita estaba en el vaso " + vasoConPelota + ".</span>";
        // Agrega animación roja al vaso con la pelota
        document.getElementById("vaso" + vasoConPelota).classList.add("blink-red");
    }

    // Actualiza el texto que muestra el dinero actual
    document.getElementById("dineroTxt").innerHTML = "Tienes $" + dinero;
}

// Reinicia el juego al estado inicial
function reiniciarJuego() {
    dinero = 1000; // Restablece el dinero
    document.getElementById("dineroTxt").innerHTML = "Tienes $1000"; // Actualiza texto
    document.getElementById("mensaje").innerHTML = ""; // Limpia mensajes
    document.getElementById("apuesta").value = ""; // Limpia el campo de apuesta
    // Quita animaciones previas de los vasos
    for (let i = 1; i <= 3; i++) {
        document.getElementById("vaso" + i).classList.remove("blink-green", "blink-red");
    }
}

// Envía los puntos ganados al backend con una petición POST
function sumarPuntos(puntos) {
    fetch("../../backend/controladores/actualizar_puntos.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "puntos=" + puntos // Envía los puntos al servidor
    })
    .then(response => response.text()) // Convierte la respuesta a texto
    .then(data => console.log("Servidor:", data)) // Muestra la respuesta en consola
    .catch(error => console.error("Error al enviar puntos:", error)); // Captura errores
}

// Lógica del menú responsive
const menuToggle = document.getElementById('menu-toggle'); // Botón para abrir/cerrar el menú
const navbar = document.getElementById('navbar'); // Elemento del menú de navegación

// Alterna la clase "expanded" al hacer clic en el botón del menú
menuToggle.addEventListener('click', () => {
    navbar.classList.toggle('expanded');
});
