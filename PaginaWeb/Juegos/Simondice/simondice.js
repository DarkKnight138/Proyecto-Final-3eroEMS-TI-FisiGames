var colores = ["rojo", "verde", "azul", "amarillo"];
        var secuencia = [];
        var jugador = [];
        var puedeJugar = false;

        var sonidoRojo = new Audio("sonidos/1.mp3");
        var sonidoVerde = new Audio("sonidos/2.mp3");
        var sonidoAzul = new Audio("sonidos/3.mp3");
        var sonidoAmarillo = new Audio("sonidos/4.mp3");
        var sonidoPierde = new Audio("sonidos/pierde.mp3");
        var sonidoEmpieza = new Audio("sonidos/empieza.mp3");
        var sonidoNivel10 = new Audio("sonidos/empieza.mp3");

        var imgRojo = "imgs/rojo.webp";
        var imgVerde = "imgs/verde.avif";
        var imgAzul = "imgs/azul.png";
        var imgAmarillo = "imgs/amarillo.avif";

        var imgRojoOn = "imgs/rojo1.jpg";
        var imgVerdeOn = "imgs/verde1.avif";
        var imgAzulOn = "imgs/azul1.jpeg";
        var imgAmarilloOn = "imgs/amarillo2.avif";

        document.getElementById("comienzaBtn").addEventListener("click", comenzarJuego);

        function comenzarJuego() {
            sonidoEmpieza.play();
            secuencia = [];
            jugador = [];
            puedeJugar = false;
            setTimeout(nuevoNivel, 1000);
        }

        document.getElementById("rojo").addEventListener("click", function () {
            tocarColor("rojo");
        });

        document.getElementById("verde").addEventListener("click", function () {
            tocarColor("verde");
        });

        document.getElementById("azul").addEventListener("click", function () {
            tocarColor("azul");
        });

        document.getElementById("amarillo").addEventListener("click", function () {
            tocarColor("amarillo");
        });

        function tocarColor(color) {
            if (puedeJugar == false) return;

            reproducirColor(color);
            jugador.push(color);
            verificar(jugador.length - 1);
        }

        function nuevoNivel() {
            puedeJugar = false;
            var numero = Math.floor(Math.random() * 4);
            var color = colores[numero];
            secuencia.push(color);
            jugador = [];

            if ((secuencia.length - 1) > 0 && (secuencia.length - 1) % 10 == 0) {
                sonidoNivel10.play();
            }

            mostrarSecuencia(0);
        }

        function mostrarSecuencia(i) {
            if (i < secuencia.length) {
                var color = secuencia[i];
                reproducirColor(color);
                setTimeout(function () {
                    mostrarSecuencia(i + 1);
                }, 800);
            } else {
                setTimeout(function () {
                    puedeJugar = true;
                }, 500);
            }
        }

        function reproducirColor(color) {
            var imagen = document.getElementById(color);
            if (color == "rojo") {
                imagen.src = imgRojoOn;
                sonidoRojo.play();
                setTimeout(function () {
                    imagen.src = imgRojo;
                }, 400);
            } else if (color == "verde") {
                imagen.src = imgVerdeOn;
                sonidoVerde.play();
                setTimeout(function () {
                    imagen.src = imgVerde;
                }, 400);
            } else if (color == "azul") {
                imagen.src = imgAzulOn;
                sonidoAzul.play();
                setTimeout(function () {
                    imagen.src = imgAzul;
                }, 400);
            } else if (color == "amarillo") {
                imagen.src = imgAmarilloOn;
                sonidoAmarillo.play();
                setTimeout(function () {
                    imagen.src = imgAmarillo;
                }, 400);
            }
        }

        function verificar(pos) {
            if (jugador[pos] != secuencia[pos]) {
                sonidoPierde.play();
                alert("¡Perdiste! Llegaste al nivel " + secuencia.length);
                puedeJugar = false;
                return;
            }

            if (jugador.length == secuencia.length) {
                setTimeout(nuevoNivel, 1000);
            }
        }