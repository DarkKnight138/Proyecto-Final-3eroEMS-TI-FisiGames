(function(){
  const doors = Array.from(document.querySelectorAll('.door'));
  const message = document.getElementById('message');
  const btnCambiar = document.getElementById('btn-cambiar');
  const btnMantener = document.getElementById('btn-mantener');
  const btnReiniciar = document.getElementById('btn-reiniciar');
  const menuToggle = document.getElementById('menu-toggle');
  const navbar = document.getElementById('navbar');

  menuToggle?.addEventListener('click', ()=> navbar.classList.toggle('expanded'));

  let prizeDoor = null;
  let playerChoice = null;
  let openedDoor = null;
  let stage = 'idle';

  function initGame(){
    prizeDoor = Math.floor(Math.random()*3);
    playerChoice = null;
    openedDoor = null;
    stage = 'pick';

    doors.forEach((d,i)=>{
      d.className = 'door';
      d.textContent = '🚪';
      d.onclick = () => elegirPuerta(i);
    });
    btnCambiar.style.display = 'none';
    btnMantener.style.display = 'none';
    btnReiniciar.style.display = 'none';
    message.textContent = 'Elige una puerta para empezar.';
  }

  function elegirPuerta(index){
    if(stage !== 'pick') return;
    playerChoice = index;
    doors.forEach((d,i)=> d.classList.toggle('selected', i===playerChoice));
    abrirPuertaAnfitrion();
  }

  function abrirPuertaAnfitrion(){
    const candidatos = [0,1,2].filter(i => i !== playerChoice && i !== prizeDoor);
    openedDoor = candidatos[Math.floor(Math.random()*candidatos.length)];
    const d = doors[openedDoor];
    d.textContent = '🐐';
    d.classList.add('opened','goat');
    d.onclick = null;

    stage = 'opened';
    message.textContent = 'El anfitrión abrió una puerta. ¿Quieres cambiar tu elección?';
    btnCambiar.style.display = 'inline-block';
    btnMantener.style.display = 'inline-block';

    btnCambiar.onclick = () => finalizar(true);
    btnMantener.onclick = () => finalizar(false);
  }

  function finalizar(usarCambio){
    if(stage !== 'opened') return;
    let finalChoice = playerChoice;
    if(usarCambio){
      finalChoice = [0,1,2].find(i => i !== playerChoice && i !== openedDoor);
    }
    revealAll(finalChoice);
  }

  function revealAll(finalChoice){
    doors.forEach((d,i)=>{
      d.classList.remove('selected');
      d.classList.add('opened');
      d.onclick = null;
      if(i === prizeDoor){
        d.textContent = '🎁';
        d.classList.add('prize');
      } else {
        d.textContent = '🐐';
        d.classList.add('goat');
      }
    });

    const win = finalChoice === prizeDoor;
    if(win){
      message.textContent = '¡Ganaste!(+20 puntos)';
      sumarPuntos(20); 
    } else {
      message.textContent = 'Perdiste.';
    }

    btnCambiar.style.display = 'none';
    btnMantener.style.display = 'none';
    btnReiniciar.style.display = 'inline-block';
    stage = 'result';
    btnReiniciar.onclick = initGame;
  }

  function sumarPuntos(puntos){
    fetch("../../backend/controladores/actualizar_puntos.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "puntos=" + puntos
    })
    .then(res => res.text())
    .then(data => console.log("Servidor:", data))
    .catch(err => console.error("Error al enviar puntos:", err));
  }

  initGame();
})();
