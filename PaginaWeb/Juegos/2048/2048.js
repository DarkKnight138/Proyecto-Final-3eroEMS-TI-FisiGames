  var puntuacion = 0;
   var empieza = 0;//Crea la variable empieza
   var modal = document.getElementById("cajadeReglas");/*Crea una variable que se refiere a la caja de reglas*/
   var modal2 = document.getElementById("tabla");/*Crea una variable que se refiere al tablero*/
   function abrir() {/*Cuando se toca el botón "Reglas" las reglas se muestran*/
       modal.style.display = "block";
   }
   function cerrar() {/*Cuando se toca la "X" las reglas se cierran*/
       modal.style.display = "none";
   }
   function abrir2() { /*Cuando se toca el botón "Empezar" el tablero se muestra*/
       modal2.style.display = "block";
   }
   function cerrar2() {/*Cuando se toca la "X" el tablero se cierra*/
       modal2.style.display = "none";
   }
    var tablero = [ // Creación del tablero 5x5 con valores vacíos.
       [null, null, null, null, null],
       [null, null, null, null, null],
       [null, null, null, null, null],
       [null, null, null, null, null],
       [null, null, null, null, null]
   ];
   function actualizarTablero() {
       for (var i = 0; i < 5; i++) { //Este for es para recorrer las filas
           for (var j = 0; j < 5; j++) {//Este for es para recorrer las celdas
               var celda = document.getElementById(String.fromCharCode(97 + i) + (j + 1));
               //Crea la variable celda el id de esta celda se combina por una parte alfabética y una
               // numérica. "(String.fromCharCode(97 + i) "Esta parte convierte el número 97 en la letra a
               // mediante el código ASCII. Y la j es un número del 1 al 5.
               if (tablero[i][j] !== null) {/*Si la posición de la celda no está vacía:*/
                   celda.innerHTML = tablero[i][j];/*selecciona esa celda*/
                   celda.setAttribute('data-value', tablero[i][j]);/*Y le da los atributos del valor dependiendo el número*/
               } else {//sino
                   celda.innerHTML = "";//vacía el contenido de la celda
                   celda.removeAttribute('data-value');//le quita el atributo del valor
                   celda.classList.add("empty");//Le da un fondo de color blanco
               }
           }
       }
   }
   function reiniciarJuego() {/*Cuando tocas el boton de reiniciar*/
    console.log("Reiniciando juego");
       empieza = 0;/*El juego recien empezo*/
       tablero = [/*Se limpia el tablero*/
           [null, null, null, null, null],
           [null, null, null, null, null],
           [null, null, null, null, null],
           [null, null, null, null, null],
           [null, null, null, null, null]
       ];
       actualizarTablero();// llama al método actualizar tablero
   }
   // Genera un número aleatorio (2 o 4) en una casilla vacía
   function Numaleatorio() {
       var posicionesVacias = [];
//Crea un array de las posiciones vacías
       for (var i = 0; i < 5; i++) {//Este for recorre las filas
           for (var j = 0; j < 5; j++) {
              //Este for recorre las casillas
               if (tablero[i][j] === null) {
            //Si la casilla está vacía
                   posicionesVacias.push([i, j]);
//Agrega la casilla al array de casillas vacías
               }
           }
       }
       if (posicionesVacias.length > 0) {//Si hay posiciones vacías:
           var randomPos = posicionesVacias[Math.floor(Math.random() * posicionesVacias.length)];
//Elige un número aleatrio de las posiciones vacías y lo redondea y lo pone en el array randomPos
           var numero = Math.random() < 0.5 ? 2 : 4; //Elige un número entre 2 y 4
           tablero[randomPos[0]][randomPos[1]] = numero;
          // Al valor de la casilla le asigna el valor de número
           actualizarTablero(); //Llama al método que actualiza el tablero
       }
   }
   // Lógica de movimiento
   function moverArriba() {//Cuando tocas la flechita para arriba
        for (var col = 0; col < 5; col++) {//Este for recorre las columnas
            var nuevaColumna = [null, null, null, null, null];
            //Crea una columna auxiliar donde analizamos los valores de la columna
            var index = 0;//Esta  variable recorre la columna auxiliar de arriba para abajo con respecto a la columna del tablero
            for (var fila = 0; fila < 5; fila++) {//Este for va recorriendo las casillas de la columna de arriba para abajo
                if (tablero[fila][col] !== null) {//Revisa si la casilla no está vacía
                    if (nuevaColumna[index] === null) {//Revisa que la casilla con el valor de index de la fila donde se está trabajando está vacía.
                        nuevaColumna[index] = tablero[fila][col];//Carga el valor del tablero original a la nueva fila.
                    } else if (nuevaColumna[index] === tablero[fila][col]) {//Si la casilla de la fila auxiliar vale lo mismo que la casilla del tablero
                        nuevaColumna[index] *= 2;//Se multiplica por 2
                        index++;//Nos movemos una casilla hacia abajo para poder seguir recorriendo
                    } else {//Si tienen valores distintos
                        index++;//Nos movemos una casilla hacia abajo para poder seguir recorriendo
                        nuevaColumna[index] = tablero[fila][col];// Copia el valor de la casilla del tablero a la posición actual de la fila auxiliar
                    }
                }
            }
            for (var fila = 0; fila < 5; fila++) {//Carga los valores resultantes de la fila auxiliar al tablero original
                tablero[fila][col] = nuevaColumna[fila];
            }
        }
        actualizarTablero();//Actualiza los valores del tablero
        if (checkGameOver() || Ganaste()) reiniciarJuego();
         //Si perdiste o ganste reinicia el juego
    }
    function moverAbajo() {// Cuando tocas la flechita para abajo.
        for (var col = 0; col < 5; col++) {//Este for recorre las columnas
            var nuevaColumna = [null, null, null, null, null];
            //Crea una columna auxiliar donde analizamos los valores de la columna
            var index = 4;//Esta  variable recorre la columna auxiliar de abajo para arriba con respecto a la columna del tablero
            for (var fila = 4; fila >= 0; fila--) { //Este for va recorriendo las casillas de la columna de abajo para arriba
                if (tablero[fila][col] !== null) {//Revisa si la casilla no está vacía
                    if (nuevaColumna[index] === null) { //Revisa que la casilla con el valor de index de la fila donde se está trabajando está vacía.
                        nuevaColumna[index] = tablero[fila][col];//Carga el valor del tablero original a la nueva fila.
                    } else if (nuevaColumna[index] === tablero[fila][col]) {//Si la casilla de la fila auxiliar vale lo mismo que la casilla del tablero
                        nuevaColumna[index] *= 2;//Se multiplica por 2
                        index--;//Nos movemos una casilla hacia arriba para poder seguir recorriendo
                    } else {//Si tienen valores distintos
                        index--;//Nos movemos una casilla hacia arriba para poder seguir recorriendo
                        nuevaColumna[index] = tablero[fila][col];
                        // Copia el valor de la casilla del tablero a la posición actual de la fila auxiliar
                    }
                }
            }
            for (var fila = 0; fila < 5; fila++) {//Carga los valores resultantes de la fila auxiliar al tablero original
                tablero[fila][col] = nuevaColumna[fila];}
        }
        actualizarTablero();//Actualiza los valores del tablero
        if (checkGameOver() || Ganaste()) reiniciarJuego();
        //Si perdiste o ganste reinicia el juego
    }
    function moverIzquierda() {//Cuando se toca la flecha de la izquierda
        for (var fila = 0; fila < 5; fila++) {//Este for recorre las filas
            var nuevaFila = [null, null, null, null, null];
            //Crea una fila auxiliar donde analizamos los valores de la fila
            var index = 0;//Esta variable sirve para fijarnos en que posición de la casilla vamos a poner los números empezando desde la derecha.
            for (var col = 0; col < 5; col++) {//Este for va recorriendo las casillas de izquierda a derecha.
                if (tablero[fila][col] !== null) {//Revisa si la casilla no está vacía
                    if (nuevaFila[index] === null) { //Revisa que la casilla con el valor de index de la fila donde se está trabajando está vacía.
                        nuevaFila[index] = tablero[fila][col];//Carga el valor del tablero original a la nueva fila.
                    } else if (nuevaFila[index] === tablero[fila][col]) {//Si la casilla de la fila auxiliar vale lo mismo que la casilla del tablero
                        nuevaFila[index] *= 2;//Se multiplica por 2
                        index++;//Nos movemos una casilla a la derecha para poder seguir recorriendo
                    } else {//Si tienen valores distintos
                        index++;//Nos movemos una casilla a la drecha para poder seguir recorriendo
                        nuevaFila[index] = tablero[fila][col];
                        // Copia el valor de la casilla del tablero a la posición actual de la fila auxiliar


                    }
                }
            }
            for (var col = 0; col < 5; col++) {//Carga los valores resultantes de la fila auxiliar al tablero original
                tablero[fila][col] = nuevaFila[col];
            }
        }
        actualizarTablero();//Actualiza el tablero
        if (checkGameOver() || Ganaste()) reiniciarJuego();
         //Si perdiste o ganste reinicia el juego
    }
    function moverDerecha() {//Cuando se toca la flecha de la derecha
        for (var fila = 0; fila < 5; fila++) {//Este for recorre las filas
            var nuevaFila = [null, null, null, null, null];
                      //Crea una fila auxiliar donde analizamos los valores de la fila
            var index = 4; //Esta variable sirve para fijarnos en que posición de la casilla vamos a poner los números empezando desde la derecha.
            for (var col = 4; col >= 0; col--) {//Este for va recorriendo las casillas de derecha a izquierda.
                if (tablero[fila][col] !== null) {
                 //Revisa si la casilla no está vacía
                    if (nuevaFila[index] === null) {
                    //Revisa que la casilla con el valor de index de la fila donde se está trabajando está vacía.
                        nuevaFila[index] = tablero[fila][col];
//Carga el valor del tablero original a la nueva fila.
                    } else if (nuevaFila[index] === tablero[fila][col]) { //Si la casilla de la fila auxiliar vale lo mismo que la casilla del tablero
                        nuevaFila[index] *= 2; //Se multiplica por 2
                        index--;//Nos movemos una casilla a la izquierda para poder seguir recorriendo
                    } else {//Si tienen valores distintos
                        index--; //Nos movemos una casilla a la izquierda para poder seguir recorriendo
                        nuevaFila[index] = tablero[fila][col];
// Copia el valor de la casilla del tablero a la posición actual de la fila auxiliar
                    }
                }
            }
            for (var col = 0; col < 5; col++) {//Carga los valores resultantes de la fila auxiliar al tablero original
                tablero[fila][col] = nuevaFila[col];
            }
        }
        actualizarTablero();//Actualiza los valores de la tabla
        if (checkGameOver() || Ganaste()) reiniciarJuego();
       //Si perdiste reinicia o ganaste el juego
    }
    function checkGameOver() {
// Verificar si ya no hay más movimientos posibles
        for (var i = 0; i < 5; i++) {//Este for recorre las filas
            for (var j = 0; j < 5; j++) {
                   //Este for recorre las casillas
                if (tablero[i][j] === null) {
  //Si alguna casilla está vacía te dice que no la perdiste.
                    return false;//Devuelve que no perdiste
                }
            }
        }
        // Verificar si hay movimientos posibles
        for (var i = 0; i < 5; i++) {//este for recorre las filas
            for (var j = 0; j < 5; j++) {
                      //este for recorre las casillas
                if (i < 4 && tablero[i][j] === tablero[i + 1][j]) {
  //Compara el valor de la celda que seleccione con la de abajo
//Si alguna celda coincide de valor significa que tiene algún movimiento
                    return false;//Devuelve que no perdiste
                }
                if (j < 4 && tablero[i][j] === tablero[i][j + 1]) {
//Compara que el valor de la celda que seleccione con la de la derecha
//Si alguna celda coincide de valor significa que tiene algún movimiento
                    return false;//Devuelve que no perdiste
                }
            }
        }
        alert("¡Perdiste! El juego se reiniciará.");
//Te sale una alerta que perdiste porque no tenes casillas vacías y no tenes valor iguales adyacentes.
        return true;//Devuelve que perdiste
    }
    function teclas(event) {//Cuando tocas una tecla
         // Cuando tocas alguna tecla pasa lo siguiente
        if (event.key === "ArrowUp") {
            //Si tocas la flechita para arriba
            moverArriba();//Llama al método mover arriba y
            Numaleatorio();// genera un número aleatorio
        }
        if (event.key === "ArrowDown") {
            //Si tocas la flechita para abajo
            moverAbajo(); //Llama al método mover abajo y
            Numaleatorio();//genera un número aleatorio
        }
        if (event.key === "ArrowLeft") {
        //Si tocas la flechita para la izquierda
            moverIzquierda();//Llama al método mover izquierda y
            Numaleatorio();//genera un número aleatorio
        }
        if (event.key === "ArrowRight") {
        //Si tocas la flechita para la izquierda
            moverDerecha();//Llama al método mover derecha y
            Numaleatorio();//genera un número aleatorio
        }
    }
    window.addEventListener("keydown", teclas);
    //Cuando se presione una tecla llama al método teclas
    function iniciarJuego() {//Cuando se toca el botón empezar
  abrir2();// Se abre el tablero
  if (empieza === 0) {//Si el juego no inicio aun
      Numaleatorio();//Genera el primer número aleatorio
      Numaleatorio();//Genera el segundo número aleatorio
      empieza = 1;//Avisa que el juego ya inició
  }
}
function Ganaste() {//Función que revisa si ganaste
 for (var i = 0; i < 5; i++) {//Es un for que recorre todas las filas
     for (var j = 0; j < 5; j++) {//Es un for que recorre las celdas
         if (tablero[i][j] === 2048) {
                             //Verifica si alguna celda vale 2048
             alert("¡Felicidades! Has ganado el juego.");//Te avisa que ganaste.
             return true; // Reinicia el juego
         }
     }
 }
 return false; // No reinicia el juego
   puntuacion++;
}
