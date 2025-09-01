// Simulación para ingresar a grupo
	document.getElementById('ingresar-grupo-form').addEventListener('submit', function (e) {
  	e.preventDefault();
  	const grupo = document.getElementById('grupo').value.trim();
  	const password = document.getElementById('password').value;

  	if (grupo === "Los Físicos" && password === "fisica123") {
    	alert("Bienvenido al grupo " + grupo + "!");
    	window.location.href = "grupos.html";
  	} else {
    	alert("Grupo o contraseña incorrectos.");
  	}
	});

	// Simulación para crear grupo
	document.getElementById('crear-grupo-form').addEventListener('submit', function (e) {
  	e.preventDefault();
  	const nuevoGrupo = document.getElementById('nuevo-grupo').value.trim();
  	const claveGrupo = document.getElementById('clave-grupo').value;

  	if (nuevoGrupo.length >= 3 && claveGrupo.length >= 4) {
    	alert("Grupo creado con éxito: " + nuevoGrupo);
    	// Aquí podrías guardar en localStorage o redirigir
  	} else {
    	alert("Por favor, completá los campos correctamente.");
  	}
	});
  