const menuToggle = document.getElementById('menu-toggle');
        const navbar = document.getElementById('navbar');
        menuToggle.addEventListener('click', () => {
            navbar.classList.toggle('expanded');
        });




        const reversoImg = 'img/reverso.jpg'; /*Asignamos una imagen al reverso*/
        const imagenesOriginales = [
            'img/Brim.jpg', 'img/Chamber.jpg', 'img/Fenix.jpg', 'img/gekko.jpg',
            'img/jett.jpg', 'img/Key_0.jpg', 'img/omen.jpg', 'img/reina.jpg'
        ]; /*Cargamos las imágenes que se van a mostrar(Las parejas)*/
        let imagenes, cartasVolteadas, idCartasVolteadas, cartasEmparejadas, intentos, bloquearClicks;/*Creacion de variables*/
        function mezclar(array) {
            /*Esta función mezcla un array cualquiera que recibe */
            let copia = [...array];
            /*Crea un array llamado copia que va a ser igual al array que reciba y lo mezcla */
            for (let i = copia.length - 1; i > 0; i--) {
                /*Hace que la variable i sea igual a la posición del último objeto del array y mientras i sea mayor que 0 se va a repetir y por eso se va restando, usamos menos y no más para que nos quede más fácil mezclar cada elemento del array aleatoriamente */
                const j = Math.floor(Math.random() * (i + 1));
                /*Crea una variable j que va a ser igual a un número aleatorio entre 0 y el valor máximo del array*/
                [copia[i], copia[j]] = [copia[j], copia[i]];
                /*Esto hace que el array original cambie la última posición que es “i“ por la posición “j” que es la que sale aleatoriamente.*/
            }
            return copia;
            /* Devuelve el array mezclado*/
        }
        function iniciarJuego() {
            /*Cuando presionas el botón jugar se ejecuta lo siguiente*/
            const pares = [...imagenesOriginales, ...imagenesOriginales];
            /*Crea un array llamado pares y le agrega 2 veces las imágenes para formar los pares*/
            imagenes = mezclar(pares);/*Mezclamos el array imágenes*/
            cartasVolteadas = [];/*Creamos el array cartasVolteadas*/
            idCartasVolteadas = [];/*Creamos el array idCartasVolteadas*/
            cartasEmparejadas = new Array(imagenes.length).fill(false);
            /*Crea un array llamado cartasEmparejadas al cual le se asigna el largo del array imágenes y lo llena de valores false */
            intentos = 0;/* Crea la variable intentos y le da valor 0*/
            bloquearClicks = false;
            /*variable para bloquear los clicks mientras espera verificación de las parejas de cartas*/
            document.getElementById('mensaje').textContent = '';
            /*El texto con id mensaje imprime nada*/
            const tablero = document.getElementById('tablero');
            /*Obtiene el elemento con id tablero*/
            tablero.innerHTML = '';/*Elimina contenido previo*/
            for (let i = 0; i < 4; i++) {
                const fila = document.createElement('tr');
                /*Crea 4 filas*/
                for (let j = 0; j < 4; j++) {
                    /*Esto es para crear las 4 celdas de cada fila*/
                    const id = i * 4 + j;
                    /*Crea una variable id a la cual se le va a ir guardando el valor de la celda.*/
                    const celda = document.createElement('td');
                    /*va creando casillas*/
                    celda.id = id;
                    /*Asigna el valor del id de la celda*/
                    celda.onclick = () => voltearCarta(id);
                    /*Llama a la función volterCarta con el parámetro del id (Al hacer clic)*/
                    const img = document.createElement('img');
                    /*Crea dentro de la casilla la imagen de reverso*/
                    img.src = reversoImg; /*Le asigna a img la foto del reverso*/
                    celda.appendChild(img);
                    /*Coloca la imagen dentro de la casilla*/
                    fila.appendChild(celda);
                    /*Coloca la celda dentro de la fila*/
                }
                tablero.appendChild(fila);
                /*Coloca la fila dentro de la tabla*/
            }
        }
        function voltearCarta(i) {
            if (bloquearClicks || cartasEmparejadas[i] || idCartasVolteadas.includes(i)) return;
            /*Si alguna de esas 3 condiciones es verdadera regresa sin hacer nada*/
            /*Veamos bloquearClicks, esta se pone en true mientras haya 2 cartas seleccionadas al mismo tiempo, ósea mientras se verifica si la pareja es correcta*/
            /*Veamos cartasEmparejadas[i] esta función es true si la carta ya fue emparejada sino es false(vuelve sin seleccionar nada)*/
            /*Veamos idCartasVolteadas.includes(i) si la carta que seleccionamos ya la habíamos seleccionado previamente esta condición se vuelve true así no podemos elegir 2 veces la misma carta no tendría sentido*/
            const celda = document.getElementById(i);/*Crea una variable celda la cual es igual a la celda del tablero*/
            const img = celda.querySelector('img');/*Selecciona la imagen que corresponde a la casilla*/
            img.src = imagenes[i];/*Camiba la carta del reverso por la imagen de la casilla */
            cartasVolteadas.push(imagenes[i]);/*La carta volteada es guardada en el array cartas volteadas*/
            idCartasVolteadas.push(i);/*Agrega el id de la carta al array para luego compararlo*/
            if (cartasVolteadas.length === 2) {/*Si hay 2 cartas volteadas pasa lo siguiente*/
                intentos++;/*Se agrega un intento*/
                bloquearClicks = true;/*Hace que no se pueda seleccionar otra carta*/
                if (cartasVolteadas[0] === cartasVolteadas[1]) {/*Si las 2 cartas tienen el mismo id pasa lo siguiente:*/
                    setTimeout(() => {
                        idCartasVolteadas.forEach(id => {/*Para cada carta volteada*/
                            const celdaEmp = document.getElementById(id);/*Encontramos su id*/
                            celdaEmp.classList.add('emparejado');/*Agrega a la celda a la clase emparejado*/
                        });
                        cartasEmparejadas[idCartasVolteadas[0]] = true;/*Deja las 2*/
                        cartasEmparejadas[idCartasVolteadas[1]] = true;/*cartas como emparejadas*/
                        resetTurno();/*Termina el turno*/
                        comprobarFin();/*Comprueba si ese fue el último turno*/
                    }, 700);/*espera 700 milisegundos y ejecuta el código dentro */
                } else {
                    setTimeout(() => {
                        idCartasVolteadas.forEach(id => {/*Para cada carta (estas no son iguales)*/
                            const celda = document.getElementById(id);/*Selecciona la celda*/
                            const img = celda.querySelector('img');/*le cambia la imagen a la de la variable img*/
                            img.src = reversoImg;/*A img le pone la imagen del reverso*/
                        });
                        resetTurno();/*Termina el turno*/
                    }, 1000);/*Espera 1 segundo y ejecuta el código */
                }
            }
        }
        function resetTurno() {/*Cuando termina el turno pasa lo siguiente:*/
            cartasVolteadas = [];/*Vaciamos el array con las cartas volteadas*/
            idCartasVolteadas = [];/*Vaciamos el array con los ids de las cartas volteadas*/
            bloquearClicks = false;/*Habilitados los clicks en las cartas*/
        }
        function comprobarFin() {
            if (cartasEmparejadas.every(val => val)) {/*Si todas las cartas están emparejadas*/
                document.getElementById('mensaje').textContent = `¡Felicidades! Terminaste en ${intentos} intentos.`;/*Te da un mensaje de felicidades y cuántos intentos te tarda*/
            }
        }
        function reiniciarJuego() {/*Al presionar el botón “reinicia” el juego (lo inicia de nuevo)*/
            iniciarJuego();
        }
