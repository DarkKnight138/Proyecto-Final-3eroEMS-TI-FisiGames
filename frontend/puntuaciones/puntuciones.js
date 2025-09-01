  const rankingJugadores = document.getElementById("ranking-jugadores");
  const rankingGrupos = document.getElementById("ranking-grupos");
  const switchBtn = document.querySelector(".switch-button");


  function toggleRanking() {
    const showingJugadores = !rankingJugadores.classList.contains("hidden");
    if (showingJugadores) {
      rankingJugadores.classList.add("hidden");
      rankingGrupos.classList.remove("hidden");
      switchBtn.textContent = "Cambiar a vista de Jugadores";
    } else {
      rankingJugadores.classList.remove("hidden");
      rankingGrupos.classList.add("hidden");
      switchBtn.textContent = "Cambiar a vista de Grupos";
    }
  }
