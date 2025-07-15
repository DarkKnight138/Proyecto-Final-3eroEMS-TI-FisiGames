//Inicializo los atributos de los botones
        for (let index = 0; index < 9; index++) {
            document.getElementById(index).setAttribute("class", "vacio btn");
            document.getElementById(index).disabled = false;
            document.getElementById(index).setAttribute("onclick", "elijeCasilla(this.id)");
        }
        $casillas = [0, 1, 2, 3, 4, 5, 6, 7, 8];
        var contador = 0;

        function reiniciar() {

            for (let index = 0; index < 9; index++) {
                document.getElementById(index).setAttribute("class", "vacio btn");
                document.getElementById(index).disabled = false;
                document.getElementById(index).setAttribute("onclick", "elijeCasilla(this.id)");
            }
            $casillas = [0, 1, 2, 3, 4, 5, 6, 7, 8];
            contador = 0;
            var existe = false;
        }

        const combinacionesGanadoras = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], //filas
            [0, 3, 6], [1, 4, 7], [2, 5, 8], //columnas
            [0, 4, 8], [2, 4, 6]          //diagonales
        ]
        var contenedor = document.getElementById("contenedor");
        var btn = document.createElement("input");
        btn.type = "button";
        btn.value = "Reiniciar";
        btn.setAttribute("onclick", "reiniciar()");
        btn.setAttribute("class", "btnReinicio btn");
        function elijeCasilla(id) {
            $eleccionBot = null;
            $eleccion = null;
            $existe = false;

            $eleccion = $casillas[id];
            document.getElementById($eleccion).setAttribute("disabled", true);
            document.getElementById($eleccion).setAttribute("class", "btn cruz");
            $casillas[$eleccion] = 10;

            if (hayGanador(combinacionesGanadoras, "cruz")) {
                setTimeout(() => alert("Usted gano"), 10);
                return;
            }

            $eleccionBot = Math.floor((Math.random() * 9));

            do {
                if (contador < 4) {
                    if ($eleccionBot == $casillas[$eleccionBot]) {
                        $existe = true;
                        document.getElementById($eleccionBot).setAttribute("disabled", true);
                        document.getElementById($eleccionBot).setAttribute("class", "btn circulo");
                        $casillas[$eleccionBot] = 10;
                    } else {
                        $eleccionBot = Math.floor((Math.random() * 9));
                    }
                } else {
                    $existe = true;
                }

            } while ($existe == false);

            if (hayGanador(combinacionesGanadoras, "circulo")) {
                setTimeout(() => alert("Usted perdio"), 10);
            }
            var contadorArray = 0;
            for (let i = 0; i < $casillas.length; i++) {
                if ($casillas[i] == 10) {
                    contadorArray++;

                }
            }
            if (contadorArray == 9 || hayGanador(combinacionesGanadoras, "circulo") || hayGanador(combinacionesGanadoras, "cruz")) {
                contenedor.appendChild(btn);
            }
            console.log(contadorArray);
            contador++;
        }




        function hayGanador(combinacionesGanadoras, jugadorClase) {


            for (let i = 0; i < combinacionesGanadoras.length; i++) {
                let combinacion = combinacionesGanadoras[i];
                let todosCoinciden = true;

                for (let j = 0; j < combinacion.length; j++) {
                    let id = combinacion[j];
                    let btn = document.getElementById(id);

                    if (!btn.classList.contains(jugadorClase)) {
                        todosCoinciden = false;
                        break;
                    }
                }

                if (todosCoinciden) {
                    return true;
                }
            }

            return false;
        }
