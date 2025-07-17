var dinero = 1000;


        function jugar(vasoElegido) {
            var apuesta = document.getElementById("apuesta").value;
            apuesta = parseInt(apuesta);

            if (isNaN(apuesta) || apuesta <= 0) {
                document.getElementById("mensaje").innerHTML = "Ingresa una apuesta válida.";
                return;
            }


            if (apuesta > dinero) {
                document.getElementById("mensaje").innerHTML = "No tienes suficiente dinero.";
                return;
            }


            var vasoConPelota = Math.floor(Math.random() * 3) + 1;


            if (vasoElegido === vasoConPelota) {
                dinero = dinero + apuesta;
                document.getElementById("mensaje").innerHTML =
                    "¡Ganaste! La pelotita estaba en el vaso " + vasoConPelota + ".";
            } else {
                dinero = dinero - apuesta;
                document.getElementById("mensaje").innerHTML =
                    "Perdiste. La pelotita estaba en el vaso " + vasoConPelota + ".";
            }


            document.getElementById("dineroTxt").innerHTML = "Tienes $" + dinero;
        }